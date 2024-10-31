<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::all();

        return ProjectResource::collection($project);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->storeAs('images/projects', time() . '_' . $request->file('image')->getClientOriginalName(), 'public');
        }

        // dd($data);

        $project = Project::create($data);

        return new ProjectResource($project);
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
    public function update(ProjectRequest $request, $id)
    {
        $project = Project::findOrFail($id);
        $data = $request->validated();

        // cek apakah ada inputan file
        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }

            // simpan gambar baru
            $data['image'] = $request->file('image')->storeAs('images/projects', time() . '_' . $request->file('image')->getClientOriginalName(), 'public');
        }

        $project->update($data);

        dd($request->all());
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return new ProjectResource($project);
    }
}