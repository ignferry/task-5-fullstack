<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $apiData = [];

        if (request('page')) {
            $apiData['page'] = request('page');
        }

        $response = Http::get(env('API_URL') . 'articles', $apiData);

        if (!$response->successful()) {
            return abort($response->status());
        }

        $responseData = json_decode($response);

        $articles = $responseData->data;
        $paginationData = $responseData->meta;

        return view('home.home', [
            'articles' => $articles,
            'pagination' => $paginationData
        ]);
    }

    public function show(Article $article)
    {
        $response = Http::get(env('API_URL') . 'articles/' . $article->id);

        if (!$response->successful()) {
            return abort($response->status());
        }

        $article = json_decode($response);

        return view('home.show', [
            'article' => $article
        ]);
    }
}
