<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class PoDetail extends Model
{
    protected $table = 'po_detail';
    //public $timestamps = false;
    protected $primaryKey = 'po_line_id';

    protected $fillable = ['po_head_po_no','po_head_amd_no','pios_pois_no','pios_ven_cd','pios_item_cd','pios_rev_no','item_type','uom','qty','ed_date','pros_rate','material_rate','discount_percent','discount_amt','excise_percent','excise_amt','tariff_no','po_detail_others','srv_qty','rate_uom','proc_seq','proc_seq_revno','proc_cd','newn','remarks','closed','sales_tax_amt','sales_tax_per','sur_charge_per','sur_charge_amt','landed_cost','pf_charge','po_head_dumm_amd_no','stax_code','stax_type','subsidized_quantity','subsidized_price','rec_tolength','rec_totwidth','goods_value','detailed_uom','rec_height','rec_weight','rec_diam','str_qty','quot_no','sugar_per','asset_category','tooling_advance','subsidized_duration','excise_flag','sch_qty','ass_rate','repair_proc','item_specf','tot_disc_amt','rej_allow_per','rej_input_rate','gst_percent','gst_amt','po_hsn_code','cgst_per','cgst_amt','sgst_per','sgst_amt','gst_code','po_id','created_by','creation_date','last_updated_by','last_update_date','object_version_number','conv_uom','conv_qty','manf_cd','manf_name','make_by','uc_amount','unload_address','tolerance_per'];


    public function getScheduleoDetail(){
        return $this->hasMany('App\Models\Material\PurchaseOrder\PoDelvSchd','po_dtl_line_id','po_line_id');
    }
}
