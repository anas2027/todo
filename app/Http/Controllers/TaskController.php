<?php

namespace App\Http\Controllers;

use App\Mail\MyCustomMail;
use App\Models\Category;
use Illuminate\Mail\Mailable;
use App\Enums\TaskStatus;
use App\Models\labelTask;
use App\Models\subtask;
use App\Models\task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;
use App\Models\SubtaskUser;

class TaskController extends Controller
{





    public function getTaskByLabel(Request $request)
    {


        //  $dw= labelTask::where("label_id", $request->label)->get('task_id');



        return labelTask::join('tasks', 'tasks.id', '=', 'label_task.task_id')
            ->join('labels', 'labels.id', '=', 'label_task.label_id')
            ->where('label_task.label_id', $request->label)->select('tasks.name', 'tasks.title', 'tasks.endDate', 'tasks.status', 'tasks.Priority', 'tasks.users_id', 'tasks.id')->get();


        // // $dw->pluck("task_id")->each(function($label){
// $aray = [];
// $fw = $dw->pluck('task_id')->toArray();
// for($i= 0;$i<count($fw);$i++){
// $la= task::where('id',$fw[$i])->get();
// $laala = $la->pluck('')->toArray();
// $newArray = array_merge($aray , $laala);
// //$lol = $aray::add($i,$la);
// print_r($laala);
// print_r($newArray);
// // = Arr::add($data, 'age', 30);  array_merge


        // }
// return $newArray;


        //     // $new = labelTask::find("label_id", $request->label)->first();

        // $us->pluck('role')->toArray();

        // return $result = labelTask::where('label_id',$new)->get();
        // return response()->json(['task'=> $new],200);



    }






    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mytask = Task::orderBy("endDate", "desc")->get();
        return $mytask;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $mynewtask = new task();
        $mynewtask->name = $request->name;
        $mynewtask->title = $request->title;
        $mynewtask->endDate = $request->endDate;
        $mynewtask->status = $request->statu;
        $mynewtask->Priority = $request->Priority;
        $mynewtask->save();
        $mynewtask->lables()->attach($request->lables);
        return response()->json(['success', true, 'mynewtask' => $mynewtask]);

