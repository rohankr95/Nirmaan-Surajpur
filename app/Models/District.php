<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $primaryKey = 'district_id';
    public $timestamps = true;

    public function state()
    {
        return $this->belongsTo(State::class,'state_id','state_id');
    }
}
