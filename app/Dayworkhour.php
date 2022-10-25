<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dayworkhour extends Model
{
    protected $fillable = [
        'employee_num', 'date', 'starttime', 'endtime', 'resthours', 'workhours_each_day', 'workhours_each_month', 'workhours_each_year',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
