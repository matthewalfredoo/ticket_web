<?php

namespace App\Http\Controllers\Api;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaksi;
use App\TransaksiDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(int $user_id)
    {
        $transaksi = Transaksi::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => 1,
            'message' => 'Get Transaksi Berhasil',
            'list_transaksi' => $transaksi
        ]);
    }

    public function show(int $transaksi_id)
    {
        $transaksi = Transaksi::find($transaksi_id);
        return response()->json([
            'success' => 1,
            'message' => 'Get Transaksi Berhasil',
            'transaksi' => $transaksi,
        ]);
    }

    public function store(Request $request)
    {
        //nama, email, password
        $validasi = Validator::make($request->all(), [
            'user_id' => 'required',
            'total_item' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'detail_tanggal' => 'required',
        ]);
        $totalHargaTransaksi = 0;

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $kode_payment = "INV/PYM/" . now()->format('Y-m-d') . "/" . rand(100, 999);
        $kode_trx = "INV/PYM/" . now()->format('Y-m-d') . "/" . rand(100, 999);
        $kode_unik = rand(100, 999);
        $status = "MENUNGGU";
        $expired_at = now()->addDay();

        $dataTransaksi = array_merge($request->all(), [
            'kode_payment' => $kode_payment,
            'kode_trx' => $kode_trx,
            'kode_unik' => $kode_unik,
            'status' => $status,
            'expired_at' => $expired_at,
        ]);

        DB::beginTransaction();
        $transaksi = Transaksi::create($dataTransaksi);

        $ticketArr = [];

        foreach ($request->tickets as $ticket) {
            $ticketArr[] = $ticket;
            $ticketDb = Ticket::find($ticket['id']);

            $transaksiDetail = new TransaksiDetail();
            $transaksiDetail->transaksi_id = $transaksi->id;
            $transaksiDetail->produk_id = $ticketDb->id;
            $transaksiDetail->total_item = $ticket['total_item'];

            $totalHargaTiket = $ticketDb->harga * $ticket['total_item'];
            $transaksiDetail->total_harga = $totalHargaTiket;
            $totalHargaTransaksi += $totalHargaTiket;

            $transaksiDetail->save();
        }

        $transaksi->total_harga = $totalHargaTransaksi;
        $transaksi->save();

        if (!empty($transaksi) && !empty($transaksiDetail)) {
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Transaksi Berhasil',
                'transaksi' => collect($transaksi)
            ]);
        } else {
            DB::rollback();
            $this->error('Transaksi Gagal');
        }
    }

    public function uploadBuktiTransfer(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'transaksi_id' => 'required',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            'total_transfer' => 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $transaksi = Transaksi::find($request->transaksi_id);

        if(file_exists($transaksi->bukti_transfer)) {
            unlink($transaksi->bukti_transfer);
        }

        $transaksi->status = "MENUNGGU KONFIRMASI";
        $transaksi->total_transfer = $request->total_transfer;

        $file = $request->file('file');
        $fileNameClient = str_replace(' ', '', $file->getClientOriginalName());
        $fileName = date('mYdHs') . rand(1, 999) . '-' . $fileNameClient;
        $file->move('bukti-transfer-transaksi', $fileName);

        $transaksi->bukti_transfer = 'bukti-transfer-transaksi/' . $fileName;
        $transaksi->save();

        if (!empty($transaksi)) {
            return response()->json([
                'succes' => 1,
                'message' => 'Bukti Bayar Berhasil',
                'transaksi' => collect($transaksi)
            ]);
        } else {
            $this->error('Bukti Bayar Gagal');
        }
    }

    public function error($pesan)
    {
        return response()->json([
            'success' => 0,
            'message' => $pesan
        ]);
    }
}
