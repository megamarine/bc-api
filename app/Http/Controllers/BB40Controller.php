<?php

namespace App\Http\Controllers;

use App\Models\BB40;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BB40Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */

    function index(){
        $model = new BB40();
        $data = $model->get_data_bb40();

        $arr_header = array();
        
        foreach ($data as $header) {//header? eah
            $no_spb = $header->spb;
            if ($header->address == null) {
                $alamat =  $header->city;
            }else {
                $alamat = $header->address;

            }

            $detail_spb = $model->get_detail_data_spb($no_spb);

            $arr_detail = array();
            $total_qty = 0;

            foreach ($detail_spb as $data_spb) {
                $po = substr($data_spb->po_from_spb,0,3);
                if ($po == 'POJ') {
                    $hitung_po = $data_spb->unit_price * $data_spb->qty_flow;
                }else {
                    $hitung_po = 0.00;
                }
                $arr_detail[] = array(
                    'asuransi'  => number_format(0,2),
                    'bruto' => $data_spb->qty,
                    'cif'  => number_format(0,2),
                    'diskon' => 0.00,
                    'hargaEkspor' => 0.00,
                    'hargaPenyerahan' => $data_spb->unit_price * $data_spb->qty_flow,
                    'hargaSatuan' => $data_spb->unit_price,
                    'isiPerKemasan' => 0,
                    'jumlahKemasan' => 0.00,
                    'jumlahRealisasi' => 0.00,
                    'jumlahSatuan' => $data_spb->qty_flow,
                    'kodeBarang' => $data_spb->inv,
                    'kodeDokumen' => '40',
                    'kodeJenisKemasan' => 'NE',
                    'merk' => '-',
                    'netto' => $data_spb->qty,
                    'nilaiBarang' => number_format(0,2),
                    'posTarif' => '48191000',
                    'seriBarang' => 1,
                    'spesifikasiLain' => $data_spb->spesifikasi,
                    'tipe' => "TIPE BARANG",
                    'ukuran' => '',
                    'uraian' => 'Box',
                    'volume' => 0.00,
                    'cifRupiah' => $data_spb->unit_price * $data_spb->qty_flow,
                    'hargaPerolehan' => 0.00,
                    'kodeAsalBahanBaku' => '1',
                    'ndpbm' => 1,
                    'uangMuka' => 0.00,
                    'nilaiJasa' => $hitung_po,
                    'barangTarif' =>[
                        array(
                            'kodeJenisTarif' => '1',
                            'jumlahSatuan' => $data_spb->qty_flow,
                            'kodeFasilitasTarif' => '6',
                            'kodeSatuanBarang' => $data_spb->unitgd,
                            'nilaiBayar' => $data_spb->unit_price * $data_spb->qty_flow,
                            'nilaiFasilitas' => 0.11 * $data_spb->unit_price * $data_spb->qty_flow,
                            'nilaiSudahDilunasi' => 0.00,
                            'seriBarang' => 1,
                            'tarif' => 0.00,
                            'tarifFasilitas' => 0.11 * $data_spb->unit_price * $data_spb->qty_flow,
                            'kodeJenisPungutan' => 'PPN'
                        ),
                    ]
                );
                $no_po = $data_spb->po_from_spb;
                $total_qty += $data_spb->qty_flow;

                $detail_po = $model->get_detail_data_po($no_po);
                $arr_po = array();

                $total_unit_price = 0;
                foreach ($detail_po as $data_po) {
                    $arr_po[] = array(
                        'no_po' => $data_po->po
                    );

                    $total_unit_price += $data_po->price;
                    $harga_penyerahan = $total_unit_price * $total_qty;
                    $pajak = 0.11 * $total_unit_price * $total_qty;

                }
            }

            // arr entitas
            $arr_entitas =[
                array(
                    'alamatEntitas' => 'JL. DUSUN WONOKOYO RT.03 RW.01 003/001 WONOKOYO, BEJI, PASURUAN, JAWA TIMUR',
                    'kodeEntitas' => '3',
                    'kodeJenisIdentitas' => '5',
                    'namaEntitas' => 'BARAMUDA BAHARI',
                    'nibEntitas' => '8120002941581',
                    'nomorIdentitas' => '017185901651000',
                    'nomorIjinEntitas' => '3458/KM.4/2017',
                    'seriEntitas' => 1,
                    'tanggalIjinEntitas' => '2017-12-14'
                ), array(
                    'alamatEntitas' => $alamat,
                    'kodeEntitas' => '7',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => $header->nama_supp,
                    'nibEntitas' => '-',
                    'nomorIdentitas' => '-',
                    'seriEntitas' => 2
                ), array(
                    'alamatEntitas' => 'JL. DUSUN WONOKOYO RT.03 RW.01 003/001 WONOKOYO, BEJI, PASURUAN, JAWA TIMUR',
                    'kodeEntitas' => '9',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => 'BARAMUDA BAHARI',
                    'nibEntitas' => '017185901651000',
                    'nomorIdentitas' => '017185901651000',
                    'seriEntitas' => 3
                )
            ]; 

            $arr_dokumen = [
                array(
                'kodeDokumen' => '999',
                'nomorDokumen' => $header->spb,
                'seriDokumen' => 1,
                'tanggalDokumen' => date('Y-m-d', strtotime($header->date_spb))
                )
            ];

            $arr_pengangkut = [
                array(
                'namaPengangkut' => 'MOBIL TRUK',
                'nomorPengangkut' => $header->nopol,
                'seriPengangkut' => 1
                )
            ];

            $arr_kemasan = [ 
                array(
                'jumlahKemasan' => '-',
                'kodeJenisKemasan' => 'NE',
                'merkKemasan' => '-',
                'seriKemasan' => 1
                )
            ];

            $arr_pungutan = [
                array(
                'kodeFasilitasTarif' => '3',
                'kodeJenisPungutan' => 'PPN',
                'nilaiPungutan' => $pajak
                )
            ];

            $arr_header = array(
                'asalData' => 'S',
                'asuransi' => number_format(0,2),
                'bruto' => $total_qty,
                'cif' => 0.00,
                'kodeJenisTpb' => 1,
                'freight' => number_format(0, 2),
                'hargaPenyerahan' => $harga_penyerahan,
                'idPengguna' => '-',
                'jabatanTtd' => 'KUASA DIREKSI',
                'jumlahKontainer' => 0,
                'jumlahKontainer' => '40',
                'kodeKantor' => '071300',
                'kodeTujuanPengiriman' => 'tujuan pengiriman',
                'kotaTtd' => 'PASURUAN',
                'namaTtd' => 'ADI SURYA',
                'netto' => $total_qty,
                'nik' => '017185901651000',
                'nomorAju' => 'waiting list ...',
                'seri' => 0,
                'tanggalAju' => date('Y-m-d', strtotime($header->date_bc)),
                'tanggalTtd' => date('Y-m-d', strtotime($header->date_bc)), 
                'volume' => 0.00,
                'biayaTambahan' => 0.00,
                'biayaPengurang' => 0.00,
                'vd' => 0.00,
                'uangMuka' => 0.00,
                'nilaiJasa' => 0.00,
                // Detail section
                'entitas' => $arr_entitas,
                'dokumen' => $arr_dokumen,
                'pengangkut' => $arr_pengangkut,
                'kontainer' => [],
                'kemasan' => $arr_kemasan,
                'pungutan' => $arr_pungutan,
                'barang' => $arr_detail,
            );
        }

        

        // return json_encode($arr_header);
        return response()->json($arr_header, 200);
    }

    //  public function index() {
    //     $model = new BB40();
    //     $data = $model->get_data_bb40();
        
    //     $arr_header = array();

    //     $arr_detail = array();
    //     foreach ($data as $row) {
    //         $no_trans = $row->docp;
    //         $no_spb = $row->spb;

    //         $detail_spb = $model->get_detail_data_spb($no_spb);//detail 1
             
    //         $idx = 0;
    //         foreach ($detail_spb as $row_spb) {
    //             $no_po = $row_spb->po_from_spb;
    //             $qty_spb = $row_spb->qty;

    //             $detail_po = $model->get_detail_data_po($no_po);//detail 2
    //             foreach ($detail_po as $row_po) {


    //                 $harga_penyerahan = $row_po->price * $row_spb->qty_flow;

    //                 $arr_header[] = array(
    //                     'NoTransaksi' => $no_trans,
    //                     'asaldata' => 'S',
    //                     'asuransi' => number_format(0,2),
    //                     'bruto' => $qty_spb,
    //                     'cif' => 0.00,
    //                     'kodeJenisTpb' => 1,
    //                     'freight' => number_format(0, 2),
    //                     'hargaPenyerahan' => $harga_penyerahan,
    //                     'idPengguna' => '-',
    //                     'jabatanTtd' => 'KUASA DIREKSI',
    //                     'jumlahKontainer' => 0,
    //                     'jumlahKontainer' => '40',
    //                     'kodeKantor' => '071300',
    //                     'kodeTujuanPengiriman' => 'tujuan pengiriman',
    //                     'kotaTtd' => 'PASURUAN',
    //                     'namaTtd' => 'ADI SURYA',
    //                     'netto' => $qty_spb,
    //                     'nik' => '017185901651000',
    //                     'nomorAju' => 'waiting list ...',
    //                     'seri' => 0,
    //                     'tanggalAju' => date('Y-m-d', strtotime($row->date_bc)),
    //                     'tanggalTtd' => date('Y-m-d', strtotime($row->date_bc)), 
    //                     'volume' => 0.00,
    //                     'biayaTambahan' => 0.00,
    //                     'biayaPengurang' => 0.00,
    //                     'vd' => 0.00,
    //                     'uangMuka' => 0.00,
    //                     'nilaiJasa' => 0.00,
    //                     'inv' => []
    //                 );
    //             }
    //         }
    //     }

    //     $arr_header['inv'] = $arr_detail;
    //     return response()->json(
    //         $arr_header, 200);
    //  }


