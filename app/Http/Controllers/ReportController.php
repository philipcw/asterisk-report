<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Call;

class ReportController extends Controller
{

	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function index()
	{
		return view('home');
	}

	/**
	 * [index description]
	 * @return [type] [description]
	 */
    public function report()
    {
    	dd(
    		Call::select(DB::raw('account_codes.name, src, cdr.accountcode, count(cdr.accountcode), sum(billsec)'))
		   ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
		   ->where('calldate', '>=', '2015-08-01 00:00:00')
		   ->where('calldate', '<=', '2015-08-30 23:59:00')
		   ->where('cdr.accountcode', '!=', '')
		   ->where('outbound_cnum', '!=', '')
		   ->where('disposition', '=', 'ANSWERED')
		   ->groupBy('cdr.accountcode')
		   ->orderByRaw('sum(billsec) desc')
		   ->take(10)
		   ->get()
    	);
    }

    
    /**
     * [report description]
     * @return [type] [description]
     */
    public function details()
    {
    	var_dump (
    		Call::select('calldate', 'dst', 'billsec')
			   ->where('calldate', '>=', '2015-08-01 00:00:00')
			   ->where('calldate', '<=', '2015-08-30 23:59:00')
			   ->where('accountcode', '=', '1866')
			   ->where('outbound_cnum', '!=', '')
			   ->where('disposition', '=', 'ANSWERED')
			   ->orderBy('billsec', 'desc')
			   ->get()
    	);
    }
}
