<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class label extends Model
{
    protected $fillable=['name','color'],
    $table='labels';
    use HasFactory;
    public function labelsTasks(){
        return $this->hasMany(labelTask::class);
    }
    public function tasks(){
        return $this->belongsToMany(task::class);
    }
}
