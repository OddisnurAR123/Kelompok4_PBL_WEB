<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';  // Sesuaikan dengan nama tabel Anda

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];

    // Relasi dengan model PenggunaModal
    public function notifiable()
    {
        return $this->morphTo(); // Menyatakan relasi polymorphic
    }
}
