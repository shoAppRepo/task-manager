<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Period extends Model
{
    protected $fillable = ['period_id', 'start', 'end', 'user_id', 'work_days'];

    protected $primaryKey = 'period_id';
}
