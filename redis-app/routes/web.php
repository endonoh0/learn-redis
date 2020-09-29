<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|----------------- ---------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

function remeber($key, $min, $callback)
{
    // If this value exists in cache, fetch the results form the database query
    if ($value = Redis::get('articles.all')) {
        return json_decode($value);
    };
    // Perform database query
    $value = $callback();
    // Set a value with an expiration date of 60 seconds
    Redis::setex($key, $min, $value);

    return $value;
}

Route::get('/', function () {
    remeber('articles.all', 60 * 60, function () {
        return App\Articles::all();
    });
});
