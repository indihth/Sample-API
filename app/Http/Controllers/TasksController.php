<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;
use App\Traits\HttpResponses;

class TasksController extends Controller
{

    use HttpResponses;
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
    public function show(Task $task)
    {
        // Check if user is authorised, if yes, print out error message, if not, return $task
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : new TasksResource($task);

        return new TasksResource($task);
    }

    // The edit() method also isn't needed, just a web form that is sent to update()

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // PUT vs. PATCH - PUT updates all fields / PATCH updates selected fields

        // Check the tasks belongs to the authorised user, if not returns forbidden error
        if(Auth::user()->id != $task->user_id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }

        $task->update($request->all());

        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : $task->delete();
    }

    private function isNotAuthorized($task) {
        // Check the tasks belongs to the authorised user, if not returns forbidden error
        if(Auth::user()->id != $task->user_id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }
}
