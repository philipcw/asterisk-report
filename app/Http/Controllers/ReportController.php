<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Report;

class ReportController extends Controller
{   
    /**
     * On instantiation call the 'date.flush ' middleware which 
     * validates and flashes the given dates.
     */
    public function __construct()
    {
        $this->middleware('date.flash', ['only' => 'summary']);
    }

    /**
     * Display date pickers used to generate a report.
     * @return \Illuminate\Http\Response
     */
	public function home()
	{
		return view('home');
	}

    /**
     * Display an overview of numbers called and account codes 
     * used.
     * @return \Illuminate\Http\Response
     */
    public function summary()
    {   
        $dates = $this->retrieveDates();
        $report = Report::generateSummary($dates);

		$data = [
			'report' => $report,
			'dates' => $this->formatDates()
		];

		return view('report.summary', $data);	
    }

    /**
     * Display a report for the given account code. 
     * @param  Int $accountcode
     * @return \Illuminate\Http\Response
     */
    public function accountCodes($accountcode)
    {
        $dates = $this->retrieveDates();
        $report = Report::generateAccountCodes($accountcode, $dates);

        $data = [
            'report' => $report,
            'dates' => $this->formatDates()
        ];
        
        return view('report.account_codes', $data);
    }

    /**
     * Display a report for the given phone number.
     * @param  Int $number
     * @return \Illuminate\Http\Response
     */
    public function phoneNumbers($number)
    {   
        $dates = $this->retrieveDates();
        $report = Report::generateAllCalls($number, $dates);

        $data = [
            'report' => $report,
            'dates' => $this->formatDates()
        ];
        
        return view('report.phone_numbers', $data);
    }

    /**
     * Return dates stored in the session.
     * @return Array $dates
     */
    private function retrieveDates()
    {
        $dates['start'] = session('start-date');
        $dates['end'] = session('end-date');
        return $dates;
    }
    
    /**
     * Format the dates stored in session.
     * @return Array $dates
     */
    private function formatDates()
    {   
        foreach ($this->retrieveDates() as $key => $date) 
        {
            $explode = explode("-", $date);
            $carbonMade = Carbon::createFromDate($explode[0], $explode[1], $explode[2]);
            $dates[$key] = $carbonMade->toFormattedDateString();
        }

        return $dates;
    }   
}