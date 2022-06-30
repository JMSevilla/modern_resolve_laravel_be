<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class MDRUsers extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
        'userType',
        'isLock',
        'imgURL',
        'occupationStatus',
        'occupationDetails',
        'occupationPositionWork',
        'nameofschool',
        'degree',
        'address'
    ];
   
}
