<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $filable = [
        'user_id', 'jumlah', 'harga', 'tanggal', 'bukti_transfer', 'status'
    ];

}
