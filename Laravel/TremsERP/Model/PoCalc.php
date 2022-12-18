<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class PoCalc extends Model
{
    protected $table = 'po_calc';
    //public $timestamps = false;
    protected $primaryKey = 'po_calc_id';

    protected $fillable = ['po_no','amd_no','sno','head','oper','unit','unit_rate','amount','based_on','cal_on','po_id','po_line_id','created_by','creation_date','last_updated_by','last_update_date','object_version_number'];
}
