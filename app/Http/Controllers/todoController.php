<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoTask;
use App\Models\TodoCategory;

class todoController extends Controller
{
  public function index()
  {
    $login_id = \Auth::id();
    $items = [];
    
    // カテゴリー一覧取得
    $categories = TodoCategory::where('user_id', $login_id)->orderby('sort')->get();

    // taskを取得
    $tasks = TodoTask::where('user_id', $login_id)->orderby('sort')->get();

    // カテゴリーとtaskを紐付け
    foreach($categories as $category){
      $item = [];
      
      // 紐づくtaskを取得し、インデックスを採番
      $tasks_of_this_category = $tasks->where('category_id', $category->category_id)->values();

      $item['category_id'] = $category->category_id;
      $item['name'] = $category->name;
      $item['sort'] = $category->sort;
      $item['tasks'] = $tasks_of_this_category;

      array_push($items, $item);
    }

    return compact('items');
  }

  public function update(Request $request)
  {
    $login_id = \Auth::id();
    $items = $request->input('items');
    $delete_tasks = $request->input('delete_tasks');
    $delete_categories = $request->input('delete_categories');

    foreach($delete_categories as $delete_category){
      TodoCategory::where('user_id', $login_id)->where('category_id', $delete_category['category_id'])->delete();
    }    

    foreach($delete_tasks as $delete_task){
      TodoTask::where('user_id', $login_id)->where('task_id', $delete_task['task_id'])->delete();
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
        TodoCategory::where('user_id', $login_id)->where('category_id', $category_id)->update($copy_item);
      }
      // tasksの登録・更新
      $tasks = $item['tasks'];
      foreach($tasks as $task){
        $task_id = $task['task_id'];

        if(array_key_exists('is_new', $task)){
          unset($task['is_new'], $task['task_id']);
          $task['category_id'] = $category_id;
          $task['user_id'] = $login_id;
          $inserted_task = TodoTask::create($task);
          $task_id = $inserted_task->task_id;
        }else{
          TodoTask::where('user_id', $login_id)->where('task_id', $task['task_id'])->update($task);
        }
      }
    }

    return $this->index();
  }
}
