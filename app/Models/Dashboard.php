<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    public function financial_year()
    {
        return $this->belongsTo(FinancialYear::class,'financial_year_id','id');
    }
}
