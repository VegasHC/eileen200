<?php

use Statamic\Facades\Term;
use Statamic\Support\Arr;

return [

    /*
    |--------------------------------------------------------------------------
    | Default search index
    |--------------------------------------------------------------------------
    |
    | This option controls the search index that gets queried when performing
    | search functions without explicitly selecting another index.
    |
    */

    'default' => env('STATAMIC_DEFAULT_SEARCH_INDEX', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Search Indexes
    |--------------------------------------------------------------------------
    |
    | Here you can define all of the available search indexes.
    |
    */

    'indexes' => [

        'default' => [
            'driver' => 'local',
            'searchables' => 'all',
            'fields' => ['title'],
        ],
        'providers' => [
            'driver' => 'algolia',
            'searchables' => 'collection:providers',
            'fields' => [
                'id', 'title', 'test', 'org_name', 'first_name', 'middle_name', 'last_name', 'suffix_name', '_geoloc', 'location', 'sponsored', 'phone', 'fax', 'website',
                'address', 'city', 'state', 'zip', 'image', 'email', 'license_type', 'services', 'category', 'service_category', 'description', 'gallery', 'video', 'insurance_accepted',
                'promotion_level',

            ],
            'transformers' => [

                // Return a value to store in the index.
                'description' => function ($description) {
                    return Statamic\Facades\Markdown::parse((string) $description);
                },
                'insurance_accepted' => function ($text) {
                    return Statamic\Facades\Markdown::parse((string) $text);
                },

                'services' => function ($services) {
                    $newServices = [];

                    foreach (Arr::wrap($services) as $key) {
                        $service = Term::findBySlug($key, 'services');
                        if ($service) {
                            $newServices[] = $service->get('title');
                        } else {
                            var_dump($key);
                        }
                    }

                    if (empty($newServices)) {
                        $newServices = $services;
                    }

                    return ['services' => $newServices];
                },

            ],

        ],

        // 'blog' => [
        //     'driver' => 'local',
        //     'searchables' => 'collection:blog',
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Driver Defaults
    |--------------------------------------------------------------------------
    |
    | Here you can specify default configuration to be applied to all indexes
    | that use the corresponding driver. For instance, if you have two
    | indexes that use the "local" driver, both of them can have the
    | same base configuration. You may override for each index.
    |
    */

    'drivers' => [

        'local' => [
            'path' => storage_path('statamic/search'),
        ],

        'algolia' => [
            'credentials' => [
                'id' => env('ALGOLIA_APP_ID', ''),
                'secret' => env('ALGOLIA_SECRET', ''),
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Search Defaults
    |--------------------------------------------------------------------------
    |
    | Here you can specify default configuration to be applied to all indexes
    | regardless of the driver. You can override these per driver or per index.
    |
    */

    'defaults' => [
        'fields' => ['title'],
    ],

];
