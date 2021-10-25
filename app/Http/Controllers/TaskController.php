<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Controllers\PeriodController;

class TaskController extends Controller
{
  public function index()
  {
    $login_id = \Auth::id();
    $tasks = Task::where('user_id', $login_id)->orderby('task_id')->get();

    $PeriodController = new PeriodController();
    $periods = $PeriodController->index();

    return compact('tasks', 'periods');
  }

  
}
