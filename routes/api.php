<?php

use Illuminate\Support\Facades\Route;

Route::get(
    config('fullsearch.route'),
    'AhmetBarut\FullSearch\Controllers\FullSearchController@results'
)->name('ahmetbarut.full-search');
