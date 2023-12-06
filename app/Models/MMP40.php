<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MMP40 extends Model
{
    use HasFactory;

    function get_data_header() {
        return DB::connection('sqlsrv')->select("SELECT
            ath.id,
            ath.Ucode_tpb_header, 
            ath.No_BC as no_trans,
            ath.ASURANSI,
            SUBSTRING(ath.No_BC,3,2) AS kode_bc, 
            ath.TANGGAL_AJU,
            ath.NOMOR_AJU,
            ath.KODE_KANTOR,
            ath.KODE_JENIS_TPB,
            ath.CIF,
            ath.KODE_TUJUAN_PENGIRIMAN,
            ath.ID_PENGUSAHA as npwp_pengusaha,
            ath.NAMA_PENGUSAHA,
            ath.ALAMAT_PENGUSAHA,
            ath.KODE_DOKUMEN_PABEAN,
            ath.NOMOR_IJIN_TPB,
            ath.ID_PENGIRIM as npwp_pengirim,
            ath.NAMA_PENGIRIM,
            ath.ALAMAT_PENGIRIM,
            ath.NAMA_PENGANGKUT,
            ath.NOMOR_POLISI,
            ath.HARGA_PENYERAHAN,
            ath.VOLUME,
            ath.BRUTO,
            ath.NETTO,
            ath.NAMA_TTD,
            ath.JABATAN_TTD,
            ath.KOTA_TTD,
            ath.TANGGAL_TTD,
            ath.FREIGHT,
            ath.JUMLAH_KONTAINER,
            ath.BIAYA_TAMBAHAN,
            atk.JUMLAH_KEMASAN,
            atk.KODE_JENIS_KEMASAN,
            tmk.Nama_Kemasan
        from
            ACTS_tpb_header ath
        join ACTS_tpb_kemasan atk on atk.UCode_tpb_header = ath.Ucode_tpb_header
        join tb_m_Kemasan tmk on tmk.UCode_Kemasan = atk.ucode_kemasan
        WHERE SUBSTRING(ath.No_BC,3,2) = 40 and ath.NOMOR_AJU = '07134000165220231101005201'");
    }

    function get_data_dokumen($Ucode) {
        return DB::connection('sqlsrv')->select("SELECT
        no_urut,
        KODE_JENIS_DOKUMEN,
        NOMOR_DOKUMEN,
        TANGGAL_DOKUMEN,
        TIPE_DOKUMEN
    FROM
        ACTS_tpb_dokumen atd
    WHERE
        UCode_tpb_header = '$Ucode'");
    }

    function get_data_kemasan($Ucode) {
        return DB::connection('sqlsrv')->select("SELECT
        no_urut,
        JUMLAH_KEMASAN,
        KODE_JENIS_KEMASAN,
        MERK_KEMASAN
    from
        ACTS_tpb_kemasan atk
    WHERE
        UCode_tpb_header = '$Ucode'");
    }

    function get_data_barang($Ucode) {
        return DB::connection('sqlsrv')->select("select 
            atb.no_urut,
            atb.ASURANSI,
            atb.CIF,
            atb.CIF_RUPIAH,
            atb.DISKON,
            atb.FOB,
            atb.HARGA_INVOICE,
            atb.HARGA_PENYERAHAN,
            atb.HARGA_SATUAN,
            atb.JUMLAH_SATUAN,
            atb.KATEGORI_BARANG,
            atb.KODE_BARANG,
            atb.KODE_SATUAN,
            atb.NETTO,
            atb.POS_TARIF,
            atb.TIPE,
            atb.URAIAN,
            atb.VOLUME,
            atb.ID_HEADER,
            atb.no_bukti_terima,
            atb.tgl_bukti_terima,
            atb.Ket,
            atb.JUMLAH_KEMASAN,
            atb.NILAI_PABEAN,
            SPESIFIKASI_LAIN
            from ACTS_tpb_barang atb WHERE UCode_tpb_header = '$Ucode'");
    }
}
