<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class AllPacketController extends Controller
{
    public function index()
    {
        $packetFull = Paket::with('questions')->where('tipe_test_packet', 'Full Test')->get();
        $packetMini = Paket::with('questions')->where('tipe_test_packet', 'Mini Test')->get();

        return view('packetfull.index', compact('packetFull', 'packetMini'));
    }
}
