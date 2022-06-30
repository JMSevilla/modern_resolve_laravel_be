<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tokenization extends Model
{
    protected $fillable = [
        'userID',
        'token',
        'lastRoute',
        'isDestroyed',
        'isvalid'
    ];
}
