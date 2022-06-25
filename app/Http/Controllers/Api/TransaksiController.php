<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaksi;
use App\TransaksiDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function store (Request $request)
    {
        //nama, email, password
        $validasi = Validator::make($request->all(), [
            'user_id' => 'required',
            'total_item' => 'required',
            'total_harga' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'detail_tanggal' => 'required',

        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $kode_payment = "INV/PYM/".now()->format('Y-m-d')."/".rand(100,999);
        $kode_trx = "INV/PYM/".now()->format('Y-m-d')."/".rand(100,999);
        $kode_unik = rand(100,999);
        $status = "MENUNGGU";
        $expired_at = now()->addDay();


        $dataTransaksi = array_merge($request->all(), [
            'kode_payment' => $kode_payment,
            'kode_trx' => $kode_trx,
            'kode_unik' => $kode_unik,
            'status' => $status,
            'expired_at' => $expired_at,
        ]);

        \DB::beginTransaction;
        $transaksi = Transaksi::create($dataTransaksi);
        foreach($request->tickets as $ticket){
            $detail = [
                'transaksi_id' => $transaksi->id,
                'produk_id' => $ticket['id'],
                'total_item' => $ticket['total_item'],
                'catatan' => $ticket['catatan'],
                'total_harga' => $ticket['total_harga']
            ];
            $transaksi_detail = TransaksiDetail::create($detail);
        }

        if (!empty($transaksi)&& !empty($transaksiDetail)){
            \DB::commit();
            return response()->json([
                'succes' => 1,
                'message' => 'Transaksi Berhasil',
                'user' => collect($transaksi)
            ]);
        }else{
            \DB::rollback();
            $this -> error('Transaksi Gagal');
        }

    }

    public function error($pesan){
        return response()->json([
            'success' => 1,
            'message' => $pesan
        ]);
    }
}
