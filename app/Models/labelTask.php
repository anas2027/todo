<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class labelTask extends Model
{
    protected $fillable=['task_id','label_id',],
    $table='label_task';
    use HasFactory;
}
