<?php

namespace App\Http\Controllers;

use App\Models\label;
use App\Models\labelTask;
use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return label::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request )
     {

    //     $labels = [1,2,4];
    //   $task = new task();

    //    $task->title = $request->title;
    //    $task->endDate = $request->endDate;
    //    $task->save();
    //   $task->tasks()->attach($request->lables);
    //   return response()->json(['success' , true, 'task' => $task]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rule =[
            "name"=>'required|string',
            "color"=>'required|String',
        ];
        $validator = validator::make($request->all(),$rule);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
          }

          label::create([
            'name'=> $request->name,
            'color'=> $request->color,
          ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $mylabel = label::where('id',$request->id)->first();
        $myTaskLabel = labelTask::where('label_id',$mylabel->id)->first();
        $myTask = task::where('id',$myTaskLabel->task_id)->first();
        return $myTask;
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(label $label)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, label $label)
    {
       label::where('id', $request->id)->update([
        'name'=> $request->name,
        'color'=> $request->color,
       ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        label::where('id', $request->id)->delete();
    }
}
