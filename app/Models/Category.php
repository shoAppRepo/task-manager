<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['category_id', 'name', 'period_id'];

  protected $primaryKey = 'category_id';
}
