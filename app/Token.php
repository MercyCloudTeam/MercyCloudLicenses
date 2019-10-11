<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{

protected $table = 'token';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'status','remark'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
