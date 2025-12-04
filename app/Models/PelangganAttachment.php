<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelangganAttachment extends Model
{
    protected $table = 'pelanggan_attachments';
    protected $fillable = [
        'pelanggan_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'pelanggan_id');
    }
}
