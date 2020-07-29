<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $dates = ['expired_at'];
    public function dealproduct(){
        return $this->belongsTo('App\Product','product');
    }
}
