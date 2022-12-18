<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class MultiPayTerm extends Model
{
    protected $table = 'multi_pay_term'; 
    //public $timestamps = false;
    protected $primaryKey = 'multi_pay_term_id';

    protected $fillable = ['po_no','amd_no','days','percentage','remarks','pay_cd','po_id','po_line_id','created_by','creation_date','last_updated_by','last_update_date','object_version_number'];
}
