<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TaskPriority;
use TaskStatus;

class task extends Model
{
    protected $fillable=['name','endDate','title','users_id','Priority','status'],

    $table='tasks';
    use HasFactory;

    protected $casts=[
        'status'=>\App\Enums\TaskStatus::class,
        'Priority'=>\App\Enums\TaskPriority::class,
    ];
    public function lables(): BelongsToMany
    {
        return $this->belongsToMany(label::class);
    }
    public function labeltask(){
        return $this->belongsToMany(labelTask::class);
    }
    public function subtask(){
        return $this->hasMany(subtask::class);
    }
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class,'task_user','task_id','users_id');
    }
    public function categories()
    {
        return $this->morphMany(Category::class, 'categorizable');
    }


}
