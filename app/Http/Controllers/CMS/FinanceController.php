<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            dd('ok');
        }
        return view('cms.transactions.index', ['title'  => 'CMS | Finance']);
    }
}
