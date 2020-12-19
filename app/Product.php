<?php

namespace App;

use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['product_name', 'price', 'description', 'image'];

    public function stock($id){
        $sell =  DB::table('product_sale')->where('product_id', '=', $id)->sum('qty');
        $free =  DB::table('product_sale')->where('product_id', '=', $id)->sum('free');
        $purchase = DB::table('product_purchase')->where('product_id', '=', $id)->sum('qty');
        $order = DB::table('order_product')->where('product_id', '=', $id)->sum('qty');
        $return = DB::table('product_returnproduct')->where('product_id', '=', $id)->sum('qty');
        $damage = DB::table('damage_product')->where('product_id', '=', $id)->sum('qty');

        
        $stock = ($purchase+$return) -  ($order+$sell+$damage+$free);
        return $stock;
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function size(){
        return $this->belongsTo('App\Size');
    }
    public function subcategory(){
        return $this->belongsTo('App\Subcategory');
    }
    public function tags(){
        return $this->belongsToMany('App\Tags')->withTimestamps();
    }
    public function comments($id){
      return Comments::where('product_id',$id)->where('status',1)->get();
    }
    public function brand(){
        return $this->belongsTo('App\Brand');
    }

}
