<?php

return [
    // path to save sitemap files
    'output' => [
        'disk' => 'public',
        'path' => '/sitemap',
    ],
    // entity collections for sitemap generation
    'collections' => [
        \Larapress\Sitemap\Services\Sitemap\EntityCollections\PagesEntityCollection::class => [
            'chunk' => 100,
            'priority' => 0.7,
            'changefreq' => 'monthly',
        ],
        \Larapress\Sitemap\Services\Sitemap\EntityCollections\ProductsEntityCollection::class => [
            'chunk' => 100,
            'path' => '/shop/product/{id}',
            'pathPlaceholder' => '{id}',
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ],
        \Larapress\Sitemap\Services\Sitemap\EntityCollections\ProductCategoriesEntityCollection::class => [
            'chunk' => 100,
            'path' => '/shop/category/{id}',
            'pathPlaceholder' => '{id}',
            'priority' => 0.9,
            'changefreq' => 'weekly',
        ],
    ],
    // date format for displaying in xml
    'dateformat' => 'Y-m-d',
];
