<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttendanceHead extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function attendance_detail():HasOne
    {
        return $this->hasOne(AttendanceDetail::class);
    }
}
