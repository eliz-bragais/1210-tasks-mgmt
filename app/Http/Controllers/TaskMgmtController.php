<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\StoreSubTaskRequest;

class TaskMgmtController extends Controller
{
    public function index(Request $request, $status = 'all', $title = null)
    {
        $user_id = auth()->user()->id;

        $tasks_status = $request->status ? $request->status : $status;
        $tasks_title = $request->title ? $request->title : $title;

        $tasks = Task::with(['tasks' => function ($query) use ($user_id) {
                                $query->whereNull('deleted_at');
                                $query->where('user_id', $user_id);
                            }])
                    ->whereNull('deleted_at')
                    ->where(function ($query) use ($tasks_status, $tasks_title){
                        if($tasks_status != 'all') {
                            $query->where('status', $tasks_status);
                        }

                        if($tasks_title != null) {
                            $query->where('title', 'like', '%'.$tasks_title.'%');
                        }
                    })
                    ->where('user_id', $user_id)
                    ->where('subtask_id', 0)
                    ->orderBy('id', 'asc')
                    ->get();
        // dd($tasks->toArray());
        return view('tasks', compact('tasks'));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $btn_form = $request->btnForm;

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $img_name = $file->getClientOriginalName();
            $file->move(public_path('task_img/'), $file->getClientOriginalName());
        }
        else
        {
            $img_name = null;
        }
        
        $task_id = Task::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'image' => $img_name,
            'save_as' => $btn_form,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
        ])->id;

        return redirect()->back()->with(array('message' => 'New Task successfully created!', 'error_type' => 'success'));
    }

    public function storeSubTask(StoreSubTaskRequest $request)
    {
        $validated = $request->validated();
        $btn_form = $request->sub_task_btnForm;

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $img_name = $file->getClientOriginalName();
            $file->move(public_path('task_img/'), $file->getClientOriginalName());
        }
        else
        {
            $img_name = null;
        }
        
        $task_id = Task::create([
            'title' => $validated['sub_task_title'],
            'content' => $validated['sub_task_content'],
            'status' => $validated['sub_task_status'],
            'image' => $img_name,
            'save_as' => $btn_form,
            'subtask_id' => $request->task_id,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
        ])->id;

        return redirect()->back()->with(array('message' => 'New Sub Task successfully created!', 'error_type' => 'success'));
    }
}
