<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceHead extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function attendance_details():HasMany
    {
        return $this->hasMany(AttendanceDetail::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
