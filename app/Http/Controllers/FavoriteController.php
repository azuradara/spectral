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

    public function delete(Request $request, $id)
    {
        $rm = $request->user()->favorites()->where('id', '=', $id)->delete();

        return $rm
            ? [
                'data' => "Fav of ID:$id has been deleted.",
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Fav of ID:$id cannot be found."
            ];
    }

    public function seek(Request $request)
    {
        return response(
            $request()->user()->favorites,
            200
        );
    }



    public function pin(Request $request, $id)
    {
        $pin = $request->user()->favorites()->where('id', '=', $id)->update(['is_pinned' => $request['is_pinned']]);


        return $pin
            ? [
                'data' => $request->user()->favorites()->where('id', '=', $id)->get()[0],
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Fav of ID:$id cannot be updated."
            ];
    }

    public function getPinned(Request $request)
    {
        return [
            "data" => $request->user()->favorites()->where('is_pinned', '=', true)->get()->toArray(),
            "error" => null
        ];
    }
}