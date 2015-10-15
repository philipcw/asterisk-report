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
     * [getName description]
     * @param  [type] $accountcode [description]
     * @return [type]              [description]
     */
    public static function getName($accountcode)
    {
    	return self::select('name')
			->where('accountcode', $accountcode)
			->get()->pop()['name'];
    }
}