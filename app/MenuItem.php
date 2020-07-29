<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table="admin_menu_items";

    protected $fillable=['label','link','parent','sort','class','menu','depth'];
}
