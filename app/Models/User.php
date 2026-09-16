<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey ='user_id';

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $fillable = [
        'login_id',
        'password',
        'force_password_reset',
        'name', 
        'designation', 
        'landline', 
        'mobile', 
        'email', 
        'user_role_id', 
        'office_id', 
        'emp_id', 
    ];

    public function office()
    {
        return $this->BelongsTo(Office::class,'office_id','office_id');
    }
    public function role()
    {
        return $this->BelongsTo(UserRole::class,'user_role_id','user_role_id');
    }
}
