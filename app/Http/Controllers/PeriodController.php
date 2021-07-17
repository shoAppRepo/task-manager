<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Http\Controllers\CategoryController;

class PeriodController extends Controller
{
  public function index()
  {
    $login_id = \Auth::id();
    $periods = Period::where('user_id', $login_id)->get();

    return compact('periods');
  }

  public function indexWithItems($params)
  {
    list(
      'periods' => $periods,
    ) = $this->index();

    $items = [];
    $selected_period = $params[0];

    if(is_numeric($selected_period)){
      $CtgController = new CategoryController();
      list(
        'items' => $items,
      )= $CtgController->index([$selected_period]);
    }

    return compact('periods', 'items');
  }

  public function update(Request $request)
  {
    $login_id = \Auth::id();
    $periods = $request->input('periods');
    $delete_periods = $request->input('delete_periods');

    foreach($delete_periods as $delete_period){
      Period::where('user_id', $login_id)->where('period_id', $delete_period['period_id'])->delete();
    }

    foreach($periods as $period){
      if(array_key_exists('is_new', $period)){
        unset($period['is_new']);
        $period['user_id'] = $login_id;
        Period::create($period);
      }else{
        Period::where('user_id', $login_id)->where('period_id', $period['period_id'])->update($period);
      }
    }

    return $this->index();
  }
}
