<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:32',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id'
        ]);

        $fav = $request->user()->favorites()->create([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id
        ]);

        return (bool)($fav)
            ? [
                'data' => $fav,
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Fav of cannot be created."
            ];
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
        return [
            "data" => $request->user()->favorites,
            "error" => null
        ];
    }



    public function pin(Request $request, $id)
    {
        // TODO: establish error checks later
        $pin = $request->user()->favorites()->where('id', '=', $id)->update(['is_pinned' => $request['is_pinned']]);


        return $pin
            ? [
                'data' => $request->user()->favorites()->where('id', '=', $id)->first(),
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Fav of ID:$id cannot be updated."
            ];
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'max:128',
            'url' => 'url',
            'category_id' => 'exists:categories,id'
        ]);
        // dd($request->all());

        $fav = $request->user()->favorites()->where('id', '=', $id);
        // dd((bool)($fav->first()));

        foreach ($request->all() as $k => $v) {
            $fav->update(["$k" => $v]);
        }


        // TODO: Revisit query later

        // $fav = Favorite::find($id);
        // $fav->title = $request->title;
        // $fav->url = $request->url;
        // $fav->category_id = $request->category_id;

        return (bool)($fav->first())
            ? [
                'data' => $fav->first(),
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