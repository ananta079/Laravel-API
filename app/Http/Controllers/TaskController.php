<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use tidy;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = Task::all();
        return TaskResource::collection($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->storeAs('images/tasks', time() . '_' . $request->file('image')->getClientOriginalName(), 'public');
        }

        $task = Task::create($data);

        return response()->json(['message' => 'successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }

            $data['image'] = $request->file('image')->storeAs('images/tasks', time() . '_' . $request->file('image')->getClientOriginalName(), 'public');
        }

        $task->update($data);

        return response()->json(['message' => 'success update data']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return new TaskResource($task);
    }
}