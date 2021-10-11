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

  public function insert(Request $request)
  {
    $item = $request->input('item');

    $item['user_id'] = \Auth::id();
    Manhour::create($item);

    return $this->index();
  }

  public function update(Request $request)
  {
    $item = $request->input('item');

    $item['user_id'] = \Auth::id();
    Manhour::where('man_hour_id', $item['man_hour_id'])->update($item);

    return $this->index();
  }

  public function delete(Request $request)
  {
    $item = $request->input('item');

    $item['user_id'] = \Auth::id();
    Manhour::where('man_hour_id', $item['man_hour_id'])->delete();

    return $this->index();
  }

  public function start(Request $request)
  {
    $item = $request->input('item');

    $item['user_id'] = \Auth::id();
    return Manhour::create($item);
  }

  public function stop(Request $request)
  {
    $item = $request->input('item');

    Manhour::where('man_hour_id', '=', $item['man_hour_id'])->update($item);
  }
}
