<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        return Favorite::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:32',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id'
        ]);

        return $request->user()->favorites()->create([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id
        ]);
    }

    public function seek(Request $request)
    {
        return response(
            auth()->user()->favorites,
            200
        );
    }

    public function getPinned(Request $request)
    {
        return [
            "data" => $request->user()->favorites()->where('is_pinned', '=', true)->get()->toArray(),
            "error" => null
        ];
    }
}