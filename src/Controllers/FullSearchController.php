<?php

namespace AhmetBarut\FullSearch\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FullSearchController extends Controller
{
    /**
     * stored full-search config
     * @var array
     */
    protected array $config;

    /**
     * stored all results
     * @param Collection $results
     */
    protected Collection $results;

    /**
     * Stored searchable models
     * @var Collection
     */
    protected Collection $models;

    /**
     * Stored active model
     * @var Collection
     */
    protected string $activeModel = '';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->config = config('fullsearch');

        $this->results = collect([]);

        $this->models = collect($this->config['models']);
    }


    /**
     * return all models
     * @throws \Exception
     * @return array
     */
    protected function models(): array
    {
        return $this->config['models'] ?? throw new Exception('FullSearch models not found.');
    }

    /**
     * Return only searchable models attributes
     * @return array
     */
    protected function model(string $model): array
    {
        return $this->models()[$model];
    }

    /**
     * return results
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function results(Request $request): \Illuminate\Http\JsonResponse
    {
        $key = $request->get('q');
        $models = $this->models();

        // What we're doing here is navigating the
        // searchable models with a foreach loop.
        // In the model with the record we return 3 fields, url, title and page.
        foreach ($models as $model => $attributes) {
            $this->activeModel = $model;

            if ($this->isSearchable() === true && $this->isAbility() === true) {
                $fields = $this->get('searchable_fields');

                $model::query()->orWhere(function ($query) use ($fields, $key) {
                    foreach ($fields as $field) {
                        $query->orWhere($field, 'like', '%' . $key . '%');
                    }
                })
                    ->limit($this->get('max_results'))
                    ->get()
                    ->each(
                        function ($queryResult) {
                            $this->results->push([
                                'title' => $queryResult->{$this->get('response_parameters')['title']},
                                'url' => $this->parseRoute(
                                    $this->parseParameters($queryResult->toArray())
                                ),
                                'page' => $this->get('response_parameters')['page']
                            ]);
                        }
                    );
            }
        }
        return response()->json([
            'results' => $this->results->count() > 0 ? $this->results->toArray() : []
        ]);
    }

    /**
     * Check if model is searchable
     * @return bool
     */
    protected function isSearchable(): bool
    {
        return $this->get('searchable') ?? false;
    }

    /**
     * get model attribute
     * @param string $key
     * @return mixed
     */
    protected function get(string $key): mixed
    {
        return $this->models()[$this->activeModel][$key];
    }

    /**
     * Populates parameters according to the route.
     * @param array $parameters
     * @return string
     */
    protected function parseRoute(array $parameters): string
    {
        return route($this->get('route')['name'], $parameters);
    }

    /**
     * Parses the parameters and prepares them for forwarding to the route.
     * @param array $parameters
     * @return array
     */
    protected function parseParameters(array $parameters): array
    {
        $routeParameter = $this->get('route')['parameters'];

        $matchedParameters = [];

        foreach ($routeParameter as $key => $value) {
            $matchedParameters[$key] = $parameters[$value];
        }

        return $matchedParameters;
    }

    /**
     * Check if model has ability
     * @return bool
     */
    protected function isAbility(): bool
    {
        $ability = $this->get('ability');

        if ($ability === false || $ability === null) {
            return true;
        }

        return Gate::allows($ability);
    }
}
