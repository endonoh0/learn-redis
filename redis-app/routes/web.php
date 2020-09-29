<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class Articles
{
    public function all()
    {
        return Cache::remember('articles.all', 60 * 60, function () {
            return App\Articles::all();
        });
    }
}
Route::get('/', function (Articles $articles) {
    return $articles->all();
});
