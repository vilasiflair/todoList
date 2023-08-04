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

    public function deleteCompletedTasks(Request $request)
    {
        $task_id = $request->task_id;
        
        Todo::where('id', $task_id)->delete();
        $taskData = Todo::get();
        return $taskData;
    }

    public function getFilteredTaskData(Request $request)
    {
        $task_id_Arr = explode(',', $request->task_id);
        // $task_id = $request->task_id;
        $taskData = Todo::whereIn('id', $task_id_Arr)->get();
        // dd($taskData);
        return $taskData;
    }

    /* private function isCsvStructureValid($csvData, $tableColumns)
    {
        $csvColumns = array_map('strtolower', $csvData[0]); // Assuming the first row of CSV contains column headers

        // Compare the two arrays
        return count(array_diff($tableColumns, $csvColumns)) === 0;
    } */

    public function importTaskData(Request $request)
    {
        // Get the column names of the table
        
        // $tableColumns = Schema::getColumnListing((new Todo)->getTable());
        // dd($tableColumns);
        if($request->hasFile('importCSVFile'))
        {
            /* $file = $request->file('importCSVFile');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            // Process the CSV data and store it in the database
            $csvData = array_map('str_getcsv', file(storage_path('app/csv/' . $fileName)));
    
            // Check if CSV columns match with table columns
            if (!$this->isCsvStructureValid($csvData, $tableColumns)) {
                return response()->json(['success' => false, 'message' => 'CSV columns do not match table columns']);
            } */
        
            $file = $request->file('importCSVFile');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('csv', $fileName);
            
            $csvData = array_map('str_getcsv', file(storage_path('app/csv/' . $fileName)));
            
            foreach ($csvData as $row) {
                Todo::create([
                    'task_title' => $row[0],
                    'task_status' => 0,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'CSV data stored successfully']);
        }
        return response()->json(['success' => false, 'message' => 'File upload failed']);
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
