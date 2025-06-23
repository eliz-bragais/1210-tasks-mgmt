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

        $tasks = Task::with(['tasks' => function ($query) use ($user_id, $tasks_status, $tasks_title) {
                                $query->whereNull('deleted_at');
                                $query->where('user_id', $user_id);

                                if($tasks_status != 'all') {
                                    $query->where('status', $tasks_status);
                                }

                                if($tasks_title != null) {
                                    $query->where('title', 'like', '%'.$tasks_title.'%');
                                }
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
        $save_type = $request->save_type;

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
            'save_as' => $save_type,
            'subtask_id' => $request->task_id,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
        ])->id;

        if($task_id)
        {
            return response()->json([
                'message' => 'New Sub Task successfully created.',
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Failed to create the package.',
            ], 400);
        }
    }

    public function updateStatusTask(Request $request)
    {
        $task = Task::where('id', $request->task_id)->where('user_id', auth()->user()->id)->whereNull('deleted_at')->first();
        
        if($task)
        {
            Task::where('id', $request->task_id)
                ->update([
                    'status' => $request->task_status_update,
                    'updated_at' => now()
                ]);

            if($request->task_status_update == 'done')
            {
                if($task->subtask_id > 0)
                {
                    $getAllSubTasks = Task::where('subtask_id', $task->subtask_id)->where('user_id', auth()->user()->id)->whereNull('deleted_at')->get();

                    $subtask_status_lists = [];
                    foreach($getAllSubTasks as $getSubTask) {
                        array_push($subtask_status_lists, $getSubTask->status);
                    }

                    if(array_unique($subtask_status_lists) === ['done'])
                    {
                        Task::where('id', $task->subtask_id)
                            ->update([
                                'status' => $request->task_status_update,
                                'updated_at' => now()
                            ]);
                    }
                }                
            }

            return redirect()->back()->with(array('message' => 'Status successfully updated!', 'error_type' => 'success'));
        }
        else
        {
            return redirect()->back()->with(array('message' => 'Status failed to update!', 'error_type' => 'error'));
        }
    }

    public function updateSaveTypeTask(Request $request)
    {
        $task = Task::where('id', $request->task_id)->where('user_id', auth()->user()->id)->whereNull('deleted_at')->first();
        
        if($task)
        {
            Task::where('id', $request->task_id)
                ->update([
                    'save_as' => $request->task_save_type_update,
                    'updated_at' => now()
                ]);
            
            return redirect()->back()->with(array('message' => 'Save Type successfully updated!', 'error_type' => 'success'));
        }
        else
        {
            return redirect()->back()->with(array('message' => 'Save Type failed to update!', 'error_type' => 'error'));
        }
    }

    public function deleteTask(Request $request)
    {
        $task = Task::where('id', $request->task_info)->where('user_id', auth()->user()->id)->whereNull('trash_at')->whereNull('deleted_at')->first();
        
        if($task)
        {
            // Task::where('id', $task->id)->delete();
            Task::where('id', $task->id)->update(['trash_at' => now()]);
            
            return redirect()->back()->with(array('message' => 'Task successfully deleted!', 'error_type' => 'success'));
        }
        else
        {
            return redirect()->back()->with(array('message' => 'Task failed to delete!', 'error_type' => 'error'));
        }
    }

    public function retrieveTask(Request $request)
    {
        $task = Task::where('id', $request->task_info)->where('user_id', auth()->user()->id)->whereNotNull('trash_at')->whereNull('deleted_at')->first();
        
        if($task)
        {
            Task::where('id', $task->id)->update(['trash_at' => null]);
            
            return redirect()->back()->with(array('message' => 'Task successfully retrieved!', 'error_type' => 'success'));
        }
        else
        {
            return redirect()->back()->with(array('message' => 'Task failed to retrieve!', 'error_type' => 'error'));
        }
    }
}
