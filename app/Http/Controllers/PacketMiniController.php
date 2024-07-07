<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PacketMiniController extends Controller
{
    public function getMiniPaket()
    {
        $dataPacketMini = Paket::with('questions')->where('tipe_test_packet', 'Mini Test')->get();
        return view('datapacketmini.index', compact('dataPacketMini'));
    }

    public function index($id)
    {
        $dataPacketFull = Paket::with('questions')->where('tipe_test_packet', 'Mini Test')
            ->where('_id', $id)->get();
        $dataId = $id;
        return view('datapacketfull.index', compact('dataPacketFull', 'dataId'));
    }

    public function getEntryQuestionMini($id)
    {
        $dataPacketFull = Paket::where('_id', $id)->first();
        return view('datapacketfull.entry', compact('dataPacketFull'));
    }

    public function deletePacket($id)
    {
        $selectedPacket = Paket::where('_id', $id)->first();
        $selectedPacket->delete();
        return back()->with('success', 'Data Packet berhasil dihapus');
    }

    public function editPacket(Request $request, $id)
    {
        Paket::where('_id', $id)->update([
            'name_packet' => $request->name_packet,
        ]);

        return back()->with('success', 'Data Packet berhasil diubah');
    }

    public function addPacket(Request $request)
    {
        $request->validate([
            'no_packet' => 'required',
            'name_packet' => 'required',
            'tipe_test_packet' => 'required',
        ]);

        Paket::create([
            'no_packet' => $request->no_packet,
            'name_packet' => $request->name_packet,
            'tipe_test_packet' => $request->tipe_test_packet,
        ]);

        return back()->with('success', 'Data Packet berhasil ditambahkan');
    }
}
