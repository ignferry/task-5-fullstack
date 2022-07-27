<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Helper\RequestApi;

class DashboardArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiData = [];
        if (auth()->user()->type == 0) {
            $apiData['user_id'] = auth()->user()->id;
        }

        if (request('page')) {
            $apiData['page'] = request('page');
        }

        $apiCall = RequestApi::callAPI('GET', 'articles', $apiData, false);

        if (!$apiCall->success) {
            return view('dashboard.index', [
                'message' => $apiCall->message
            ]);
        }

        $articles = $apiCall->data->data;
        $paginationData = $apiCall->data;
        $paginationData->data = null;

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
        $apiCall = RequestApi::callAPI('GET', 'categories', [], false);

        if (!$apiCall->success) {
            return view('dashboard.index', [
                'message' => $apiCall->message
            ]);
        }

        $categories = $apiCall->data;
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
        $apiCall = RequestApi::callAPI('POST', 'articles', $apiData, true);

        if ($apiCall->message == "Unauthenticated.") {
            return view('home.home', [
                'message' => $apiCall->message
            ]);
        }

        return redirect('/dashboard/articles')->with('message', $apiCall->message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $apiCall = RequestApi::callAPI('GET', 'articles/' . $article->id, [], false);

        if (!$apiCall->success) {
            return back()->with('message', $apiCall->message);
        }

        $article = $apiCall->data;

        if ($article->user_id != auth()->user()->id && auth()->user()->type == 0) {
            return redirect('/dashboard/articles');
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
        $apiCallCategories = RequestApi::callAPI('GET', 'categories', [], false);

        if (!$apiCallCategories->success) {
            return view('dashboard.index', [
                'message' => $apiCallCategories->message
            ]);
        }

        $categories = $apiCallCategories->data;

        $apiCallArticle = RequestApi::callAPI('GET', 'articles/' . $article->id, [], false);

        if (!$apiCallArticle->success) {
            return view('dashboard.index', [
                'message' => $apiCallArticle->message
            ]);
        }

        $article = $apiCallArticle->data;

        if ($article->user_id != auth()->user()->id && auth()->user()->type == 0) {
            return redirect('/dashboard/articles');
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
        $apiCall = RequestApi::callAPI('PUT', 'articles/' . $article->id, $request->all(), true);
        if ($apiCall->message == "Unauthenticated.") {
            return view('home.home', [
                'message' => $apiCall->message
            ]);
        }

        if (!$apiCall->success) {
            return back()->with('message', $apiCall->message);
        }

        return redirect('/dashboard/articles')->with('message', $apiCall->message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $apiCall = RequestApi::callAPI('DELETE', 'articles/' . $article->id, [], true);

        if ($apiCall->message == "Unauthenticated.") {
            return view('home.home', [
                'message' => $apiCall->message
            ]);
        }

        if (!$apiCall->success) {
            return back()->with('message', $apiCall->message);
        }

        return redirect('/dashboard/articles')->with('message', $apiCall->message);
    }
}