// end index 1


    // public function index()
    // {
    //     // BB40::where('jenis','BC 4.0')->orderBy('datedoc','desc')->get();
    //     $data = new BB40();
    //     $data2 = $data->bcbb40();
        
    //     foreach ($data2 as $row) {
    //         $no_trans = $row->docp;
    //         // $no_po = $row->oms;
    //         // $no_spb = $row->spb;

    //         // $data_spb = $data->get_detail_data_spb($no_spb);
    //         // dd($data_spb);

    //         // $data_po = $data->get_detail_data_po($no_po);
            

    //         $harga_penyerahan = $row->harga_po * $row->qty_flow;
    //         $kode_item = substr($row->kode_item,0,1); 

    //         if ($kode_item == 9) {
    //             $tujuan_kirim = '2';
    //         }else {
    //             $tujuan_kirim = '1';
    //         }

    //         if ($row->address == null) {
    //             $alamat =  $row->city;
    //         }else {
    //             $alamat = $row->address;

    //         }

    //         $pajak = 0.11 * $row->harga_po * $row->qty_flow;
    //         $po = substr($row->no_po,0,3);
    //         if ($po == 'POJ') {
    //             $hitung_po = $row->harga_po * $row->qty_flow;
    //         }else {
    //             $hitung_po = 0.00;
    //         }
            

    //         return response()->json([
    //             // 'status' => true,
    //             'NoTransaksi' => $row->docp,
    //             'asalData' => 'S',
    //             'asuransi' => number_format(0, 2),
    //             'bruto' => $row->qty,
    //             'cif' => 0.00,
    //             'kodeJenisTpb' => 1,
    //             'freight' => number_format(0, 2),
    //             'hargaPenyerahan' => $harga_penyerahan,
    //             'idPengguna' => '-',
    //             'jabatanTtd' => 'KUASA DIREKSI',
    //             'jumlahKontainer' => 0,
    //             'jumlahKontainer' => '40',
    //             'kodeKantor' => '071300',
    //             'kodeTujuanPengiriman' => $tujuan_kirim,
    //             'kotaTtd' => 'PASURUAN',
    //             'namaTtd' => 'ADI SURYA',
    //             'netto' => $row->qty,
    //             'nik' => '017185901651000',
    //             'nomorAju' => 'waiting list ...',
    //             'seri' => 0,
    //             'tanggalAju' => date('Y-m-d', strtotime($row->DATE)),
    //             'tanggalTtd' => date('Y-m-d', strtotime($row->DATE)), 
    //             'volume' => 0.00,
    //             'biayaTambahan' => 0.00,
    //             'biayaPengurang' => 0.00,
    //             'vd' => 0.00,
    //             'uangMuka' => 0.00,
    //             'nilaiJasa' => 0.00,
    //             'entitas' => 
    //             [
    //                 array(
    //                     'alamatEntitas' => 'JL. DUSUN WONOKOYO RT.03 RW.01 003/001 WONOKOYO, BEJI, PASURUAN, JAWA TIMUR',
    //                     'kodeEntitas' => '3',
    //                     'kodeJenisIdentitas' => '5',
    //                     'namaEntitas' => 'BARAMUDA BAHARI',
    //                     'nibEntitas' => '8120002941581',
    //                     'nomorIdentitas' => '017185901651000',
    //                     'nomorIjinEntitas' => '3458/KM.4/2017',
    //                     'seriEntitas' => 1,
    //                     'tanggalIjinEntitas' => '2017-12-14'
    //                 ), array(
    //                     'alamatEntitas' => $alamat,
    //                     'kodeEntitas' => '7',
    //                     'kodeJenisApi' => '2',
    //                     'kodeJenisIdentitas' => '5',
    //                     'kodeStatus' => '5',
    //                     'namaEntitas' => $row->nama_supp,
    //                     'nibEntitas' => '-',
    //                     'nomorIdentitas' => '-',
    //                     'seriEntitas' => 2
    //                 ), array(
    //                     'alamatEntitas' => 'JL. DUSUN WONOKOYO RT.03 RW.01 003/001 WONOKOYO, BEJI, PASURUAN, JAWA TIMUR',
    //                     'kodeEntitas' => '9',
    //                     'kodeJenisApi' => '2',
    //                     'kodeJenisIdentitas' => '5',
    //                     'kodeStatus' => '5',
    //                     'namaEntitas' => 'BARAMUDA BAHARI',
    //                     'nibEntitas' => '017185901651000',
    //                     'nomorIdentitas' => '017185901651000',
    //                     'seriEntitas' => 3
    //                 )
    //                 ],
    //                 'dokumen' => 
    //                 [
    //                     array(
    //                     'kodeDokumen' => '999',
    //                     'nomorDokumen' => $row->penerimaan_barang,
    //                     'seriDokumen' => 1,
    //                     'tanggalDokumen' => date('Y-m-d', strtotime($row->date_do))
    //                     )
    //                 ],
    //                 'pengangkut' => [
    //                     array(
    //                     'namaPengangkut' => 'MOBIL TRUK',
    //                     'nomorPengangkut' => $row->nopol,
    //                     'seriPengangkut' => 1
    //                     )
    //                 ],
    //                 'kontainer' => [],
    //                 'kemasan' => [ 
    //                     array(
    //                     'jumlahKemasan' => '-',
    //                     'kodeJenisKemasan' => 'NE',
    //                     'merkKemasan' => '-',
    //                     'seriKemasan' => 1
    //                     )
    //                  ],
    //                  'pungutan' => [
    //                     array(
    //                     'kodeFasilitasTarif' => '3',
    //                     'kodeJenisPungutan' => 'PPN',
    //                     'nilaiPungutan' => $pajak
    //                     )
    //                  ],
    //                  'barang' => [
    //                     array(
    //                        'asuransi' => 0.00,
    //                        'bruto' => $row->qty,
    //                        'cif' => 0.00,
    //                        'diskon' => 0.00,
    //                        'hargaEkspor' => 0.00,
    //                        'hargaPenyerahan' => $harga_penyerahan,
    //                        'hargaSatuan' => $row->harga_po,
    //                        'isiPerKemasan' => 0,
    //                        'jumlahKemasan' => 0.00,
    //                        'jumlahRealisasi' => 0.00,
    //                        'jumlahSatuan' => $row->qty_flow,
    //                        'kodeBarang' => $row->kode_item,
    //                        'kodeDokumen' => '40',
    //                        'kodeJenisKemasan' => 'NE',
    //                        'kodeSatuanBarang' => $row->unit,
    //                        'merk' => '-',
    //                        'netto' => $row->qty,
    //                        'nilaiBarang' => $harga_penyerahan,
    //                        'posTarif' => '48191000',
    //                        'seriBarang' => 1,
    //                        'spesifikasiLain' => $row->spek_item,
    //                        'tipe' => 'TIPE BARANG',
    //                        'ukuran' => '-',
    //                        'uraian' => 'Box',
    //                        'volume' => 0.00,
    //                        'cifRupiah' => $harga_penyerahan,
    //                        'hargaPerolehan' => 0.00,
    //                        'kodeAsalBahanBaku' => '1',
    //                        'ndpbm' => 1,
    //                        'uangMuka' => 0.00,
    //                        'nilaiJasa' => $hitung_po,
    //                        'barangTarif' => [
    //                           array(
    //                              'kodeJenisTarif' => '1',
    //                              'jumlahSatuan' => $row->qty_flow,
    //                              'kodeFasilitasTarif' => '6',
    //                              'kodeSatuanBarang' => $row->unit,
    //                              'nilaiBayar' => $harga_penyerahan,
    //                              'nilaiFasilitas' => $pajak,
    //                              'nilaiSudahDilunasi' => 0.00,
    //                              'seriBarang' => 1,
    //                              'tarif' => 0.00,
    //                              'tarifFasilitas' => $pajak,
    //                              'kodeJenisPungutan' => 'PPN'
    //                             )
    //                        ]
    //                     )
    //                  ]
    //         ],200);
    //     }
    // }

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
