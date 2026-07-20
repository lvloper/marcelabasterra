<?php

use App\Http\Controllers\RouteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
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

Route::get('/preview-blocks', function () {
    return view('components.blockLayout', ['slot' => '', 'hideFooter' => true, 'hideHeader' => true]);
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
