<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manhour extends Model
{
  protected $fillable = ['man_hour_id', 'task_id', 'start', 'end', 'remark', 'title'];

  protected $primaryKey = 'man_hour_id';
}

