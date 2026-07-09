<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionDocument extends Model
{
    protected $fillable = [
        'booking_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
