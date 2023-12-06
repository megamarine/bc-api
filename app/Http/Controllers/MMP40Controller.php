<?php

namespace App\Http\Controllers;

use App\Models\MMP40;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PhpParser\Node\Stmt\Echo_;

class MMP40Controller extends Controller
{
    function index()
    {
        $model = new MMP40();
        $data = $model->get_data_header();

        $arr_header = array();
        foreach ($data as $header) {
            $ucode = $header->Ucode_tpb_header;

            $data_doc = $model->get_data_dokumen($ucode);
            $arr_doc = array();
            foreach ($data_doc as $doc) {
                $arr_doc[] = array(
                    'kodeDokumen' => $doc->KODE_JENIS_DOKUMEN,
                    'nomorDokumen' => $doc->NOMOR_DOKUMEN,
                    'seriDokumen' => intval($doc->no_urut),
                    'tanggalDokumen' => date('Y-m-d', strtotime($doc->TANGGAL_DOKUMEN))
                );
            }

            $data_kemasan = $model->get_data_kemasan($ucode);
            $arr_kemasan = array();
            foreach ($data_kemasan as $kemasan) {
                if ($kemasan->MERK_KEMASAN == null) {
                    $merk = '-';
                } else {
                    $merk = $kemasan->MERK_KEMASAN;
                }
                $arr_kemasan[] = array(
                    'jumlahKemasan' => intval($kemasan->JUMLAH_KEMASAN),
                    'kodeJenisKemasan' => $kemasan->KODE_JENIS_KEMASAN,
                    'merkKemasan' => $merk,
                    'seriKemasan' => intval($kemasan->no_urut),
                    
                );

                $kode_kemas = $kemasan->KODE_JENIS_KEMASAN;
            }

            $data_barang = $model->get_data_barang($ucode);
            $arr_barang = array();
            foreach ($data_barang as $barang) {
                if ($barang->JUMLAH_KEMASAN == null) {
                    $jml_kemasan = 0;
                }else {
                    $jml_kemasan = $barang->JUMLAH_KEMASAN;
                }
                if ($barang->SPESIFIKASI_LAIN == null) {
                    $spek_lain = '-';
                }else {
                    $spek_lain = $barang->SPESIFIKASI_LAIN;
                }
                $arr_barang[] = array(
                    'asuransi' => floatval($barang->ASURANSI),
                    'bruto' => 0.0000,
                    'cif' => floatval($barang->CIF),
                    'diskon' => floatval($barang->DISKON),
                    'hargaEkspor' => 0.00,
                    'hargaPenyerahan' => floatval($barang->HARGA_PENYERAHAN),
                    'hargaSatuan' => floatval($barang->HARGA_SATUAN),
                    'isiPerKemasan' => 0,
                    'jumlahKemasan' => intval($jml_kemasan),
                    'jumlahRealisasi' => 0.00,
                    'jumlahSatuan' => intval($barang->JUMLAH_SATUAN),
                    'kodeBarang' => $barang->KODE_BARANG,
                    'kodeDokumen' => '40',
                    'kodeJenisKemasan' => $kode_kemas,
                    'kodeSatuanBarang' => $barang->KODE_SATUAN,
                    'merk' => '-',
                    'netto' => floatval($barang->NETTO),
                    'nilaiBarang' => floatval($barang->NILAI_PABEAN),
                    'posTarif' => empty($barang->POS_TARIF)?"":$barang->POS_TARIF,
                    'seriBarang' => intval($barang->no_urut),
                    'spesifikasiLain' => $spek_lain,
                    'tipe' => 'TIPE BARANG',
                    'ukuran' => '-',
                    'uraian' => $barang->URAIAN,
                    'volume' => floatval($barang->VOLUME),
                    'cifRupiah' => floatval($barang->CIF_RUPIAH),
                    'hargaPerolehan' => 0.00,
                    'kodeAsalBahanBaku' => '1',
                    'ndpbm' => 0.00,
                    'uangMuka' => 0.00,
                    'nilaiJasa' => 0,
                    'barangTarif' => [
                        array(
                            'kodeJenisTarif' => '1',
                            'jumlahSatuan' => floatval($barang->JUMLAH_SATUAN),
                            'kodeFasilitasTarif' => '3',
                            'kodeSatuanBarang' => $barang->KODE_SATUAN,
                            'nilaiBayar' => floatval(0),
                            'nilaiFasilitas' => floatval(0),
                            'nilaiSudahDilunasi' => 0.00,
                            'seriBarang' => intval($barang->no_urut),
                            'tarif' => floatval($barang->HARGA_SATUAN),
                            'tarifFasilitas' => floatval(0),
                            'kodeJenisPungutan' => "PPN"
                        ),
                    ]
                );
            }

            $freight = $header->FREIGHT;
            if ($freight == null) {
                $r_freight = 0.00;
            } else {
                $r_freight = floatval($freight);
            }

            $cif = $header->CIF;
            if ($cif == null) {
                $r_CIF = 0.00;
            } else {
                $r_CIF = $cif;
            }

            $kontainer = $header->JUMLAH_KONTAINER;
            if ($kontainer == null) {
                $r_kontainer = 0;
            } else {
                $r_kontainer = $kontainer;
            }


            $arr_entitas = [
                array(
                    'alamatEntitas' => $header->ALAMAT_PENGUSAHA,
                    'kodeEntitas' => '3',
                    'kodeJenisIdentitas' => '5',
                    'namaEntitas' => $header->NAMA_PENGUSAHA,
                    'nibEntitas' => '8120106862498',
                    'nomorIdentitas' => $header->npwp_pengusaha,
                    'nomorIjinEntitas' => $header->NOMOR_IJIN_TPB,
                    'seriEntitas' => 1,
                    'tanggalIjinEntitas' => '2020-09-10'
                ), 
                array(
                    'alamatEntitas' => $header->ALAMAT_PENGUSAHA,
                    'kodeEntitas' => '7',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => $header->NAMA_PENGUSAHA,
                    'nibEntitas' => '8120106862498',
                    'nomorIdentitas' => $header->npwp_pengusaha,
                    'seriEntitas' => 2
                ),array(
                    'alamatEntitas' => $header->ALAMAT_PENGIRIM,
                    'kodeEntitas' => '9',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => $header->NAMA_PENGIRIM,
                    'nibEntitas' => $header->npwp_pengirim,
                    'nomorIdentitas' => $header->npwp_pengirim,
                    'seriEntitas' => 3
                )
            ];

            $arr_pengangkut[] = array(
                'namaPengangkut' => $header->NAMA_PENGANGKUT,
                'nomorPengangkut' => $header->NOMOR_POLISI,
                'seriPengangkut' => 1
            );

            $arr_pungutan [] = array(
                'kodeFasilitasTarif' => "3",
                'kodeJenisPungutan' => 'PPN',
                'nilaiPungutan' => floatval(0)
            );

            $arr_header = array(
                // 'NoTransasksi' => $header->no_trans,
                "isFinal" => false,
                'asalData' => 'S',
                'asuransi' => 0.00,
                'bruto' => floatval($header->BRUTO),
                'cif' => $r_CIF,
                'kodeJenisTpb' => $header->KODE_JENIS_TPB,
                'freight' => $r_freight,
                'hargaPenyerahan' => floatval($header->HARGA_PENYERAHAN),
                'idPengguna' => $header->npwp_pengusaha,
                'jabatanTtd' => $header->JABATAN_TTD,
                'jumlahKontainer' => $r_kontainer,
                'kodeDokumen' => $header->kode_bc,
                'kodeKantor' => $header->KODE_KANTOR,
                'kodeTujuanPengiriman' => $header->KODE_TUJUAN_PENGIRIMAN,
                'kotaTtd' => $header->KOTA_TTD,
                'namaTtd' => $header->NAMA_TTD,
                'netto' => floatval($header->NETTO),
                'nik' => '05.005643',
                'nomorAju' => '00004001468020231123000338',
                'seri' => 0,
                'tanggalAju' => date('Y-m-d', strtotime($header->TANGGAL_AJU)),
                'tanggalTtd' => date('Y-m-d', strtotime($header->TANGGAL_AJU)),
                'volume' => floatval($header->VOLUME),
                'biayaTambahan' => floatval($header->BIAYA_TAMBAHAN),
                'biayaPengurang' => 0.00,
                'vd' => 0.00,
                'uangMuka' => 0.00,
                'nilaiJasa' => 0.00,
                'entitas' => $arr_entitas,
                'dokumen' => $arr_doc,
                'pengangkut' => $arr_pengangkut,
                'kontainer' => [],
                'kemasan' => $arr_kemasan,
                'pungutan' => $arr_pungutan,
                'barang' => $arr_barang

            );
        }

        return response()->json($arr_header, 200);
    }
}
