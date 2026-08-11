<?php

// (config('admin.richEditor.basic')
return [
    'richEditor' => [
        'basic' => [
            'bold',
            'italic',
            'link',
            'underline',
        ],
        'advanced' => [
            'attachFiles',
            'blockquote',
            'bold',
            'bulletList',
            'codeBlock',
            'customBlocks',
            'h2',
            'h3',
            'italic',
            'link',
            'orderedList',
            'redo',
            'strike',
            'underline',
            'undo',
        ],
    ],
    'contentMedia' => [
        'disk' => env('CONTENT_MEDIA_DISK', 'public'),
        'visibility' => 'public',
        'imageDirectory' => 'blog/content/images',
        'videoDirectory' => 'blog/content/videos',
        'posterDirectory' => 'blog/content/posters',
        'imageMimeTypes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'videoMimeTypes' => ['video/mp4', 'video/webm'],
        'imageMaxSize' => 10240,
        'videoMaxSize' => 30720,
    ],
];
