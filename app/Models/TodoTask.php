<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoTask extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'task_id', 'user_id'];

    protected $primaryKey = 'task_id';

}
