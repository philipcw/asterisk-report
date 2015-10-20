<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'accountcode'];

    /**
     * Get the name associated with the specified account code.
     * @param  int $accountcode 
     * @return string 
     */
    public static function getName($accountcode)
    {
    	return self::select('name')
			->where('accountcode', $accountcode)
			->get()->pop()['name'];
    }
}