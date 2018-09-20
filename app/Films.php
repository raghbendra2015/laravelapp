<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Films extends Authenticatable {

    use SoftDeletes;
    use Notifiable;

    /**
     * The database table and primary key used by the model.
     * @var string
     */
    protected $table;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];


}
