<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $primaryKey ='ward_id';
    public $timestamps = true;

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','city_id');
    }

}
