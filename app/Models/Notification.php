<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['loai', 'noiDung', 'link', 'doiTuong'];

     public function users()
    {
        return $this->hasMany(NotificationUser::class);
    }
}
