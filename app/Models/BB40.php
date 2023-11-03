<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BB40 extends Model
{
    use HasFactory;

    protected $table = 'bcmasuk';

    function bcbb40()  {
        return DB::connection('mysql')->select("SELECT
            a.docp,
            a.DATE,
            a.jnsp,
            a.oms as no_po,
            b.nodoc as penerimaan_barang,
            c.sub as kode_supp, 
            c.remark as ket_kebutuhan, 
            c.nosj as sj_supp, 
            c.nodoc as doc_bc,
            c.date as date_do,
            c.nopol,
            d.inv as kode_item, 
            d.loc, 
            d.spesifikasi, 
            d.qty, 
            d.no, 
            d.unit, 
            d.qty1, 
            d.cct, 
            d.qtygd as qty_flow, 
            d.etd, 
            d.unitgd, 
            d.qtytim,
            e.name as nama_supp,
            e.address,
            e.city,
            f.name as item_name,
            f.spesifikasi as spek_item,
            g.price as harga_po
        FROM
            docp a 
        JOIN docpd b on b.docp = a.docp
        JOIN spb c on c.spb = b.nodoc
        JOIN spbd d on d.spb = b.nodoc
        JOIN sub e on e.sub = c.sub
        JOIN inv f on f.inv = d.inv
        JOIN omd g on g.oms = a.oms
        WHERE a.jnsp = 'BC 4.0' and b.nodoc = 'SPB-2310-000138' ORDER BY date DESC");
    }
    // and b.nodoc = 'SPB-2310-000069'
    // SPB-2310-000138

    function get_data_bb40() {
        return DB::connection('mysql')->select("SELECT
            a.docp,
            a.`date` as date_bc,
            a.jnsp,
            a.oms,
            b.nodoc as spb,
            c.sub as kode_supp,
            c.remark as ket_spb,
            c.nopol,
            c.nosj,
            c.nodoc as nopen,
            c.date as date_spb,
            d.name as nama_supp,
            d.address,
            d.city
        FROM docp a
            JOIN docpd b on b.docp = a.docp
            JOIN spb c on c.spb = b.nodoc
            JOIN sub d ON d.sub = c.sub
        WHERE
            a.jnsp = 'BC 4.0'
            and b.nodoc = 'SPB-2310-000138'
        ORDER BY a.`date` DESC");
        
    }

    function get_detail_data_spb($spb) {
        return DB::connection('mysql')->select("SELECT
            g.inv,
            g.loc as gd_spb,
            g.remark as nama_item,
            g.spesifikasi,
            g.qty,
            g.qtygd as qty_flow,
            g.unitgd,
            g.cct,
            h.oms as po_from_spb,
            i.price as unit_price
        from spbd g
        JOIN spb h ON h.spb = g.spb
        JOIN omd i on i.oms = h.oms
        WHERE g.spb = '$spb'
        GROUP BY g.inv,	g.loc, g.remark, g.spesifikasi, g.qty, g.qtygd, g.unitgd, g.cct, h.oms, i.price");
    }

    function get_detail_data_po($po)  {
        return DB::connection('mysql')->select("SELECT
            e.oms as po,
            e.inv as kode_item,
            e.loc,
            f.name as nama_gud,
            e.remark as nama_item,
            e.spesifikasi,
            e.qty as qty_po,
            e.price,
            e.val,
            e.no
        from omd e
            JOIN loc f on f.loc = e.loc
        WHERE e.oms = '$po'");
    }
}
