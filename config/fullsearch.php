<?php

use Illuminate\Database\Eloquent\Model;

return [
    'models' => [
        Model::class => [
            'searchable' => true,
            'ability' => false,
            'searchable_fields' => [
                'title',
                'summary',
                'content',
                'slug',
            ],
            'url_parameter_name' => 'slug',
            'response_parameters' => [
                'title' => 'title',
                'url' => 'news/{slug}',
                'page' => 'news'
            ]
        ],
    ],
    'route' => 'full-search/results',
];
