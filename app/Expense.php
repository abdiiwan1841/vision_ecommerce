<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $dates = ['expense_date'];
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
}
