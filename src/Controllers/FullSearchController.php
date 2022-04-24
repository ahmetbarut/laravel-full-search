<?php

namespace AhmetBarut\FullSearch\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FullSearchController extends Controller
{
    public function results(Request $request)
    {
        $key = $request->get('q');
        $models = config('full-search.models');

        $results = collect();

        foreach ($models as $model => $attributes) {
            if ($attributes['searchable'] == true) {
                $fields = $models[$model]['searchable_fields'];
                $model::where(function ($query) use ($fields, $key) {
                    foreach ($fields as $field) {
                        $query->where($field, 'like', '%' . $key . '%');
                    }
                })
                    ->get()
                    ->each(
                        function ($response) use ($models, $model, &$results) {
                            $results->push([
                                'title' => $response->{$models[$model]['response_parameters']['title']},
                                'url' => Str::of($models[$model]['response_parameters']['url'])
                                    ->replace(
                                        '{' . $models[$model]['url_parameter_name'] . '}',
                                        $response->{$models[$model]['url_parameter_name']}
                                    ),
                                'page' => $models[$model]['response_parameters']['page']
                            ]);
                        }
                    );
            }
        }

        return response()->json([
            'results' => $results->count() > 0 ? $results->toArray() : []
        ]);
    }
}
