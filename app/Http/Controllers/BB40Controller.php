<?php

namespace App\Http\Controllers;

use App\Models\BB40;
use Illuminate\Http\Request;

class BB40Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // BB40::where('jenis','BC 4.0')->orderBy('datedoc','desc')->get();
        $data = new BB40();
        $data2 = $data->bcbb40();
        
        foreach ($data2 as $row) {
            $harga_penyerahan = $row->harga_po * $row->qty_flow;
            $kode_item = substr($row->kode_item,0,1); 
            if ($kode_item == 9) {
                $tujuan_kirim = '2';
            }else {
                $tujuan_kirim = '1';
            }

            return response()->json([
                'status' => true,
                'message' => 'Data BC 4.0 ditemukan',
                'asalData' => 'S',
                'asuransi' => number_format(0, 2),
                'bruto' => $row->qty,
                'cif' => 0.00,
                'kodeJenisTpb' => 1,
                'freight' => number_format(0, 2),
                'hargaPenyerahan' => $harga_penyerahan,
                'idPengguna' => '-',
                'jabatanTtd' => 'KUASA DIREKSI',
                'jumlahKontainer' => 0,
                'jumlahKontainer' => '40',
                'kodeKantor' => '071300',
                'kodeTujuanPengiriman' => $tujuan_kirim,
                'kotaTtd' => 'PASURUAN',
                'namaTtd' => 'ADI SURYA',
                'netto' => $row->qty,
                'nik' => '017185901651000',
                'nomorAju' => 'waiting list ...',
                'seri' => 0,
                'tanggalAju' => date('Y-m-d', strtotime($row->DATE)),
                'tanggalTtd' => date('Y-m-d', strtotime($row->DATE)), 
                'volume' => 'Waiting List ..',
                'biayaTambahan' => 0.00,
                'biayaPengurang' => 0.00,
                'vd' => 0.00,
                'uangMuka' => 0.00,
                'nilaiJasa' => 0.00,
                'entitas' => array(
                    'property' => $row->docp
                )
            ],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BB40 $bB40)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BB40 $bB40)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BB40 $bB40)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BB40 $bB40)
    {
        //
    }
}
