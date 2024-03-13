<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['name' ,'categorizable_id', 'categorizable_type'],
    $table='categories';
    use HasFactory;

    public function categorizable()
    {
        return $this->morphTo();
    }
}
