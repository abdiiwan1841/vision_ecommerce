<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    
    protected $dates = ['purchased_at'];

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }

    public function product(){
        return $this->belongsToMany('App\Product')->withPivot('price','qty','supplier_id','purchased_at')->withTimestamps();
    }
}
