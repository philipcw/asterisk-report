<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
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
}
