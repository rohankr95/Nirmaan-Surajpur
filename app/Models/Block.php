<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;
    protected $primaryKey = 'block_id';
    public $timestamps = true;
    public function subdivision()
    {
        return $this->BelongsTo(Subdivision::class,'subdivision_id','subdivision_id');
    }

}
