<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'period_id', 'task_id', 'point', 'user_id'];

    protected $primaryKey = 'task_id';

}
