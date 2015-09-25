<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AccountCode;

class AccountCodeController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
    	$accountcodes = AccountCode::all();
    	return view('accountcodes', ['accountcodes' => $accountcodes]);
    }
}
