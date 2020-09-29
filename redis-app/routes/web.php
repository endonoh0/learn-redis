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

Route::get('/', function () {
    // Fetch database query from memory if key exists
    if (Redis::exists('articles.all')) {
        return json_decode(Redis::get('articles.all'));
    };

    // Perform database query
    $articles = App\Article::all();
    // Put into Redis
    Redis::set('article.all', $articles);

    return $articles;
});
