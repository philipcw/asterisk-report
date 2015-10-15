<?php

namespace App\Http\Middleware;

use Closure;
use Validator;

class ValidateAndFlashDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $validator = Validator::make($request->all(), [
            'start-date' => 'required',
            'end-date'   => 'required'
        ]);

        if($validator->fails()) {
            return redirect('/')->withErrors($validator);
        } else {
            $request->session()->put('start-date',
                $request->input('start-date'));

            $request->session()->put('end-date',
                $request->input('end-date'));
        }

        return $next($request);
    }
}