        //
    }
    public function updateTaskLabel(Request $request)
    {
        $task = task::find($request->taskid);
        $task->lables()->sync($request->lablesid);
    }
    public function deleteTaskLabel(Request $request)
    {
        $task = task::find($request->taskid);
        $task->lables()->detach();
        $task->delete();


    }

    public function getTaskLabel(Request $request)
    {
        $task = task::with('lables')->get();
        return response()->json(['task' => $task], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            "name" => "required|string",

            "title" => "required|string",
            "endDate" => "required|date_format:Y-m-d H:i:s",
            "status" => "required|tinyInteger",
            "Priority" => "required|tinyInteger",

        ];
        $validator = validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $mynewtask = new task();

        $mynewtask->name = $request->name;
        $mynewtask->title = $request->title;
        $mynewtask->endDate = $request->endDate;
        $mynewtask->status = $request->statu;
        $mynewtask->Priority = $request->Priority;
        $mynewtask->users_id = Auth::user()['id'];




        $mynewtask->save();
        $mynewtask->lables()->attach($request->lables);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {


        $subtak = subtask::where('id', $request->subtask_id)->first();

        $mytask = task::where('id', $subtak->task_id)->get();
        return $mytask;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, task $task)
    {
        task::find($request->id)->update([
            'title' => $request->title,
            'endDate' => $request->endDate,
            'name' => $request->name,
            'status' => $request->status,
            'Priority' => $request->Priority,
        ]);
        $newlabel = task::find($request->id);
        $newlabel->save();

        $newlabel->lables()->sync($request->lablesid);

        //return response()->json('',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        task::where('id', $request->id)->delete();
    }
    //////////////////////////// Task user
    public function GetTaskUser()
    {
        $task = task::where("users_id", Auth::user()['id'])->get();
        return $task;

    }
    //////////// create userTask With Label
    public function createUserTask(Request $request)
    {

        $rule = [
            "name" => "required|string",
            "title" => "required|string",
            "endDate" => "required|date_format:Y-m-d H:i:s",
            "Priority" => "required|integer",
            "status" => "required|integer",
            "lablesid" => "required|integer"


        ];
        $validator = validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $mu = task::create([
            'title' => $request->title,
            'endDate' => $request->endDate,
            'users_id' => Auth::user()['id'],
            'name' => $request->name,
            'status' => $request->status,
            'Priority' => $request->Priority,
        ]);

        $taskId = $mu->id;
        $newlabel = task::find($taskId);
        $newlabel->lables()->sync($request->lablesid);


        return response()->json(['success' => $taskId], 200);





    }


    public function updateTaskUser(Request $request)
    {
        $mytask = Task::where('id', $request->id)
            ->where("users_id", Auth::user()['id'])->first();
        if (!$mytask)
            return response()->json(['this is not your task' => 'unsuccess'], 404);
        $mytask->update([
            'title' => $request->title,
            'endDate' => $request->endDate,
            'name' => $request->name,
            'status' => $request->status,
            'Priority' => $request->Priority,
        ]);
        return response()->json(['success' => 'Done'], 200);
    }
    public function DestroyTaskUser(Request $request)
    {
        $task = Task::findOrFail($request->id);

        if ($task->delete()) {
            return response()->json(['message' => 'Delete successful'], 200);
        } else {
            return response()->json(['error' => 'Delete failed'], 500);
        }

    }

    public function getTaskByDate(Request $request)
    {
        $mytask = Task::where('endDate', $request->endDate)->where('users_id', Auth::user()['id'], )->where('status', '1', )->get();
        return $mytask;
    }
    public function getTaskByDateTodo(Request $request)
    {
        $mytask = Task::where('endDate', $request->endDate)->where('users_id', Auth::user()['id'], )->where('status', '3', )->get();
        if ($mytask->isEmpty()) {
            // Return a response indicating no tasks found
            return response()->json(['message' => 'No tasks found for the specified date and user.'], 404);
        }

        // Return the tasks if they exist
        return $mytask;
    }


    public function updateTaskPriorty(Request $request)
    {
        task::find($request->id)->update([

            'Priority' => $request->Priority,

        ]);
    }
    public function updateTaskState(Request $request)
    {
        task::find($request->id)->update([

            'status' => $request->status,

        ]);
    }
    public function assignToUser(Request $request)
    {
        $mynewtask = new task;
        $mu = task::create([
            'title' => $request->title,
            'endDate' => $request->endDate,
            'users_id' => Auth::user()['id'],
            'name' => $request->name,
            'status' => $request->status,
            'Priority' => $request->Priority,
        ]);
        $taskId = $mu->id;
        $newlabel = task::find($taskId);
        $newlabel->users()->sync([$request->users_id]);


        //   $mynewtask->users_id = Auth::user()['id'];
        //   $mynewtask->save();
        //   $mynewtask->users()->attach($request->userId);

    }
    public function updateassignToUser(Request $request)
    {

        $task = task::find($request->task_id);
        $task->users()->sync([$request->user_id]);


    }
    public function getUserByTaskAssign(Request $request)
    {


        return UserTask::join('tasks', 'tasks.id', '=', 'task_user.task_id')
            ->join('users', 'users.id', '=', 'task_user.users_id')
            ->where('task_user.task_id', $request->task_id)->select('users.id', 'users.name', 'users.email')->get();

    }
    public function getTaskByUserToken(Request $request)
    {


        return UserTask::join('tasks', 'tasks.id', '=', 'task_user.task_id')
            ->join('users', 'users.id', '=', 'task_user.users_id')
            ->where('task_user.users_id', Auth::user()['id'])->select('tasks.id', 'tasks.name', 'tasks.title', 'tasks.Priority', 'tasks.status', 'tasks.endDate', 'tasks.users_id')->get();

    }

    public function updateTask(Request $request)
    {

        $myTask = task::all();
        // $date = Carbon::now()->format('Y-m-d H:i:s');
        return $myTask;
    }




    public function getTaskwithCategoey(Request $request)
    {
        $taskCategories = task::with('categories')->get();
        return $taskCategories;
    }



    public function addTaskWithCategory(Request $request)
    {
        $task = new Task([
            'title' => $request->title,
            'endDate' => $request->endDate,
            'users_id' => $request->userid,
            'name' => $request->name,
            'status' => $request->status,
            'Priority' => $request->Priority,
        ]);
        $task->save();
        $categorie = new Category([
            'name' => 'Test Category',
        ]);


        $task->categories()->save($categorie);

    }
    public function deleteTaskWithCategories(Request $request)
    {
        $task = task::find($request->subTaskId);

        if ($task) {
            $task->categories()->delete();



            return response()->json(['message' => 'Task and associated categories deleted successfully']);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    public function updateTaskWithCategory(Request $request)
    {
        // Find the task by ID
        $task = Task::find($request->subTaskId);

        if ($task) {
            // Update the associated category's attributes
            $task->categories()->update(['name' => $request->name]);

            return response()->json(['message' => 'Category updated successfully']);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }


    }
}
