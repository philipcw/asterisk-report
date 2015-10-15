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
     * [__construct description]
     */
    public function __construct()
    {
        $this->middleware('date.flash', ['only' => 'summary']);
    }

    /**
     * [home description]
     * @return [type] [description]
     */
	public function home()
	{
		return view('home');
	}

    /**
     * [summary description]
     * @return [type] [description]
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
     * [accountCodes description]
     * @param  [type] $accountcode [description]
     * @return [type]              [description]
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
     * [phoneNumbers description]
     * @param  [type] $number [description]
     * @return [type]         [description]
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
     * [retrieveDates description]
     * @return [type] [description]
     */
    private function retrieveDates()
    {
        $dates['start'] = session('start-date');
        $dates['end'] = session('end-date');
        return $dates;
    }
    
    /**
     * [formatDates description]
     * @return [type] [description]
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