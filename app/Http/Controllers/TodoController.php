<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("todo/todoList");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getTaskData(Request $request)
    {
        // dd($request);
        $taskData = Todo::get();
        return $taskData;
    }

    public function storeTaskData(Request $request)
    {
        $task_title = $request->task_title;

        $taskData = new Todo;
        $taskData->task_title = $task_title;
        $taskData->status = 0;
        $taskData->save();
        return $taskData;
    }

    public function updateTaskData(Request $request)
    {
        $task_id = $request->task_id;
        $task_status = $request->task_status;
        
        Todo::where('id', $task_id)->update(['status' => $task_status]);
        $taskData = Todo::get();
        return $taskData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        //
    }
}
