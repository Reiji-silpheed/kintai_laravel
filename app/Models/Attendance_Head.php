<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attendance_Head extends Model
{
    public function attendance_detail():HasOne
    {
        return $this->hasOne(Attendance_Detail::class);
    }
}
