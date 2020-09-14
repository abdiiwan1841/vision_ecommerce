<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee  extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    
    protected $fillable = [
        'name','email','phone','password','address','joining_date','salary','nid','employee_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function employee_type(){
        return $this->belongsTo('App\EmployeeType');
    }
}
