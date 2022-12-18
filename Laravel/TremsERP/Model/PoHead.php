<?php

namespace App\Models\Material\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class PoHead extends Model
{
    protected $table = 'po_head';
    //public $timestamps = false;
    protected $primaryKey = 'po_id';

    protected $fillable = ['po_no','amd_no','po_dt','jo_po','unit_cd','valid_fr','valid_to','ven_cd','sch_no','amd_dt','amd_wef','cur_cd','pay_term','pay_type','del_term','pf_charge','ship_mode','desti','entry_dt','po_status','remarks','ins_cd','ins_amt','sale_tax','freight','tran_prd','transporter','po_head_others','reference','annexure','cij_no','prep_by','chck_by','appr_by','frez_by','vendor_address_type','cost_centre','sales_tax_code','sales_tax_type','credit_period','credit_condition','del_schd','saltax','pay_term_remarks','excise_remarks','po_source','ins_per','form','born_u_p','tds_payment_code','tds_per','quotation_ref','indent_ref','freight1','mode_del','dummy_po_no','dummy_amd_no','amd_prep_by','amd_chck_by','amd_appr_by','amd_frez_by','dummy_amd_flag','canc_flag','canc_date','temp_topermanent','temp_topermanentdt','old_pono','old_amdno','temp_blockflag','po_type','excise_cd','po_type2','cal_sceam_no','permit_no','permit_flag','po_unit_type','vari_fact','surchg_per','tot_per','service_tax_per','wc_tax_per','cess_per','packing_flag','hold_lv1','hold_lv2','hold_lv3','pf_per','head_excise_flag','chck_dt','appr_dt','frez_dt','hs_ed_cess','pf_type','po_value','tds_code','curr_rate','bud_code','bud_no','tot_disc','test_cert','buyer_name','our_bank','our_bank_add','our_bank_ac','gst_rate','gst_code','created_by','creation_date','last_updated_by','last_update_date','object_version_number','prev_po','annex_type','price_basis','freight_rem','price_basis_remark','tolerance_remark','quotation_no','disp_inst','curr_amt_word'];

    /**
     * This function use for get the sub-group details.
     * 
     * @return array
     */
    public function getUnitDetails(){
        return $this->hasOne('App\Models\Unit','code','unit_cd');
    }

    /**
     * This function use for get the vendor details.
     * 
     * @return array
     */
    public function getVenderDetails(){
        return $this->hasOne('App\VendorMaster','vendor_code','ven_cd');
    }

    /**
     * This function use for get the ship vendor details.
     * 
     * @return array
     */
    public function getShipVenderDetails(){
        return $this->hasOne('App\VendorMaster','vendor_code','ship_to');
    }

    /**
     * This function use for get the transpoter details.
     * 
     * @return array
     */
    public function getTranspoter(){
        return $this->hasOne('App\VendorMaster','vendor_code','transporter');
    }
    

    /**
     * This function use for get the vendor address details.
     * 
     * @return array
     */
    public function getVenderAddDetails(){
        return $this->hasOne('App\VendorRegdAddress','vendor_code','ven_cd');
    }

    /**
     * This function use for get the ship vendor address details.
     * 
     * @return array
     */
    public function getShipVenderAddDetails(){
        return $this->hasOne('App\VendorRegdAddress','vendor_code','ship_to');
    }

    /**
     * This function use for get the location details.
     * 
     * @return array
     */
    public function getLocationDetails(){
        return $this->hasOne('App\Location','locat_code','cost_centre');
    }

    /**
     * This function use for get the documents details.
     * 
     * @return array
     */
    public function getDocDetails(){
        return $this->hasMany('App\Models\Material\DocAttachRefDtlModel','doc_id_ref','po_id')->where('ref_doc_type', 'Purchase Order');
    }

    /**
     * This function use for get the annex_po details.
     * 
     * @return array
     */
    public function getAnnexPoMst(){
        return $this->hasMany('App\Models\Material\PurchaseOrder\AnnexPoMst','po_id','po_id');
    }

    /**
     * This function use for get the MultiPayTerm details.
     * 
     * @return array
     */
    public function getMultiPayTerm(){
        return $this->hasMany('App\Models\Material\PurchaseOrder\MultiPayTerm','po_id','po_id');
    }

    /**
     * This function use for get the PoCalc details.
     * 
     * @return array
     */
    public function getPoCalc(){
        return $this->hasMany('App\Models\Material\PurchaseOrder\PoCalc','po_id','po_id');
    }

    /**
     * This function use for get the PoDetail details.
     * 
     * @return array
     */
    public function getPoDetail(){
        return $this->hasMany('App\Models\Material\PurchaseOrder\PoDetail','po_id','po_id')->with('getScheduleoDetail');
    }

    /**
     * This function use for get the schedule details.
     * 
     * @return array
     */
   

}
