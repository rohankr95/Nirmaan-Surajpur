<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyConstituency extends Model
{
    use HasFactory;
    protected $primaryKey ='parliamentary_constituency_id';
    public function parliamentary_constituency()
    {
        return $this->belongsTo(ParliamentaryConstituency::class,'parliamentary_constituency_id','parliamentary_constituency_id');
    }
}
