<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class AnnexPoMst extends Model
{
    protected $table = 'annex_po_mst';
    //public $timestamps = false;
    protected $primaryKey = 'po_line_id';

    protected $fillable = ['po_no','amd_no','days','percentage','remarks','pay_cd','po_id','po_line_id','created_by','creation_date','last_updated_by','last_update_date','object_version_number'];
}
