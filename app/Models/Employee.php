<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


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

    /**
     * Plain-text password generated for the auto-provisioned login, readable
     * only on the instance that created it so it can be shown to the admin once.
     */
    public $generatedPassword;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($employee) {
            if (empty($employee->emp_email)) {
                return;
            }

            $employee->generatedPassword = Str::random(12);

            User::create([
                'login_id' => $employee->emp_email,
                'password' => Hash::make($employee->generatedPassword),
                'force_password_reset' => true,
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
