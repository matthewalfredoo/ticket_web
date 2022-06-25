<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $filable = [
        'user_id', 'kode_payment', 'kode_trx', 'total_item', 'total_harga', 'kode_unik',
        'status', 'name', 'phone', 'detail_tanggal', 'deskripsi', 'metode', 'total_transfer',
        'bank', 'expired_at'
    ];

    public function details(){
        return $this->hasMany(TransaksiDetail::class,"transaksi_id","id");
    }

    public function user(){
        return $this -> belongsTo(User::class, "user_id", "id");
    }
}
