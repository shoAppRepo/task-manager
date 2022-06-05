<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Models\Manhour;
use App\Models\Period;
use Carbon\Carbon;

class CategoryController extends Controller
{
  public function calendarIndex()
  {
    $login_id = \Auth::id();
    $manhours = Manhour::where('user_id', $login_id)->get();

    return compact('manhours');
  }
  
  public function index(string ...$params)
  {
    $login_id = \Auth::id();
    $period_id = $params[0];
    $items = [];
    
    // カテゴリー一覧取得
    $categories = Category::where('user_id', $login_id)->where('period_id', $period_id)->orderby('sort')->get();

    // taskを取得
    $tasks = Task::where('user_id', $login_id)->where('period_id', $period_id)->orderby('sort')->get();

    // manhours取得
    $manhours = Manhour::where('user_id', $login_id)->orderby('end')->get();

    // カテゴリーとtaskを紐付け
    foreach($categories as $category){
      $item = [];
      
      // 紐づくtaskを取得し、インデックスを採番
      $tasks_of_this_category = $tasks->where('category_id', $category->category_id)->sortby('sort')->values();
      $tasks_with_manhours = [];

      // taskに紐づくmanhourを取得し、インデックスを採番
      foreach($tasks_of_this_category as $task){
        $task_with_manhours['task'] = $task;

        $manhours_of_this_task = $manhours->filter(function($manhour) use($task) {
          return $manhour->task_id === $task->task_id;
        })->values();

        $task_with_manhours['task']['manhours'] = $manhours_of_this_task;

        array_push($tasks_with_manhours, $task_with_manhours);
      }

      $item['category_id'] = $category->category_id;
      $item['name'] = $category->name;
      $item['sort'] = $category->sort;
      $item['tasks'] = $tasks_with_manhours;

      array_push($items, $item);
    }

    return compact('items');
  }

  public function update(Request $request)
  {
    $login_id = \Auth::id();
    $items = $request->input('items');
    $period_id = $request->input('period_id');
    $delete_tasks = $request->input('delete_tasks');
    $delete_hours = $request->input('delete_hours');
    $delete_categories = $request->input('delete_categories');

    foreach($delete_categories as $delete_category){
      Category::where('user_id', $login_id)->where('category_id', $delete_category['category_id'])->delete();
    }    

    foreach($delete_tasks as $delete_task){
      Task::where('user_id', $login_id)->where('task_id', $delete_task['task_id'])->delete();
    }

    foreach($delete_hours as $delete_hour){
      Manhour::where('user_id', $login_id)->where('man_hour_id', $delete_hour['man_hour_id'])->delete();
    }

    
    
    foreach($items as $index => $item){
      // categoryの登録・更新
      $category_id = $item['category_id'];
      $copy_item = $item;
      unset($copy_item['tasks']);
      if(array_key_exists('is_new', $item)){
        unset($copy_item['is_new'], $copy_item['category_id']);
        $copy_item['user_id'] = $login_id;
        $inserted_category = Category::create($copy_item);
        $category_id = $inserted_category->category_id;
      }else{
        Category::where('user_id', $login_id)->where('category_id', $category_id)->update($copy_item);
      }
      // tasksの登録・更新
      $tasks = $item['tasks'];
      foreach($tasks as $task){
        $task_datum = $task['task'];
        $task_id = $task_datum['task_id'];
        $manhours = $task_datum['manhours'];

        if(array_key_exists('is_new', $task_datum)){
          unset($task_datum['is_new'], $task_datum['manhours'], $task_datum['task_id']);
          $task_datum['category_id'] = $category_id;
          $task_datum['user_id'] = $login_id;
          $inserted_task = Task::create($task_datum);
          $task_id = $inserted_task->task_id;
        }else{
          unset($task_datum['manhours']);
          Task::where('user_id', $login_id)->where('task_id', $task_datum['task_id'])->update($task_datum);
        }

        // manhoursの登録・更新
        if(count($manhours) > 0){
          foreach($manhours as $index => $manhour){
            if(array_key_exists('is_new', $manhour)){
              $manhour['task_id'] = $task_id;
              $manhour['user_id'] = $login_id;
              Manhour::create($manhour);
            }else{
              Manhour::where('user_id', $login_id)->where('man_hour_id', $manhour['man_hour_id'])->update($manhour);
            }
          }
        }
      }
    }

    return $this->index($period_id);
  }
}
