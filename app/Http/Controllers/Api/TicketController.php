<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticket;

class TicketController extends Controller
{
    public function index()
    {



        // dd($request->all());die();

        $ticket = Ticket::all();
        return response()->json([
            'success' => 1,
            'message' => 'Get Tiket Berhasil',
            'tickets' => $ticket
        ]);
    }
}
