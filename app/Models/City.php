<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class City extends Model
{
    use HasFactory;
    protected $primaryKey = 'city_id';
    public $timestamps = true;

     public function subdivision()
     {
         return $this->BelongsTo(Subdivision::class,'subdivision_id','subdivision_id');
     }
}
