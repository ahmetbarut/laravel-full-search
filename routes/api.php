<?php

use AhmetBarut\FullSearch\Controllers\FullSearchController;
use Illuminate\Support\Facades\Route;

Route::get(
    config('fullsearch.route'),
    [FullSearchController::class, 'results']
)->name('ahmetbarut.full-search');
