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
            g.price as harga_po
        FROM
            docp a 
        JOIN docpd b on b.docp = a.docp
        JOIN spb c on c.spb = b.nodoc
        JOIN spbd d on d.spb = b.nodoc
        JOIN sub e on e.sub = c.sub
        JOIN inv f on f.inv = d.inv
        JOIN omd g on g.oms = a.oms
        WHERE a.jnsp = 'BC 4.0' ORDER BY date DESC");
    }
    // and b.nodoc = 'SPB-2310-000069'
}
