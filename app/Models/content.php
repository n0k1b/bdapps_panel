<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class content extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function apps()
    {
        return $this->belongsTo('App\Models\AppList','app_id','id');
    }
}
