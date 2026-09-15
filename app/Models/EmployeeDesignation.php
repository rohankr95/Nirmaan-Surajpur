<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDesignation extends Model
{
    use HasFactory;
    protected $primaryKey = 'designation_id';
    public $timestamps = true;
    protected $table ="employees_designation";
}
