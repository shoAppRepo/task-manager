<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manhour;

class ManhourController extends Controller
{
  public function index()
  {
    $manhours = Manhour::get();

    return compact('manhours');
  }
}
