<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Class decorator that turns caching on and off
class CacheableArticles
{
    protected $articles;

    // constructor gives instance of Articles class
    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    public function all()
    {
        // Decorating logic with caching layer
        return Cache::remember('articles.all', 60 * 60, function () {
            return $this->articles->all();
        });
    }
}

class Articles
{
    public function all()
    {
        return App\Articles::all();
    }
}

Route::get('/', function () {
    $articles = new CacheableArticles(new Articles);

    return $articles->all();
});
