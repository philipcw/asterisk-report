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
     * Generates a report for all numbers called and account codes used for
     * the specified dates.
     * @param  Array $dates : contains both start and end dates
     * @return Array
     */
    public static function generateSummary($dates)
    {   
        $report['numbersCalled'] = self::getNumbersCalled($dates);
        $report['accountsUsed'] = self::getAccountCodesUsed($dates);

        return $report;
    }

    /**
     * Generates a detailed report for the specified dates using the given 
     * account code. This report consist of all calls using this account.
     * @param  Int $accountcode
     * @param  Array $dates
     * @return Array
     */
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

    /**
     * Generates a detailed report for the specified dates using the given
     * phone number. This report consist of all calls to this number.
     * @param  Int $number
     * @param  Array $dates
     * @return Array
     */
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

    /**
     * Calcuates the cost for a given call. 
     * @return String
     */
    public function formatCost()
    {      
        $minutes = ($this->totaltime / 60);
        $cost = $minutes * env('AVG_COST_PER_MIN');
        return number_format($cost, 2);
    }

    /**
     * Converts a unix timestamp to something more friendly.
     * @return String
     */
    public function formatTime()
    {   
        return sprintf('%02d:%02d:%02d', ($this->totaltime / 3600), 
            ($this->totaltime / 60 % 60), $this->totaltime % 60); 
    }

    /**
     * Return all phone numbers called for the specified dates.
     * @param  Array $dates
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return all account codes used within the specified dates.
     * @param  Array $dates
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return an overview of calls made using the account code within 
     * the specified dates.
     * @param  Int $accountCode
     * @param  Array $dates
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return a detailed list of all calls made using account code within
     * the specified dates.
     * @param  Int $accountCode
     * @param  Array $dates
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return calls made to this number within the specified dates. 
     * @param  Int $number
     * @param  Array $dates
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return the total cost for all calls given.
     * @param  Illuminate\Database\Eloquent\Collection $collection
     * @return String
     */
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
