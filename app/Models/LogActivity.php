<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject', 'details', 'subject_type', 'subject_id', 'work_id',
        'url', 'method', 'ip', 'agent', 'module', 'user_id'
    ];

    /**
     * Hindi wording for the actions the app records, so the trail reads in the
     * same language as the rest of the interface.
     */
    public const LABELS = [
        'Saved Work'           => 'नया कार्य जोड़ा गया',
        'Updated Work'         => 'कार्य में संपादन हुआ',
        'Deleted Work'         => 'कार्य हटाया गया',
        'Saved TS'             => 'तकनीकी स्वीकृति दर्ज की गई',
        'Updated TS'           => 'तकनीकी स्वीकृति अद्यतन की गई',
        'Saved AS'             => 'प्रशासकीय स्वीकृति दर्ज की गई',
        'Updated AS'           => 'प्रशासकीय स्वीकृति अद्यतन की गई',
        'Saved Tender'         => 'निविदा दर्ज की गई',
        'Updated Tender'       => 'निविदा अद्यतन की गई',
        'Saved Agreement'      => 'अनुबंध दर्ज किया गया',
        'Updated Agreement'    => 'अनुबंध अद्यतन किया गया',
        'Saved Work Progress'  => 'कार्य प्रगति दर्ज की गई',
        'Update Work Progress' => 'कार्य प्रगति अद्यतन की गई',
        'Saved Work Completed' => 'कार्य पूर्ण दर्ज किया गया',
        'Updated Work Completed' => 'कार्य पूर्ण छायाचित्र अद्यतन किया गया',
        'Deleted Photo'         => 'छायाचित्र हटाया गया',
        'Saved Work Closed'    => 'कार्य बंद किया गया',
        'Saved Work Rejected'  => 'कार्य निरस्त किया गया',
        'Saved Payment'        => 'भुगतान दर्ज किया गया',
        'User Login'           => 'लॉगिन',
        'User Logout'          => 'लॉगआउट',
        'User Change Password' => 'पासवर्ड बदला गया',
        'User Change Profile'  => 'प्रोफ़ाइल अद्यतन की गई',
    ];

    public function getLabelAttribute()
    {
        $label = self::LABELS[$this->subject] ?? $this->subject;

        return $this->details ? $label . ' — ' . $this->details : $label;
    }

    public function scopeForWork($query, $workId)
    {
        return $query->where('work_id', $workId);
    }

    public function User()
    {
        return $this->BelongsTo(User::class,'user_id','user_id');
    }
}
