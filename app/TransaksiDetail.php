<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = [
        'id', 'transaksi_id', 'produk_id', 'total_item', 'catatan', 'total_harga'
    ];

    public function transaksi(){
        return $this -> belongsTo(Transaksi::class, "transaksi_id", "id");
    }

    public function ticket(){
        return $this -> belongsTo(Ticket::class, "produk_id", "id");
    }
}
