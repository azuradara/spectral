<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function get(Request $request)
    {
        return [
            "data" => $request->user()->task_categories()->with('tasks')->get()->toArray(),
            "error" => null
        ];
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:32',
            'color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/']
        ]);

        $task_category = $request->user()->task_categories()->create([
            'name' => $request->name,
            'color' => $request->color
        ]);

        return (bool)$task_category
            ? [
                'data' => $task_category,
                'error' => null
            ] : [
                'data' => null,
                'error' => "Couldn't create category."
            ];
    }

    public function update(Request $request, String $id)
    {
        $this->validate($request, [
            'name' => 'max:32',
            'color' => ['regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/']
        ]);

        $task_category = $request->user()->task_categories()->where('id', '=', $id);

        foreach ($request->all() as $k => $v) {
            $task_category->update(["$k" => $v]);
        }

        return (bool)($task_category->first())
            ? [
                'data' => $task_category->with('favorites')->first(),
                'error' => null
            ]
            : [
                'data' => null,
                'error' => "Category of ID:$id cannot be updated."
            ];
    }

    public function delete(Request $request, $id)
    {
        $rm = $request->user()->task_categories()->where('id', '=', $id)->delete();

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