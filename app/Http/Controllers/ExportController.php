<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Call;
use DB;

class ExportController extends Controller
{	
	/**
	 * [usingAccountCodes description]
	 * @return [type] [description]
	 */
    public function usingAccountCodes()
    { 
    	Excel::create(time() . '_export_names', function($excel) {
            $excel->sheet('persons_who_made_the_most_calls', function($sheet) {
                $start_date = session('start_date');
                $end_date = session('end_date');

                $calls = Call::select(DB::raw('account_codes.name, src, cdr.accountcode, count(cdr.accountcode) as totalcalls, sum(billsec) as totaltime'))
                           ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
                           ->where('calldate', '>=', "$start_date 00:00:00")
                           ->where('calldate', '<=', "$end_date 23:59:00")
                           ->where('cdr.accountcode', '!=', '')
                           ->where('outbound_cnum', '!=', '')
                           ->where('disposition', '=', 'ANSWERED')
                           ->groupBy('cdr.accountcode')
                           ->orderByRaw('sum(billsec) desc')
                           ->take(10)
                           ->get();
                $sheet->fromArray($calls);
            });
        })->export('xlsx');
    }

    /**
     * [usingPhoneNumbers description]
     * @return [type] [description]
     */
    public function usingPhoneNumbers()
    {
    	Excel::create(time() . '_export_numbers', function($excel) {
            $excel->sheet('numbers_called_the_most', function($sheet) {
                $start_date = session('start_date');
                $end_date = session('end_date');

                $top_numbers = Call::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                   ->where('calldate', '>=', "$start_date 00:00:00")
                   ->where('calldate', '<=', "$end_date 23:59:00") 
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('dst')
                   ->orderByRaw('count(dst) desc')
                   ->take(10)
                   ->get();
                $sheet->fromArray($top_numbers);
            });
        })->export('xlsx');
    }
}
