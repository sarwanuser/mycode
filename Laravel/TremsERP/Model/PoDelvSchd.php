<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class PoDelvSchd extends Model
{
    protected $table = 'po_delv_schd';
    //public $timestamps = false;
    protected $primaryKey = 'po_delv_schd';

    protected $fillable = ['po_no','amd_no','po_hd_id','po_dtl_line_id','schd_date','schd_qty','item_cd'];

    public $timestamps = false;


}
