<?php

namespace AhmetBarut\FullSearch\Tests;

use AhmetBarut\FullSearch\Providers\FullSearchServiceProvider;
use AhmetBarut\FullSearch\Tests\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            FullSearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/Database/migrations'),
        ]);

        $this->artisan('migrate', ['--database' => 'testing'])->run();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--database' => 'testing'])->run();
        });

        Post::factory()->count(10)->create([
            'title' => 'Laravel' . time(),
            'summary' => 'Laravel' . time(),
            'content' => 'Laravel' . time(),
            'slug' => 'laravel' . time(),
        ]);
    }

    /**
     * @param \Illuminate\Routing\Router $router
     */
    protected function defineRoutes($router)
    {
        $router->get('/post/{post}', function (Post $post) {
            return $post;
        })->name('post');
    }
}
