<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Carbon\Carbon;
use App\Call;
use App\AccountCode;

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
    public function report(Request $request)
    {	
    	$start_date = $request->input('start-date');
    	$end_date = $request->input('end-date');

    	$calls = Call::select(DB::raw('account_codes.name, src, cdr.accountcode, count(cdr.accountcode) as totalcalls, sum(billsec) as totalbill'))
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

        $top_numbers = Call::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                   ->where('calldate', '>=', "$start_date 00:00:00")
                   ->where('calldate', '<=', "$end_date 23:59:00") 
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('dst')
                   ->orderByRaw('count(dst) desc')
                   ->take(10)
                   ->get();


		$request->session()->put('start_date', $start_date);
		$request->session()->put('end_date', $end_date);

		$data = [
			'calls' => $calls,
            'top_numbers' => $top_numbers,
			'start_date' => $this->formatDate($start_date),
			'end_date' => $this->formatDate($end_date)
		];

		return view('report', $data);	
    }


    /**
     * [index description]
     * @return [type] [description]
     */
    public function fullreport(Request $request)
    {   
        $start_date = $request->session()->get('start_date');
        $end_date = $request->session()->get('end_date');

        $calls = Call::select(DB::raw('account_codes.name, src, cdr.accountcode, count(cdr.accountcode) as totalcalls, sum(billsec) as totalbill'))
                   ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
                   ->where('calldate', '>=', "$start_date 00:00:00")
                   ->where('calldate', '<=', "$end_date 23:59:00")
                   ->where('cdr.accountcode', '!=', '')
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('cdr.accountcode')
                   ->orderByRaw('sum(billsec) desc')
                   ->get(); 

        $top_numbers = Call::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                   ->where('calldate', '>=', "$start_date 00:00:00")
                   ->where('calldate', '<=', "$end_date 23:59:00") 
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('dst')
                   ->orderByRaw('count(dst) desc')
                   ->get();

        $data = [
            'calls' => $calls,
            'top_numbers' => $top_numbers,
            'start_date' => $this->formatDate($start_date),
            'end_date' => $this->formatDate($end_date)
        ];

        return view('full_report', $data);   
    }

    
    /**
     * [report description]
     * @return [type] [description]
     */
    public function usingAccountCode(Request $request, $accountcode)
    {
    	$start_date = $request->session()->get('start_date');
    	$end_date = $request->session()->get('end_date');

    	$calls = Call::select('calldate', 'dst', 'billsec')
				   ->where('calldate', '>=', "$start_date 00:00:00")
				   ->where('calldate', '<=', "$end_date 23:59:00")
				   ->where('accountcode', '=', $accountcode)
				   ->where('outbound_cnum', '!=', '')
				   ->where('disposition', '=', 'ANSWERED')
				   ->orderBy('calldate', 'asc')
				   ->get();

        $summary = Call::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                    ->where('calldate', '>=', "$start_date 00:00:00")
                    ->where('calldate', '<=', "$end_date 23:59:00")
                    ->where('accountcode', '=', $accountcode)
                    ->where('outbound_cnum', '!=', '')
                    ->where('disposition', '=', 'ANSWERED')
                    ->groupBy('dst')
                    ->orderByRaw('count(dst) desc')
                    ->get();

		$name = $this->getAccountName($accountcode);
		$totalcalls = $this->getTotalCallsMade($accountcode);

		$data = [
			'name' => $name,
			'accountcode' => $accountcode,
			'totalcalls' => $totalcalls,
			'calls' => $calls,
            'summary' => $summary,
			'start_date' => $this->formatDate($start_date),
			'end_date' => $this->formatDate($end_date)
		];
    	
    	return view('report_details', $data);
    }


    public function usingPhoneNumber(Request $request, $number)
    {
        $start_date = $request->session()->get('start_date');
        $end_date = $request->session()->get('end_date');

        $calls = Call::select(DB::raw('calldate, account_codes.name, billsec'))
                    ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
                    ->where('calldate', '>=', "$start_date 00:00:00")
                    ->where('calldate', '<=', "$end_date 23:59:00")  
                    ->where('dst', '=', $number) 
                    ->where('disposition', '=', 'ANSWERED')  
                    ->orderBy('calldate', 'desc')
                    ->get();

        $data = [
            'number' => $number,
            'calls' => $calls,
            'start_date' => $this->formatDate($start_date),
            'end_date' => $this->formatDate($end_date)
        ];
        
        return view('report_by_phone', $data);
    }


    /**
     * [getAccountName description]
     * @param  [type] $accountcode [description]
     * @return [type]              [description]
     */
    private function getAccountName($accountcode)
    {
    	$account = AccountCode::select('name')
    						->where('accountcode', $accountcode)
    						->get();

    	return $account->pop()['name'];	
    }

    /**
     * [getTotalCallsMade description]
     * @param  [type] $accountcode [description]
     * @return [type]              [description]
     */
    private function getTotalCallsMade($accountcode)
    {	
    	$start_date = session('start_date');
    	$end_date = session('end_date');

    	$callcount = Call::select(DB::raw('count(accountcode) as totalcalls'))
    				->where('accountcode', $accountcode)
    				->where('calldate', '>=', "$start_date 00:00:00")
					->where('calldate', '<=', "$end_date 23:59:00")
				    ->where('outbound_cnum', '!=', '')
				    ->where('disposition', '=', 'ANSWERED')
				    ->get();

		return $callcount->pop()['totalcalls'];
    }

    /**
     * [formatDate description]
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    private function formatDate($date)
    {	
    	$dateExploded = explode("-", $date);
    	$carbonDate = Carbon::createFromDate($dateExploded[0], $dateExploded[1], $dateExploded[2]);
    	$formattedDate = $carbonDate->toFormattedDateString();
    	return $formattedDate;
    }
}
