<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = auth()->user()->categories->toArray();

        // dd($cats);

        $nucat = array_map(array($this, 'populate'), $cats);

        return [
            "data" => $nucat,
            "success" => true
        ];
    }

    private function populate($cat)
    {

        $nucat = array_merge($cat, [
            "favorites" => [
                [
                    "title" => "test",
                    "url" => "https://youtube.com",
                    "category_id" => 1,
                    "icon" => "",
                    "created_at" => "2021-06-14T13:46:36.000000Z",
                    "updated_at" => "2021-06-14T13:46:36.000000Z",
                ]
            ]
        ]);

        return $nucat;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:32',
        ]);

        return $request->user()->categories()->create([
            'name' => $request->name,
            'is_pinned' => false
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}