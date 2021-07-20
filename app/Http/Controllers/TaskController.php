<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
  public function index()
  {
    $login_id = \Auth::id();
    $tasks = Task::where('user_id', $login_id)->get();

    return compact('tasks');
  }

  
}
