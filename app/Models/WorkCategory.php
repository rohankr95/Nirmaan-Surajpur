<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_category_id';

    protected $fillable = ['work_category_name'];

    public function work_types()
    {
        return $this->hasMany(WorkType::class, 'work_category_id', 'work_category_id');
    }
}
