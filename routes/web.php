<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/db', function () {
    try {
        DB::connection()->getPdo();
        return " Database connected successfully.";
    } catch (\Exception $e) {
        return " Could not connect to the database. Error: " . $e->getMessage();
    }
});
Route::get('/', function () {
    return view('welcome');
});
