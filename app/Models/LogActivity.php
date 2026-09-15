<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject', 'url', 'method', 'ip', 'agent','module', 'user_id'
    ];
    public function User()
    {
        return $this->BelongsTo(User::class,'user_id','user_id');
    }
}
