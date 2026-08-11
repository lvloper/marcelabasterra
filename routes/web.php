<?php

use App\Http\Controllers\RouteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\PublicationController;
use Illuminate\Support\Facades\Route;

Route::get('/search-block', function () {
    $block = request('block');

    $pages = \App\Models\Page::where('blocks', 'like', "%{$block}%")->with('route')->get();

    foreach ($pages as $page) {
        if ($page->route) {
            echo "<a href='" . $page->route->getFullPath() . "'>" . $page->route->title . "</a><br>";
        }
    }
});

Route::get('/preview-blocks/{type?}', function (?string $type = null) {
    abort_unless(app()->isLocal(), 404);

    if (app()->bound('debugbar')) {
        app('debugbar')->disable();
    }

    if (blank($type)) {
        return view('components.blockLayout-minimal', ['slot' => '']);
    }

    $availableBlocks = collect(\App\Filament\Templates\DefaultTemplate::blocks())
        ->keyBy(fn ($block) => $block->getName());

    abort_unless($availableBlocks->has($type), 404);

    $storedBlock = \App\Models\Page::query()
        ->get(['blocks'])
        ->flatMap(fn (\App\Models\Page $page) => $page->blocks ?? collect())
        ->first(fn ($block) => ($block['type'] ?? null) === $type);

    $data = $storedBlock['data'] ?? [];
    $data['id'] = 'preview-' . \Illuminate\Support\Str::slug($type);
    $data['preview'] = true;

    $html = view('blocks.' . $type, $data)->render();
    $slot = new \Illuminate\Support\HtmlString(
        '<div class="block block-' . e($type) . '">' . $html . '</div>'
    );

    return view('components.blockLayout-minimal', compact('slot'));
})
->name('preview.blocks');

Route::get('/preview-blocks-minimal', function () {
    return view('components.blockLayout-minimal', ['slot' => '']);
})
->name('preview.blocks.minimal');

Route::redirect('/login', '/admin/login')->name('login');

Route::get('/home', function () {
    return redirect('/');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::get('/publicaciones', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publicaciones/libros', [PublicationController::class, 'books'])->name('publications.books');
Route::get('/publicaciones/articulos-academicos', [PublicationController::class, 'articles'])->name('publications.articles');

Route::post('/contact/send', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string|max:5000',
        'recipient' => 'required|email',
    ]);

    \Illuminate\Support\Facades\Mail::raw(
        "Nombre: {$validated['name']}\nEmail: {$validated['email']}\n"
        . ($validated['phone'] ? "Telefono: {$validated['phone']}\n" : '')
        . ($validated['subject'] ? "Asunto: {$validated['subject']}\n" : '')
        . "\nMensaje:\n{$validated['message']}",
        function ($message) use ($validated) {
            $message->to($validated['recipient'])
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject('Contacto web: ' . ($validated['subject'] ?? 'Sin asunto'));
        }
    );

    return response()->json(['ok' => true]);
})->name('contact.send');

Route::get('/{slug?}', [RouteController::class, 'show'])
    ->where('slug', '.*')
    ->name('route.show');
