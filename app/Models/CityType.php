<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityType extends Model
{
    use HasFactory;
    protected $primaryKey = 'city_type_id';
    public $timestamps = true;

}
