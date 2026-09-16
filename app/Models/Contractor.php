<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;

    protected $primaryKey = 'contractor_id';

    protected $fillable = [
        'contractor_name', 'contact_person', 'mobile',
        'registration_no', 'address', 'status',
    ];

    public function agreements()
    {
        return $this->hasMany(Agreement::class, 'contractor_id', 'contractor_id');
    }
}
