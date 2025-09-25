<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('APP_NAME'),
        'short_name' => env('APP_NAME'),
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '180x180' => [
                'path' => '/pwa/apple-icon-180.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/pwa/manifest-icon-192.maskable.png',
                'purpose' => 'maskable'
            ],
            '196x196' => [
                'path' => '/pwa/favicon-196.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/pwa/manifest-icon-512.maskable.png',
                'purpose' => 'maskable'
            ],
        ],
        'splash' => [
            '640x1136' => '/pwa/apple-splash-640-1136.jpg',
            '750x1334' => '/pwa/apple-splash-750-1334.jpg',
            '828x1792' => '/pwa/apple-splash-828-1792.jpg',
            '1125x2436' => '/pwa/apple-splash-1125-2436.jpg',
            '1242x2208' => '/pwa/apple-splash-1242-2208.jpg',
            '1242x2688' => '/pwa/apple-splash-1242-2688.jpg',
            '1536x2048' => '/pwa/apple-splash-1536-2048.jpg',
            '1668x2224' => '/pwa/apple-splash-1668-2224.jpg',
            '1668x2388' => '/pwa/apple-splash-1668-2388.jpg',
            '2048x2732' => '/pwa/apple-splash-2048-2732.jpg',
        ],
        // 'shortcuts' => [
        //     [
        //         'name' => 'Shortcut Link 1',
        //         'description' => 'Shortcut Link 1 Description',
        //         'url' => '/shortcutlink1',
        //         'icons' => [
        //             "src" => "/pwa/icon-72x72.png",
        //             "purpose" => "any"
        //         ]
        //     ],
        //     [
        //         'name' => 'Shortcut Link 2',
        //         'description' => 'Shortcut Link 2 Description',
        //         'url' => '/shortcutlink2'
        //     ]
        // ],
        'custom' => []
    ]
];
