<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable=['receipt_no','discount','grand_total','cash'];
}
