<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'riwayat_aktivitas';
    public $timestamps = false;

    protected $fillable = [
        'ID_RIWAYAT_ALTIVITAS',
        'WAKTU',
        'DESKRIPSI'
    ];
}
