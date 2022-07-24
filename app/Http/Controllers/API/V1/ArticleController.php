<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Article;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::select(
            'id',
            'title',
            'image',
            'user_id',
            'category_id',
            'created_at',
        )->latest()
        ->filter(request(['user_id']))
        ->paginate(9)
        ->withQueryString();

        return $this->sendResponse($articles, 'Articles retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'image|file|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $validatedData = [
            'title' => $request->title,
            'content' => $request->content,
            'author' => auth()->user()->id,
            'category_id' => $request->category_id,
        ];

        if($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('article-images');
        }

        Article::create($validatedData);

        return $this->sendResponse([], 'Article created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return $this->sendResponse($article, 'Article retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'image|file|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $validatedData = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $article->user_id,
            'category_id' => $request->category_id,
        ];

        if($request->file('image')) {
            if ($article->image) {
                Storage::delete($article->image);
            }
            $validatedData['image'] = $request->file('image')->store('article-images');
        }

        Article::where('id', $article->id)->update($validatedData);

        return $this->sendResponse([], 'Article updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::delete($article->image);
        }

        $article->delete();

        return $this->sendResponse([], 'Article deleted successfully');
    }
}
