<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class User extends Authenticatable {

    use SoftDeletes;
    use Notifiable;

    /**
     * The database table and primary key used by the model.
     * @var string
     */
    protected $table;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
         'name', 'email', 'password','username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * @var array
     */
    public static $userLoginValidationRules = [
        'username' => 'required',
        'password' => 'required'
    ];
    public static $userLoginValidationMessages = [
        'username.required' => 'Please enter email.',
        'password.required' => 'Please enter password.'
    ];

    /**
     * @var array
     */
    public static function userRegisterValidationRules() {
        return [
            'name'    => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:4|max:20|confirmed'
        ];
    }
    public static $userRegisterValidationMessages = [
        'name.required'                     => 'Please enter name',
        'email.required'                    => 'Please enter email address',
        'email.email'                       => 'Email address is invalid',
        'email.unique'                      => 'This email address already exist',
        'password.required'                 => 'Please enter password',
        'password.min'                      => 'Password can be minimum 4 characters long',
        'password.max'                      => 'Password can be max 20 characters long',
        'password.confirmed'                => 'Password and confirm password do not match',
        'password_confirmation.required'    => 'Please enter confirm password',
    ];


}
