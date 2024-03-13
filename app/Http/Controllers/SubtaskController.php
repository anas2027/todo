<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\subtask;
use App\Models\SubtaskUser;
use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return subtask::where('task_id',$request->task_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule=[
            "title"=> "required|string",
            "task_id"=> "required|integer",


        ];
        $validator = validator::make($request->all(), $rule);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
          }
          subtask::create([
            'title'=> $request->title,
            'task_id'=> $request->task_id,
          ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(subtask $subtask)
    {
        //
        $subtask = subtask::with('subtasks')->get();

          return   $subtask;

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function addtaskwithAssign(Request $request)
    {
        $mynewtask = new subtask();
        $mynewtask->title = $request->title;
        $mynewtask->task_id = $request->task_id;
        $mynewtask->save();
        $mynewtask->subtasks()->attach($request->user_id);
        // $taskId = $mu->id;
        // $newlabel = task::find($taskId);
        // $newlabel -> users()->sync([$request->users_id]);
        //
    }

    public function updateAssignontoSubtask(Request $request){


        $subtask = Subtask::find($request->subtask_id);
           $subtask->subtasks()->sync([$request->user_id]);
    }
   public function deleteAssign(Request $request){
    $subtask = subtask::find($request->subtask_id);
    $subtask->subtasks()->detach();

   }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, subtask $subtask)
    {
        subtask::where('id',$request->id)->update([
          'title'=> $request->title,
           'task_id'=> $request->task_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        subtask::where('id',$request->id)->delete();
    }
    public function getalluserSubtask(Request $request){

 return SubtaskUser::join('subtasks','subtasks.id','=','subtask_users.subtask_id')
 ->join('users','users.id','=','subtask_users.user_id')
 ->where('subtask_users.subtask_id',$request->subtask_id)->select('users.id','users.name','users.email')->get();

    }

    public function getSubTaskByUserAssign(Request $request){
        return SubtaskUser::join('subtasks','subtasks.id','=','subtask_users.subtask_id')
 ->join('users','users.id','=','subtask_users.user_id')
 ->where('subtask_users.user_id',Auth::user()['id'])->select('subtasks.title','subtasks.id','subtasks.task_id')->get();

    }



    public function addCategoryToSubTask(Request $request){

        $subTask = subtask::create([
            'title'=> $request->title,
            'task_id'=> $request->task_id,
          ]);

          $subTask->save();
          $categorie = new Category([
            'name' => $request->categoryName,
        ]);
        $subTask->SubTaskCategory()->save($categorie);

    }
    public function getFromCategory(Request $request, Subtask $subtask){
        $subTaskCategories = subtask::with('SubTaskCategory')->get();
return $subTaskCategories;
   }
   public function updateSubTaskFromCategory(Request $request, Subtask $subtask){
 // Find the task by ID
 $subTask = subtask::find($request->subTaskId);

 if ($subTask) {
     // Update the associated category's attributes
     $subTask->SubTaskCategory()->update(['name' => $request->name]);

     return response()->json(['message' => 'Category updated successfully']);
 } else {
     return response()->json(['message' => 'Task not found'], 404);
 }


}
public function deleteSubTaskWithCategories(Request $request){
    $subTask = subtask::find($request->subTaskId);

    if ($subTask) {
        $subTask->SubTaskCategory()->delete();



        return response()->json(['message' => 'subTask and associated categories deleted successfully']);
    } else {
        return response()->json(['message' => 'subTask not found'], 404);
    }
}

}
