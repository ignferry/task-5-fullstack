<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helper\RequestApi;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiCall = RequestApi::callAPI('GET', 'categories', [], false);

        if (!$apiCall->success) {
            return view('dashboard.index', [
                'message' => $apiCall->message
            ]);
        }

        $categories = $apiCall->data;

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
        $apiCall = RequestApi::callAPI('POST', 'categories', $request->all(), true);

        if (!$apiCall->success) {
            return back()->with('message', $apiCall->message);
        }

        return redirect('/dashboard/categories');
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
        $apiCall = RequestApi::callAPI('PUT', 'categories/' . $category->id, $request->all(), true);

        if (!$apiCall->success) {
            return redirect('/dashboard');
        }

        return redirect('/dashboard/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $apiCall = RequestApi::callAPI('DELETE', 'categories/' . $category->id, [], true);

        if (!$apiCall->success) {
            return redirect('/dashboard');
        }

        return redirect('/dashboard/categories');
    }
}
