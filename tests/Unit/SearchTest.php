<?php

namespace AhmetBarut\FullSearch\Tests\Unit;

use AhmetBarut\FullSearch\Tests\TestCase;

class SearchTest extends TestCase
{
    public function testSearch()
    {
        $this->get($this->app['config']->get('fullsearch.route') . '?q=laravel')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    '*' => [
                        'title',
                        'url',
                        'page',
                    ]
                ]
            ]);
    }
}
