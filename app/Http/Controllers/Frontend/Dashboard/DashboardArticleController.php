<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Limit articles visible to normal users
        $apiData = [];
        if (auth()->user()->type == 0) {
            $apiData['user_id'] = auth()->user()->id;
        }

        // Pagination
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

        return view('dashboard.articles.index', [
            'articles' => $articles,
            'pagination' => $paginationData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get categories
        $categoriesResponse = Http::get(env('API_URL') . 'categories');

        if (!$categoriesResponse->successful()) {
            return back()->withErrors;
        }

        $categories = json_decode($categoriesResponse);

        return view('dashboard.articles.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $apiData = $request->all();
        $apiData['user_id'] = auth()->user()->id;

        $response = '';

        if ($request->file('image')) {
            $response = Http::withToken(request()->cookie('token'))
                            ->attach('image', file_get_contents($request->file('image')), 'image')
                            ->post(env('API_URL') . 'articles', $apiData);
        }
        else {
            $response = Http::withToken(request()->cookie('token'))
                            ->post(env('API_URL') . 'articles', $apiData);
        }

        if (!$response->successful()) {
            return abort($response->status());
        }

        return redirect('/dashboard/articles')->with('message', 'Article created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $response = Http::get(env('API_URL') . 'articles/' . $article->id);

        if (!$response->successful()) {
            return abort($response->status());
        }

        $article = json_decode($response);

        if ($article->user->id != auth()->user()->id && auth()->user()->type == 0) {
            return abort(404);
        }

        return view('dashboard.articles.show', [
            'article' => $article
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        // Get categories
        $categoriesResponse = Http::get(env('API_URL') . 'categories');

        if (!$categoriesResponse->successful()) {
            return redirect('/dashboard');
        }

        $categories = json_decode($categoriesResponse);

        // Get old article data
        $response = Http::get(env('API_URL') . 'articles/' . $article->id);

        if (!$response->successful()) {
            return abort(404);
        }

        $article = json_decode($response);

        // Only admin and article owner can edit
        if ($article->user->id != auth()->user()->id && auth()->user()->type == 0) {
            return abort(404);
        }

        return view('dashboard.articles.edit', [
            'categories' => $categories,
            'article' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $apiData = $request->all();
        $apiData['user_id'] = auth()->user()->id;
        $apiData['_method'] = 'PUT';

        if ($request->file('image')) {
            $response = Http::withToken(request()->cookie('token'))
                            ->attach('image', file_get_contents($request->file('image')), 'image')
                            ->post(env('API_URL') . 'articles/' . $article->id, $apiData);
        }
        else {
            $response = Http::withToken(request()->cookie('token'))
                            ->put(env('API_URL') . 'articles/' . $article->id, $apiData);
        }

        if (!$response->successful()) {
            return back()->withErrors;
        }

        return redirect('/dashboard/articles')->with('message', 'Article updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $response = Http::withToken(request()->cookie('token'))->delete(env('API_URL') . 'articles/' . $article->id);

        if (!$response->successful()) {
            return abort($response->status());
        }

        return redirect('/dashboard/articles')->with('message', 'Article deleted successfully');
    }
}
