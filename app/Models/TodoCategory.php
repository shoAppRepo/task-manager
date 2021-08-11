<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoCategory extends Model
{
  protected $fillable = ['category_id', 'name', 'user_id'];

  protected $primaryKey = 'category_id';
}
