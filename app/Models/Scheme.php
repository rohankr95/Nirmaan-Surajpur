<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_id';

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','department_id');
    }
}
