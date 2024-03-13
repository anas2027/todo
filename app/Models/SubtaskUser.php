<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtaskUser extends Model
{
    protected $fillable=['user_id','subtask_id',],
    $table='subtask_users';


    use HasFactory;
}
