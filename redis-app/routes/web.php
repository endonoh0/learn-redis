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

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redis;

Route::get('articles/trending', function () {
    // Get the top 3 trending articles
    $trending = Redis::zrevrange('trending_articles', 0, 2);

    // Fetch all articles by id
    App\Article::whereIn('id', $trending);

    // Decode json and rehyrate
    // hyrdate accepts arr of records, filters each one, creates new instance while passing through the attributes
    App\Article::hydrate(
        array_map('json_decode', $trending)
    );

    // You may also create a global query scope
    App\Article::hydrateFromJsonString($trending);

    return $trending;
});

Route::get('articles/{article}', function (App\Article $article) {
    // increment trending_articles score by 1, set member to the article id
    Redis::zincrby('trending_articles', 1, $article->id);

    // If you don't want to store the id, you can store the article which will be cast to json in redis-cli
    Redis::zincrby('trending_articles', 1, $article);

    // Remove items from trending_articles sorted set and retain the top 3 items
    // You can go to kernel.php to set up a schedule redis command that runs once a week/month
    Redis::zremrangebyrank('trending_articles', 0, -4);

    return $article;
});
