<?php

namespace App;

use App\AccountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Report extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cdr';

      /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['calldate'];

    /**
     * [generateSummary description]
     * @param  [type] $dates [description]
     * @return [type]        [description]
     */
    public static function generateSummary($dates)
    {   
        $report['numbersCalled'] = self::getNumbersCalled($dates);
        $report['accountsUsed'] = self::getAccountCodesUsed($dates);

        return $report;
    }


    public static function generateAccountCodes($accountcode, $dates)
    {
        $report['callSummary'] = self::getCallSummary($accountcode, $dates);
        $report['callList'] = self::getCallList($accountcode, $dates);

        $totalCost = self::calculateTotalCost($report['callList']);

        $report['reportHeadings'] = [
            'name' => AccountCode::getName($accountcode),
            'totalCalls' => count($report['callList']),
            'totalCost' => $totalCost
        ];

        return $report;
    }


    public static function generateAllCalls($number, $dates)
    {
        $report['allCalls'] = self::getAllCallsToNumber($number, $dates);
        $totalCost = self::calculateTotalCost($report['allCalls']);
        $report['reportHeadings'] = [
            'number' => $number,
            'totalCalls' => count($report['allCalls']),
            'totalCost' => $totalCost
        ];

        return $report;
    }


    public function formatCost()
    {      
        $minutes = ($this->totaltime / 60);
        $cost = $minutes * env('AVG_COST_PER_MIN');
        return number_format($cost, 2);
    }


    public function formatTime()
    {   
        return sprintf('%02d:%02d:%02d', ($this->totaltime / 3600), 
            ($this->totaltime / 60 % 60), $this->totaltime % 60); 
    }


    public static function getNumbersCalled($dates)
    {
        return self::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                   ->where('calldate', '>=', $dates['start'] . "00:00:00")
                   ->where('calldate', '<=', $dates['end'] . "23:59:00") 
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('dst')
                   ->orderByRaw('count(dst) desc')
                   ->get();
    }


    public static function getAccountCodesUsed($dates)
    {
        return self::select(DB::raw('account_codes.name, cdr.accountcode, count(cdr.accountcode) as totalcalls, sum(billsec) as totaltime'))
                   ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
                   ->where('calldate', '>=', $dates['start'] . "00:00:00")
                   ->where('calldate', '<=', $dates['end'] . "23:59:00")
                   ->where('cdr.accountcode', '!=', '')
                   ->where('outbound_cnum', '!=', '')
                   ->where('disposition', '=', 'ANSWERED')
                   ->groupBy('cdr.accountcode')
                   ->orderByRaw('sum(billsec) desc')
                   ->get(); 
    }


    private static function getCallSummary($accountCode, $dates)
    {
        return self::select(DB::raw('dst, count(dst) as totalcalls, sum(billsec) as totaltime'))
                ->where('calldate', '>=', $dates['start'] . "00:00:00")
                ->where('calldate', '<=', $dates['end'] . "23:59:00")
                ->where('accountcode', '=', $accountCode)
                ->where('outbound_cnum', '!=', '')
                ->where('disposition', '=', 'ANSWERED')
                ->groupBy('dst')
                ->orderByRaw('count(dst) desc')
                ->get();
    }


    private static function getCallList($accountCode, $dates)
    {
        return self::select(DB::raw('calldate, dst, billsec as totaltime'))
               ->where('accountcode', '=', $accountCode)
               ->where('calldate', '>=', $dates['start'] . "00:00:00")
               ->where('calldate', '<=', $dates['end'] . "23:59:00")
               ->where('outbound_cnum', '!=', '')
               ->where('disposition', '=', 'ANSWERED')
               ->orderBy('calldate', 'desc')
               ->get();
    }


    public static function getAllCallsToNumber($number, $dates)
    {
        return self::select(DB::raw('calldate, account_codes.name, billsec as totaltime'))
                ->join('account_codes', 'cdr.accountcode', '=', 'account_codes.accountcode')
                ->where('calldate', '>=', $dates['start'] . "00:00:00")
                ->where('calldate', '<=', $dates['end'] . "23:59:00") 
                ->where('dst', '=', $number) 
                ->where('disposition', '=', 'ANSWERED')  
                ->orderBy('calldate', 'desc')
                ->get();
    }


    private static function calculateTotalCost($collection)
    {   
        $calls = $collection->all();
        $seconds = 0;
        foreach ($calls as $call) {
          $seconds = $seconds + $call->totaltime;
        }

        $minutes = ($seconds / 60); // convert billing seconds to minutes.
        $totalCost = $minutes * env('AVG_COST_PER_MIN');

        return number_format($totalCost, 2);
    } 
}
