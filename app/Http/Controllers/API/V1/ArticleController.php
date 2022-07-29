<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ArticleNoContentResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::filter(request(['user_id']))
        ->latest()
        ->paginate(9);

        return response()->json(ArticleNoContentResource::collection($articles)->response()->getData(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth('api')->user()->id != $request->user_id) {
            return response()->json([
                'message' => 'Permission denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'image|file|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        }

        $validatedData = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
        ];

        if($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('article-images');
        }

        Article::create($validatedData);

        return response()->json([
            'message' => 'Article created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json(new ArticleResource($article), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        if (auth('api')->user()->id != $article->user_id && auth('api')->user()->type == 0) {
            return response()->json([
                'message' => 'Permission denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'image|file|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $request->all()
            ], 400);
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

        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if (auth('api')->user()->id != $article->user_id && auth('api')->user()->type == 0) {
           return response()->json([
                'message' => 'Permission denied'
            ], 403);
        }

        if ($article->image) {
            Storage::delete($article->image);
        }

        $article->delete();

        return response()->json([], 204);
    }
}
