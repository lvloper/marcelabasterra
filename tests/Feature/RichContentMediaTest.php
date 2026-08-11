<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\MediaBlock;
use App\Models\Blog;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Tests\TestCase;

final class RichContentMediaTest extends TestCase
{
    public function test_blog_registers_its_rich_content_configuration(): void
    {
        $blog = new Blog;

        $this->assertTrue($blog->hasRichContentAttribute('content'));
        $this->assertSame('public', $blog->getRichContentAttribute('content')?->getFileAttachmentsDiskName());
        $this->assertContains(MediaBlock::class, $blog->getRichContentAttribute('content')?->getCustomBlocksConfig() ?? []);
    }

    public function test_media_block_renders_a_safe_video_with_controls(): void
    {
        $config = htmlspecialchars(json_encode([
            'type' => 'video',
            'video' => 'blog/content/videos/example.webm',
            'poster' => 'blog/content/posters/example.jpg',
            'caption' => 'Presentación institucional',
        ], JSON_THROW_ON_ERROR), ENT_QUOTES);

        $content = <<<HTML
        <div data-type="customBlock" data-id="media" data-config="{$config}"></div>
        HTML;

        $html = RichContentRenderer::make($content)
            ->customBlocks([MediaBlock::class])
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsVisibility('public')
            ->toHtml();

        $this->assertStringContainsString('<video controls', $html);
        $this->assertStringContainsString('playsinline', $html);
        $this->assertStringContainsString('type="video/webm"', $html);
        $this->assertStringContainsString('/storage/blog/content/videos/example.webm', $html);
        $this->assertStringNotContainsString('autoplay', $html);
    }
}
