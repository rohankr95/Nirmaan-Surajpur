<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;


class Employee extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'emp_id';
    public $timestamps = true;
    protected $table ="employees";
    public function office()  
    {
        return $this->belongsTo(Office::class,'office_id','office_id');
    }
    public function designation()
    {
        return $this->belongsTo(EmployeeDesignation::class,'emp_designation_id','designation_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($employee) {
            User::create([
                'login_id' => $employee->emp_email,
                'password' => '$2y$10$V0wbuecslzZjCrjtyZ7tm.ehxkya7uhm61iB12rMxM93.A34oZB6i',
                'name' => $employee->emp_name,
                'designation' => $employee->emp_designation_id,
                'landline' => 'N/A',
                'mobile' => $employee->emp_mobile,
                'email' => $employee->emp_email,
                'user_role_id' => 3,
                'office_id' => $employee->office_id,
                'emp_id' => $employee->emp_id,
            ]);
        });


        // static::updated(function ($employee) {
            
        //     $user = User::where('login_id', $employee->emp_email)->first();
        //     dd($user);
        //     if ($user) {
        //         // Update user information based on employee changes
        //         $user->update([
        //             // Update user fields as needed
        //             'login_id' => $employee->emp_mobile,
        //         ]);
        //     }
        // });

    }
}
