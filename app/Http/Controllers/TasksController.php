<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sends tasks to the TasksResource to be converted to JSON
        return TasksResource::collection(
            // Gets the tasks where the 'user_id' == the authenticated user id
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    // The create() method not used in API, go straight to store()

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request);

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        // Sends the newly created $task to be formatted by the TasksResource into a JSON object
        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
