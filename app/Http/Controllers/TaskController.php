<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function get(Request $request)
    {
        return response(
            [
                "data" => $request->user()->tasks,
                "error" => null
            ],
            200
        );
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:2000',
            'color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'task_category_id' => 'required|exists:task_categories,id',
            'is_important' => 'boolean'
        ]);

        $task = $request->user()->tasks()->create([
            'task_category_id' => $request->task_category_id,
            'content' => $request->content,
            'color' => $request->color,
        ]);

        $request->is_important && $task->update(["is_important" => $request->is_important]);

        return (bool)$task
            ? response(
                [
                    "data" => $task,
                    "error" => null
                ],
                200
            )
            : response(
                [
                    "data" => null,
                    "error" => "Could not create Task."
                ],
                401
            );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'max:2000',
            'color' => ['regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'task_category_id' => 'exists:task_categories,id',
            'is_important' => 'boolean',
            'is_done' => 'boolean'
        ]);

        $task = $request->user()->tasks()->where('id', '=', $id);

        foreach ($request->all() as $k => $v) {
            $task->update(["$k" => $v]);
        }

        return (bool)$task->first()
            ? response(
                [
                    "data" => $task->first(),
                    "error" => null
                ],
                200
            )
            : response(
                [
                    "data" => null,
                    "error" => "Could not update Task."
                ],
                401
            );
    }

    public function delete(Request $request, $id)
    {
        $rm = $request->user()->tasks()->where('id', '=', $id)->delete();

        return (bool)$rm
            ? response(
                [
                    "data" => "Task of ID:$id has been deleted.",
                    "error" => null
                ],
                200
            )
            : response(
                [
                    "data" => null,
                    "error" => "Could not delete Task."
                ],
                401
            );
    }
}