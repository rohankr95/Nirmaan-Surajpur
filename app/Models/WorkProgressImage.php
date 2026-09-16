<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProgressImage extends Model
{
    use HasFactory;

    protected $primaryKey = 'wp_image_id';

    protected $fillable = ['work_progress_id', 'file_path'];

    public function workProgress()
    {
        return $this->belongsTo(WorkProgress::class, 'work_progress_id', 'wp_id');
    }
}
