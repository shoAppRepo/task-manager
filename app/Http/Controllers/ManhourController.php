<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manhour;

class ManhourController extends Controller
{
  public function index()
  {
    $login_id = \Auth::id();
    $manhours = Manhour::where('user_id', $login_id)->get();

    return compact('manhours');
  }

  public function update(Request $request)
  {
    $item = $request->input('item');

    if($item['is_new']){
      unset($item['is_new']);
      $item['user_id'] = \Auth::id();
      Manhour::create($item);
    }else{
      Manhour::where('manhour_id', $item['manhour_id'])->update($item);
    }

    return $this->index();
  }
}
