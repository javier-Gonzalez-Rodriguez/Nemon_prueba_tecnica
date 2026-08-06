<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    protected $guarded  = ['id'];
    protected $hidden   = ['id', 'created_at', 'updated_at'];
}
