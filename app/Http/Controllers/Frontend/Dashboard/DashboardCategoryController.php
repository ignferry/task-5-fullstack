<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Helper\RequestApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::get(env('API_URL') . 'categories');

        if (!$response->successful()) {
            return abort($response->status());
        }

        $categories = json_decode($response);

        return view('dashboard.categories.index', [
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
        $response = Http::withToken(request()->cookie('token'))->post(env('API_URL') . 'categories', $request->all());

        $responseData = json_decode($response);

        if (!$response->successful()) {
            return back()->withErrors($responseData->errors);
        }

        return redirect('/dashboard/categories')->with('message', 'Category created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $response = Http::withToken(request()->cookie('token'))->put(env('API_URL') . 'categories/' . $category->id, $request->all());
        $responseData = json_decode($response);

        if (!$response->successful()) {
            return back()->withErrors($responseData->errors);
        }

        return redirect('/dashboard/categories')->with('message', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $response = Http::withToken(request()->cookie('token'))->delete(env('API_URL') . 'categories/' . $category->id);
        if (!$response->successful()) {
            return abort($response->status());
        }

        return redirect('/dashboard/categories')->with('message', 'Category deleted successfully');
    }
}
