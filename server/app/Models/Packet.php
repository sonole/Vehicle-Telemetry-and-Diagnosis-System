<?php
/**
 *  --- Coded by Le ---
 *  at 21/11/2022 5:28 PM
 *  code full of 🐛🦗🦟
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model {

    use HasFactory;

    protected $fillable = [
        'sn', 'datetime',
        'latitude', 'longitude', 'altitude', 'speed', 'course', 'satellites',
        'service1pid00', 'pids', 'dtc_status',
        'crc'
    ];

    protected $casts = [
        'pids' => 'array',
    ];
}
