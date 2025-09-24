<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'user_id',
        'guard_name',
        'daDoc',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}