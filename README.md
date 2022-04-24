# Laravel Full Search

Allows searching on multiple models. The advantage of this for us; a separate search field is not required for each model.

## Features

- Mutli-model search
- Search on multiple fields
- definition of routes

## Installation

```shell
    composer require ahmetbarut/laravel-full-search
```

## Configuration

All configurations related to the model are made from the `config/fullsearch.php` file.

### published config

```shell
    php artisan vendor:publish --provider="AhmetBarut\\LaravelFullSearch\\Providers\\LaravelFullSearchServiceProvider" --tag="fullsearch-config"
```

### view side

The `@stack('scripts')` directive must be defined on the view side, otherwise it will not work.

```html
<body>
+    <x-fullsearch />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
+    @stack('scripts')
</body>

</html>
```

> If you are using macos, you can open the search window with the cmd + K key combination.

## Usage

You need to configure the `config/fullsearch.php` file for use.

```php
    // config/fullsearch.php
use App\Models\Post;

return [
    'models' => [
        Post::class => [
            'searchable' => true,
            'ability' => false, // false or middleware name example: auth => 'auth',
            'searchable_fields' => [
                'title',
                'body',
                'id',
            ],
            'response_parameters' => [
                'title' => 'title',
                'page' => 'Posts',
            ],
            'route' => [
                'name' => 'post',
                'parameters' => [
                    'post' => 'id',
                ],
            ],
            'max_results' => 10,
        ],
    ],
];
```

In this way, you can search the `title`, `id` and `body` fields on the `Post` model. You can set the number of search results with the `max_results` parameter.

`searchable` => if `true` then search is possible.
`ability` => if `false` then search is possible. If you want to restrict some models for certain users, you need to use `Laravel Gate`. I will explain it below.
`searchable_fields` => specifies searchable fields.
`response_parameters` => specifies the parameters to be displayed in the search result.
`route` => specifies the routes to show in the search result.
`max_results` => specifies the number of search results.

## Restrict Specific Users

```php
    // app/Providers/AuthServiceProvider.php
use Illuminate\Support\Facades\Gate;

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
```

```php
    // config/fullsearch.php
use App\Models\User;

return [
    'models' => [
        User::class => [
            'searchable' => true,
            'ability' => 'admin',
            'searchable_fields' => [
                'name',
                'email',
                'id',
            ],
            'response_parameters' => [
                'name' => 'name',
                'page' => 'Users',
            ],
            'route' => [
                'name' => 'user',
                'parameters' => [
                    'user' => 'id',
                ],
            ],
            'max_results' => 10,
        ],
    ],
];
```

Users must have 'admin' authority to search on them. If you do not have admin privileges, you cannot search. It does not return an error message that it cannot search, it just returns an empty array.

Let's talk about `response_parameters` and `route['parameters']`. The `response_parameters` parameter specifies the parameters to be displayed in the search result. The `route['parameters']` parameter specifies the routes to be displayed in the search result. If you have noticed now, the `name` column has come across the `name` parameter in the `response_parameters`, so the `name` column will be shown as a return. If we want, we can `email` it.

```php
    // config/fullsearch.php
    'searchable_fields' => [
        'name',
        'email',
        'id',
    ],
    'response_parameters' => [
-       'name' => 'name',
+       'name' => 'email',
        'page' => 'Users',
    ],
```

> The column to be displayed must be defined in `searchable_fields`.

Now let's deepen on `route['parameters']`. Similar usage exists in `response_parameters`.

```php
...
        // config/fullsearch.php
    'searchable_fields' => [
        'name',
        'email',
        'id',
    ],
    'route' => [
        'name' => 'user',
        'parameters' => [
-           'user' => 'id',
+           'user' => 'email',
        ],
    ],
...
```

It will be like this `/user/user@email.com`.
