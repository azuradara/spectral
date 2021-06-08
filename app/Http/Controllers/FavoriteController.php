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
            'description' => 'max:128'
        ]);

        return $request->user()->favorites()->create([
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description
        ]);
    }

    public function seek(Request $request)
    {
        return response(
            auth()->user()->favorites,
            200
        );
    }
}