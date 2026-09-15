<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;
    protected $primaryKey ='subdivision_id';
    public function district()
    {
        return $this->BelongsTo(District::class,'district_id','district_id');
    }
}
