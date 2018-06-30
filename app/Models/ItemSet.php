<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSet extends Model
{
    protected $table="item_set";
    protected $fillable=['item_id','set_id'];
}
