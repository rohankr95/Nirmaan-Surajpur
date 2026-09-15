<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grampanchayat extends Model
{
    use HasFactory;
    protected $primaryKey = 'grampanchayat_id';
    public $timestamps = true;

    public function block()
    {
        return $this->belongsTo(Block::class,'block_id','block_id');
    }

}
