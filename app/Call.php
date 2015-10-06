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

    /**
     * [formatTime description]
     * @return [type] [description]
     */
    public function formatTime()
    {   
        return sprintf('%02d:%02d:%02d', ($this->totaltime / 3600), 
            ($this->totaltime / 60 % 60), $this->totaltime % 60); 
    }
}
