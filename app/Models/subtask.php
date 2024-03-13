<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subtask extends Model
{
     protected $fillable=['task_id','title'],
     $table='subtasks';
    use HasFactory;
    public function tasks(){
        return $this->belongsTo(Task::class);
    }
    public function subtasks(){
        return $this->belongsToMany(User::class,'subtask_users','subtask_id','user_id');
    }
    public function SubTaskCategory()
    {
        return $this->morphMany(Category::class,'categorizable');
    }
}
