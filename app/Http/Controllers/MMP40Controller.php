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
                    'seriDokumen' => $doc->no_urut,
                    'tanggalDokumen' => $doc->TANGGAL_DOKUMEN
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
                    'jumlahKemasan' => $kemasan->JUMLAH_KEMASAN,
                    'kodeJenisKemasan' => $kemasan->KODE_JENIS_KEMASAN,
                    'merkKemasan' => $merk,
                    'seriKemasan' => $kemasan->no_urut
                );
            }

            $data_barang = $model->get_data_barang($ucode);
            $arr_barang = array();
            foreach ($data_barang as $barang) {
                $arr_barang[] = array(
                    'asuransi' => $barang->ASURANSI,
                    'bruto' => '-',
                );
            }

            $freight = $header->FREIGHT;
            if ($freight == null) {
                $r_freight = 0;
            } else {
                $r_freight = floatval($freight);
            }

            $cif = $header->CIF;
            if ($cif == null) {
                $r_CIF = 0;
            } else {
                $r_CIF = $cif;
            }

            $kontainer = $header->JUMLAH_KONTAINER;
            if ($kontainer == null) {
                $r_kontainer = 0;
            } else {
                $r_kontainer = floatval($kontainer);
            }


            $arr_entitas = [
                array(
                    'alamatEntitas' => $header->ALAMAT_PENGUSAHA,
                    'kodeEntitas' => '3',
                    'kodeJenisIdentitas' => '5',
                    'namaEntitas' => $header->NAMA_PENGUSAHA,
                    'nibEntitas' => $header->npwp_pengusaha,
                    'nomorIjinEntitas' => $header->NOMOR_IJIN_TPB,
                    'seriEntitas' => 1,
                    'tanggalIjinEntitas' => 'tanya mas arik'
                ), array(
                    'alamatEntitas' => $header->ALAMAT_PENGIRIM,
                    'kodeEntitas' => '7',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => $header->NAMA_PENGIRIM,
                    'nibEntitas' => $header->npwp_pengirim,
                    'nomorIdentitas' => '-',
                    'seriEntitas' => 2
                ), array(
                    'alamatEntitas' => $header->ALAMAT_PENGUSAHA,
                    'kodeEntitas' => '9',
                    'kodeJenisApi' => '2',
                    'kodeJenisIdentitas' => '5',
                    'kodeStatus' => '5',
                    'namaEntitas' => $header->NAMA_PENGUSAHA,
                    'nibEntitas' => $header->npwp_pengusaha,
                    'nomorIdentitas' => $header->NOMOR_IJIN_TPB,
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
                'nilaiPungutan' => 'waiting list'
            );

            $arr_header = array(
                'NoTransasksi' => $header->no_trans,
                'asalData' => 'S',
                'asuransi' => $header->ASURANSI,
                'bruto' => floatval($header->BRUTO),
                'cif' => $r_CIF,
                'kodeJenisTpb' => $header->KODE_JENIS_TPB,
                'freight' => $r_freight,
                'hargaPenyerahan' => floatval($header->HARGA_PENYERAHAN),
                'idPengguna' => '-',
                'jabatanTtd' => $header->JABATAN_TTD,
                'jumlahKontainer' => $r_kontainer,
                'kodeDokumen' => $header->kode_bc,
                'kodeKantor' => $header->KODE_KANTOR,
                'kodeTujuanPengiriman' => $header->KODE_TUJUAN_PENGIRIMAN,
                'kotaTtd' => $header->KOTA_TTD,
                'namaTtd' => $header->NAMA_TTD,
                'netto' => floatval($header->NETTO),
                'nik' => $header->npwp_pengusaha,
                'nomorAju' => 'custom acts',
                'seri' => 0,
                'tanggalAju' => date('Y-m-d', strtotime($header->TANGGAL_AJU)),
                'tanggalTtd' => date('Y-m-d', strtotime($header->TANGGAL_AJU)),
                'volume' => floatval($header->VOLUME),
                'biayaTambahan' => floatval($header->BIAYA_TAMBAHAN),
                'biayaPengurang' => 0,
                'vd' => 0,
                'uangMuka' => 0,
                'nilaiJasa' => 0,
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
