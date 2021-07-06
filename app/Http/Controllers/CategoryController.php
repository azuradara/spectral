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
        return [
            "data" => auth()->user()->categories()->with('favorites')->get()->toArray(),
            "success" => true
        ];
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


    public function update(Request $request, String $id)
    {
        $this->validate($request, [
            'name' => 'required|max:32'
        ]);

        $cat = $request->user()->categories()->where('id', '=', $id);

        $cat->update(['name' => $request->name]);

        return (bool)($cat->first())
            ? [
                'data' => $cat->with('favorites')->first(),
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Category of ID:$id cannot be updated."
            ];
    }

    public function delete(Request $request, $id)
    {
        $rm = $request->user()->categories()->where('id', '=', $id)->delete();

        return $rm
            ? [
                'data' => "Category of ID:$id has been deleted.",
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Category of ID:$id cannot be found."
            ];
    }
}