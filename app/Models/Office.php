<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $primaryKey ='office_id';
    public function department()
    {
        return $this->BelongsTo(Department::class,'department_id','department_id');
    }
    public function engineers()
    {
        return $this->hasMany(Engineer::class,'office_id','office_id');
    }
}
