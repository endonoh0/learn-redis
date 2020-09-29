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

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user3Stats = [
        'favorites' => 10,
        'watchLaters' => 20,
        'completions' => 35
    ];
    // Add hash to database
    Redis::hmset('user.3.stats', $user3Stats);
    // Display to screen
    return Redis::hgetall('user.3.stats');

    // Cache
    Cache::put('foo', 'bar', 10);
    return Cache::get('foo');

    // Return favorites count for this user
    return Redis::hgetall('user.1.stats')['favorites'];
});

Route::get('users/{id}/stats', function ($id) {
    // Fetch stats from the user with the given id
    return Redis::hgetall("user.${id}.stats");
});

Route::get('favorite-video', function () {
    // Increment counter
    Redis::hincrby('user.1.stats', 'favorites', 1);

    return redirect('/');
});
