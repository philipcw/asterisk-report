<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Report;

class DownloadController extends Controller
{	
    /**
     * Creates an excel document with a report of all numbers called
     * and account codes used.
     */
    public function report()
    {   
        Excel::create(time() . '_report', function($excel) {
            $excel->sheet('persons', function($sheet) {
                $dates = self::retrieveDates();
                $persons = Report::getAccountCodesUsed($dates);
                $sheet->fromArray($persons);
            });

            $excel->sheet('numbers', function($sheet) {
                $dates = self::retrieveDates();
                $numbers = Report::getNumbersCalled($dates);
                $sheet->fromArray($numbers);
            });
        })->export('xlsx');
    }

    /**
     * Get the dates stored in session.
     * @return Array $dates
     */
    private function retrieveDates()
    {
        $dates['start'] = session('start-date');
        $dates['end'] = session('end-date');
        return $dates;
    }
}