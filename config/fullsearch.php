<?php

use Illuminate\Database\Eloquent\Model;

return [
    'models' => [
        Model::class => [
            'searchable' => true,
            'auth' => false, // false or middleware name example: auth => 'auth',
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
    ]
];
