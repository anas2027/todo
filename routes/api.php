<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\usercontroller;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SubtaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
////////////////test
Route::post('/test/updateTask',[TaskController::class,'updateTask']);









////

Route::post('/deleteUserAssign', [usercontroller::class,'deleteUserAssign']);

Route::get('/showsubtask',[SubtaskController::class,'show']);
Route::post('/deleteAssign',[SubtaskController::class,'deleteAssign']);


Route::post('/updateAssignontoSubtask',[SubtaskController::class,'updateAssignontoSubtask']);
Route::post('/getalluserSubtask',[SubtaskController::class,'getalluserSubtask']);

Route::post('/getTaskByLabel',[TaskController::class,'getTaskByLabel']);
Route::post('/addtaskwithAssign',[SubtaskController::class,'addtaskwithAssign']);
//////////////  Task controller //////////////
Route::get('/task/view',[TaskController::class,'index']);
Route::post('/task/update',[TaskController::class,'update']);   // have error
Route::delete('/task/delete',[TaskController::class,'destroy']);
Route::post('/task/label',[TaskController::class,'show']);
////////////////// label task
Route::post('/task/label/new',[TaskController::class,'create']);
Route::post('/task/label/update',[TaskController::class,'updateTaskLabel']);
Route::post('/task/label/delete',[TaskController::class,'deleteTaskLabel']);
Route::post('/label/Task/get',[TaskController::class,'getTaskLabel']);

/////////////// label Conttroler /////////////////
Route::post('/addlabel',[LabelController::class,'store']);
Route::get('/getmylabel',[LabelController::class,'index']);
Route::post('/update/label',[LabelController::class,'update']);
Route::delete('/delete/label',[LabelController::class,'destroy']);
Route::post('/label/Task',[LabelController::class,'show']);
Route::post('/label/Task/create',[LabelController::class,'create']);


////////////// sub task ////////////////////////////////
Route::post('/subtask/view',[SubtaskController::class,'index']);
Route::post('/subtask/add',[SubtaskController::class,'store']);
Route::post('/subtask/update',[SubtaskController::class,'update']);
Route::delete('/subtask/delete',[SubtaskController::class,'destroy']);


Route::get('/getAllUser',[usercontroller::class,'getAllUser']);

//////////////  Auth with passport //////////
Route::post('/updateTaskPriorty', [TaskController::class, 'updateTaskPriorty']);
Route::post('/updateTaskState', [TaskController::class, 'updateTaskState']);

Route::post('/register', [usercontroller::class, 'register']);
Route::post('/login', [usercontroller::class, 'login']);
Route::post('/verfiy', [usercontroller::class, 'verfiy']);


////////// middleware
Route::post('/getUserByTaskAssign', [TaskController::class,'getUserByTaskAssign']);



///////////////// SubTaskCategory



Route::post('/addCategoryToSubTask', [SubtaskController::class,'addCategoryToSubTask']);

Route::get('/getFromCategory', [SubtaskController::class,'getFromCategory']);
Route::post('/updateSubTaskFromCategory', [SubtaskController::class,'updateSubTaskFromCategory']);
Route::post('/deleteSubTaskWithCategories', [SubtaskController::class,'deleteSubTaskWithCategories']);



////////////////// category Api Task

Route::get('/getTaskwithCategoey', [TaskController::class,'getTaskwithCategoey']);

Route::post('/addTaskWithCategory', [TaskController::class,'addTaskWithCategory']);

Route::post('/deleteTaskWithCategories', [TaskController::class,'deleteTaskWithCategories']);
Route::post('updateTaskWithCategory', [TaskController::class,'updateTaskWithCategory']);


Route::middleware('auth:api')->group(function () {

    Route::get('/getSubTaskByUserAssign',[SubtaskController::class,'getSubTaskByUserAssign']);

    Route::get('/getTaskByUserToken', [TaskController::class,'getTaskByUserToken']);

    Route::post('/assignToUser', [TaskController::class,'assignToUser']);

    Route::post('/updateUserProfile',[usercontroller::class,'updateUserProfile']);   // have error

    Route::post('/Logout',[usercontroller::class,'Logout']);   // have error

    Route::post('/task/add',[TaskController::class,'store']);   // have error
    Route::post('/getTaskByDate',[TaskController::class,'getTaskByDate']);   // have error
    Route::post('/getTaskByDateTodo',[TaskController::class,'getTaskByDateTodo']);   // have error


    Route::post('/createUserTask', [TaskController::class, 'createUserTask']);
    Route::get('/GetTaskUser', [TaskController::class, 'GetTaskUser']);
    Route::post('/updateTaskUser', [TaskController::class, 'updateTaskUser']);
    Route::delete('/DestroyTaskUser', [TaskController::class, 'DestroyTaskUser']);
 //////////// Assigment TASK TO USER


 //AddtaskAsssigmentToUser

 Route::post('/taskassigmentAddUser', [usercontroller::class,'taskassigmentAddUser']);
 Route::post('/AddtaskAsssigmentToUser', [usercontroller::class,'AddtaskAsssigmentToUser']);

});
