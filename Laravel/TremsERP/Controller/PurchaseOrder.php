<?php

namespace App\Http\Controllers\Material\Transactions\Purchase;  
use App\Http\Controllers\Controller;
use DB;
use App\ENG\PartMaster\PartMasterModel;
use App\Models\ENG\Vendor;
use App\Models\ENG\PaymentTermsModel;
use App\ENG\PartMaster\ItemUnitStoreLoc;
use App\VendorMaster;
use App\SecControlValue;
use App\GroupMst;
use App\SubGrpMst;
use App\HsnMaster;
use App\Unit;
use App\UnitOfMeasurement;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use Validator;
use App\Models\Material\PurchaseOrder\PoHead;
use App\Models\Material\PurchaseOrder\PoDetail;
use App\Models\Material\PurchaseOrder\PoCalc;
use App\Models\Material\PurchaseOrder\MultiPayTerm;
use App\Models\Material\PurchaseOrder\AnnexPoMst;
use App\Models\Material\PurchaseOrder\PoDelvSchd;
use App\Models\Material\DocAttachRefDtlModel;
use PDF;
use function GuzzleHttp\json_decode;

class PurchaseOrder extends Controller
{


    /**
	 * This function use for match data
	 *
	 * @return purchase page
	 */
	public function getTableData(Request $request){
        //dd($request->all());
        $token = @$_REQUEST['_ts'];
		if (session()->exists($token)) {
			$user = session()->get($token);
            
			// Call Procedure here
			$search = array($request->search_field => $request->sea_field_val, 'po_no' => $request->po_no, 'po_type' => $request->po_type, 'ven_cd' => $request->vendor_code, 'po_dt' => $request->po_date, 'po_type2' => $request->po_type2, 'po_unit_type' => $request->type);
            $search=array_filter($search, function($value) { return !is_null($value) && $value !== ''; });
			session()->put($token.'.search', $search);
			$db_data = PoHead::where($search)->where('unit_cd',$user['unitcode'])->orderBy($request->sorting, $request->order)->with('getUnitDetails')->paginate($request->per_page);
			if($request->order == 'ASC'){
                $sorting = 'DESC';
                $sorting_class = 'sorting_desc';
            }else{
                $sorting = 'ASC';
                $sorting_class = 'sorting_asc';
            }

			$count = $db_data->count();
			$number_of_page = $db_data->lastPage();
			$currentPage = $db_data->currentPage();
            $total_items = $db_data->total();
            $displayfrom = ($currentPage-1)*$request->per_page+1;
            $displayto = ($currentPage-1)*$request->per_page+$count;
			$pagination = '';
			//display the pagination
			if($number_of_page > 1){
                $prv = $currentPage-1;
                $next = $currentPage+1;
                if($next > $number_of_page){
                    $next = $number_of_page;
                }
                $pagination .='<div class="pGroup">
                    <div class="pFirst pButton first-button" onclick="return getTableDataByPage(1);">
                    <span></span>
                    </div>
                    <div class="pPrev pButton prev-button" onclick="return getTableDataByPage('.$prv.');">
                    <span></span>
                    </div>
                </div>
                <div class="btnseparator"></div>
                <div class="pGroup">
					<span class="pcontrol">Page <input name="page" type="text" value="'.$currentPage.'" size="4" id="crud_page" class="crud_page">
							of <span id="last-page-number" class="last-page-number">'.$number_of_page.'</span></span>
                </div>
                <div class="btnseparator"></div>     
                <div class="pGroup">
							<div class="pNext pButton next-button" onclick="return getTableDataByPage('.$next.');">
							<span></span>
							</div>
							<div class="pLast pButton last-button" onclick="return getTableDataByPage('.$number_of_page.');">
							<span></span>
							</div>
						</div>';  
			}
			
			//return response()->json(['status' => 0, 'data' => $db_data, 'pagination' => $pagination, 'msg' => 'Data Not Found!']);
			if($count == 0){
                $records = $this->get_columns();

                return response()->json(['displayfrom' => $displayfrom, 'displayto' => $displayto, 'total_items' => $total_items, 'status' => 1, 'data' => $records, 'pagination' => $pagination, 'msg' => 'Data not found!']);
				// return response()->json(['status' => 0, 'data' => $records, 'pagination' => $pagination, 'msg' => 'Data Not Found!']);
			}else{
                $records = '';
                $records .= '<thead>
                <tr class="hDiv">
                    <th>                     
                    </th>
                    <th>';
                        if($request->sorting == 'po_no'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_no" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="po_no" order="'.$sorting.'">';
                        }
                        $records .= 'PO NO.					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'amd_no'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="amd_no" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="amd_no" order="'.$sorting.'">';
                        }
                        $records .= 'Amendment No.					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'unit_cd'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="unit_cd" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="unit_cd" order="'.$sorting.'">';
                        }
                        $records .= 'Unit Code					
                        </div>
                    </th>
                    <th>';
                        
                        $records .= 'Unit Name
                    </th>
                    <th>';
                        if($request->sorting == 'jo_po'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="jo_po" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="jo_po" order="'.$sorting.'">';
                        }
                        $records .= 'JO/PO Type					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_type'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_type" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="po_type" order="'.$sorting.'">';
                        }
                        $records .= 'PO Type          
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_type2'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_type2" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting" field="po_type2" order="'.$sorting.'">';
                        }
                        $records .= 'PO Type2					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_unit_type'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_unit_type" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="po_unit_type" order="'.$sorting.'">';
                        }
                        $records .= 'Type					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'ven_cd'){
                            $records .= '<div class="text-left field-sorting1 '.$sorting_class.'" field="ven_cd" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="ven_cd" order="'.$sorting.'">';
                        }
                        $records .= 'Vendor Code					
                        </div>
                    </th>
                    <th>';
                        $records .= 'Vendor Name					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_status'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_status" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="po_status" order="'.$sorting.'">';
                        }
                        $records .= 'PO Status					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_dt'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_dt" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="po_dt" order="'.$sorting.'">';
                        }
                        $records .= 'PO Date					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'valid_fr'){
                            $records .= '<div class="text-left field-sorting1 '.$sorting_class.'" field="valid_fr" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="valid_fr" order="'.$sorting.'">';
                        }
                        $records .= 'Valid From				
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'valid_to'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="valid_to" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="valid_to" order="'.$sorting.'">';
                        }
                        $records .= 'Valid To					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'amd_dt'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="amd_dt" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="amd_dt" order="'.$sorting.'">';
                        }
                        $records .= 'Amendment Date					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'amd_wef'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="amd_wef" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="amd_wef" order="'.$sorting.'">';
                        }
                        $records .= 'With Effect Of					
                        </div>
                    </th>
                    <th>';
                        $records .= 'Location					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'del_term'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="del_term" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="del_term" order="'.$sorting.'">';
                        }
                        $records .= 'Delivery Term					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'pf_type'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="pf_type" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="pf_type" order="'.$sorting.'">';
                        }
                        $records .= 'P/F Type					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'ins_per'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="ins_per" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="ins_per" order="'.$sorting.'">';
                        }
                        $records .= 'AMT% PF Charge					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'ins_cd'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="ins_cd" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="ins_cd" order="'.$sorting.'">';
                        }
                        $records .= 'Ins Code					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'ins_amt'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="ins_amt" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="ins_amt" order="'.$sorting.'">';
                        }
                        $records .= 'Ins AMT					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_head_others'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_head_others" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="po_head_others" order="'.$sorting.'">';
                        }
                        $records .= 'Others					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'ship_mode'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="ship_mode" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="ship_mode" order="'.$sorting.'">';
                        }
                        $records .= 'Mode Of Dispatch					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'freight'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="freight" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="freight" order="'.$sorting.'">';
                        }
                        $records .= 'Freight By					
                        </div>
                    </th>
                    <th>';
                        $records .= 'AMT % Freight
                    </th>
                    <th>';
                        if($request->sorting == 'curr_rate'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="curr_rate" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="curr_rate" order="'.$sorting.'">';
                        }
                        $records .= 'Currency					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'our_bank'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="our_bank" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="our_bank" order="'.$sorting.'">';
                        }
                        $records .= 'Our Bankers					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'our_bank_add'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="our_bank_add" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="our_bank_add" order="'.$sorting.'">';
                        }
                        $records .= 'Our Bankers Address
					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'our_bank_ac'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="our_bank_ac" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="our_bank_ac" order="'.$sorting.'">';
                        }
                        $records .= 'Our Bank A/C.					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'desti'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="desti" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="desti" order="'.$sorting.'">';
                        }
                        $records .= 'Destination					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'transporter'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="transporter" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="transporter" order="'.$sorting.'">';
                        }
                        $records .= 'Transporter					
                        </div>
                    </th>
                    <th>';
                        $records .= 'Transporter Name					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'disp_inst'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="disp_inst" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="disp_inst" order="'.$sorting.'">';
                        }
                        $records .= 'Dispatch Instruction					
                        </div>
                    </th>
                    <th>';
                        $records .= 'Based On					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'jo_po'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="jo_po" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="jo_po" order="'.$sorting.'">';
                        }
                        $records .= 'Project/ Job No.					
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'tot_per'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="tot_per" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="tot_per" order="'.$sorting.'">';
                        }
                        $records .= 'Tolerance+(%) (For Receiving)				
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'tolerance_remark'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="tolerance_remark" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="tolerance_remark" order="'.$sorting.'">';
                        }
                        $records .= 'Remarks				
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'po_value'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="po_value" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="po_value" order="'.$sorting.'">';
                        }
                        $records .= 'PO Value				
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'price_basis'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="price_basis" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="price_basis" order="'.$sorting.'">';
                        }
                        $records .= 'Price Basis				
                        </div>
                    </th>
                    <th>';
                        if($request->sorting == 'freight_rem'){
                            $records .= '<div class="text-left field-sorting '.$sorting_class.'" field="freight_rem" order="'.$sorting.'">';
                        }else{
                            $records .= '<div class="text-left field-sorting " field="freight_rem" order="'.$sorting.'">';
                        }
                        $records .= 'Freight Remarks			
                        </div>
                    </th>
                </tr>
                </thead>
                    <tbody>';
                foreach($db_data as $row){
                    $records .= '<tr class="">
                    <td style="text-align:center;"><input type="checkbox" class="item_check_box" rowid="'.$row['po_no'].'"/></td>';
                    if($row['appr_by'] != null){
                        $records .= '<td><a href="purchaseorder/'.$row['po_id'].'?_ts='.@$_REQUEST['_ts'].'">'.$row['po_no'].'</a><a style="cursor:pointer;" href="/purchase-tracking/print-reportdata/'.base64_encode($row['po_no']).'/'.$row['amd_no'].'/'.$row['po_id'].'/'.base64_encode($row['ven_cd']).'/P/N?_ts='.@$_REQUEST['_ts'].'&_per=M" target="_blank" title="View Purchase Tracking"><img src="/assets/img/reportimg.png" width="14px;" style="margin-left:6px;"></a></td>';
                    }else{
                        $records .= '<td><a href="purchaseorder/'.$row['po_id'].'?_ts='.@$_REQUEST['_ts'].'">'.$row['po_no'].'</a></td>';
                    }
                    
                    $records .= '<td>'.$row['amd_no'].'</td>
                    <td>'.$row['unit_cd'].'</td>
                    <td>'.$row->getUnitDetails->name.'</td>
                    <td>'.$row['jo_po'].'</td>
                    <td>'.getSecContDes('PO_TYPE',$row['po_type']).'</td>
                    <td>'.$row['po_type2'].'</td>
                    <td>'.getSecContDes('PO_UNIT_TYPE', $row['po_unit_type']).'</td>
                    <td>'.$row['ven_cd'].'</td>
                    <td>'.getVendorDescription($row['ven_cd'])->name.'</td>
                    <td>'.$row['po_status'].'</td>
                    <td>';
                    if($row['po_dt'] != ''){
                        $records .= date('d-m-Y', strtotime($row['po_dt']));
                    }
                    $records .= '</td>
                    <td>';
                    if($row['valid_fr'] != ''){
                        $records .= date('d-m-Y', strtotime($row['valid_fr']));
                    }
                    $records .= '</td>
                    <td>';
                    if($row['valid_to'] != ''){
                        $records .= date('d-m-Y', strtotime($row['valid_to']));
                    }
                    $records .= '</td>
                    <td>';
                    if($row['amd_dt'] != ''){
                        $records .= date('d-m-Y', strtotime($row['amd_dt']));
                    }
                    $records .= '</td>
                    <td>'.$row['amd_wef'].'</td>
                    <td>--</td>
                    <td>'.$row['del_term'].'</td>
                    <td>'.$row['pf_type'].'</td>
                    <td>'.$row['ins_per'].'</td>
                    <td>'.$row['ins_cd'].'</td>
                    <td>'.$row['ins_amt'].'</td>
                    <td>'.$row['po_head_others'].'</td>
                    <td>'.$row['ship_mode'].'</td>
                    <td>'.$row['freight'].'</td>
                    <td>--</td>
                    <td>'.$row['curr_rate'].'</td>
                    <td>'.$row['our_bank'].'</td>
                    <td>'.$row['our_bank_add'].'</td>
                    <td>'.$row['our_bank_ac'].'</td>
                    <td>'.$row['desti'].'</td>
                    <td>'.$row['transporter'].'</td>
                    <td>'.$row['transporter'].'</td>
                    <td>'.$row['disp_inst'].'</td>
                    <td>--</td>
                    <td>'.$row['jo_po'].'</td>
                    <td>'.$row['tot_per'].'</td>
                    <td>'.$row['tolerance_remark'].'</td>
                    <td>'.$row['po_value'].'</td>
                    <td>'.$row['price_basis'].'</td>
                    <td>'.$row['freight_rem'].'</td>';
                }
                $records .= '</tbody>';
            }
				
			return response()->json(['displayfrom' => $displayfrom, 'displayto' => $displayto, 'total_items' => $total_items, 'status' => 1, 'data' => $records, 'pagination' => $pagination, 'msg' => 'This is matched data!']);
		}else{
			return view('login');
		}
		
    }
	
	/**
	 * This function use for match data
	 *
	 * @return purchase page
	 */
	public function getColumns(Request $request){
        $token = @$_REQUEST['_ts'];
		if (session()->exists($token)) {
			if($request->sessionrest == 1){
                session()->forget($token.'.search');
            }
			
			$records = '<thead>
			<tr class="hDiv">
                <th>
                    <div class="text-left field-sorting">
                    PO NO.			
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Amendment No.		
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Unit Code	
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Unit Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    JO/PO Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Type2
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Vendor Code
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Vendor Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Status
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Valid From
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Valid To
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Amendment Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    With Effect Of
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Location
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Location Description
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Delivery Term
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    P/F Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    AMT% PF Charge
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Ins Code
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Ins AMT
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Others
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Mode Of Dispatch
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Freight By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    AMT % Freight
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Currency
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bankers
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bankers Address
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bank A/C.
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Destination
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Transporter
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Transporter Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Dispatch Instruction
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Based On
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Project/ Job No.
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Tolerance+(%) (For Receiving)
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Remarks
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Value
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Prepared By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Prepared By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Price Basis
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Freight Remarks
                    </div>
                </th>                
			</tr>
			</thead>
			<tbody>
				<tr><td colspan="21"><center>Not Any Search Data</center></td></tr>
			</tbody>';
				
			return response()->json(['status' => 1, 'data' => $records, 'msg' => 'Data Not Found!']);
		}else{
			return view('login');
		}
		
    }


    public function get_columns(){

        return '<thead>
            <tr class="hDiv">
                <th>
                    <div class="text-left field-sorting">
                    PO NO.          
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Amendment No.       
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Unit Code   
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Unit Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    JO/PO Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Type2
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Vendor Code
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Vendor Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Status
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Valid From
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Valid To
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Amendment Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    With Effect Of
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Location
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Location Description
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Delivery Term
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    P/F Type
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    AMT% PF Charge
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Ins Code
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Ins AMT
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Others
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Mode Of Dispatch
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Freight By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    AMT % Freight
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Currency
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bankers
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bankers Address
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Our Bank A/C.
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Destination
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Transporter
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Transporter Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Dispatch Instruction
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Based On
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Project/ Job No.
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Tolerance+(%) (For Receiving)
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Remarks
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    PO Value
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Prepared By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Prepared By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Check Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Verified Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved By
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved By Name
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Approved Date
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Price Basis
                    </div>
                </th>
                <th>
                    <div class="text-left field-sorting">
                    Freight Remarks
                    </div>
                </th>                
            </tr>
            </thead>
            <tbody>
                <tr><td colspan="21"><center>Not Any Search Data</center></td></tr>
            </tbody>';

    }
    

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $token = @$_REQUEST['_ts'];
        $search_session = session()->get($token);
        //dd($search_session);
        $po_types = SecControlValue::where('control_type', 'PO_TYPE')->where('enabled_flag', 'Y')->get();
        $types = SecControlValue::where('control_type', 'PO_UNIT_TYPE')->where('enabled_flag', 'Y')->get();
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        return view('Material.Transactions.Purchase.PurchaseOrder.index', compact('types','po_types','search_session','user'));  
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $vendors = VendorMaster::all();
        $group_codes = GroupMst::all();
        $hsnmasters = HsnMaster::all();
        $units = Unit::all();
        $part_types = SecControlValue::where('control_type', 'PART_TYP')->where('enabled_flag', 'Y')->get();
        $raw_material_types = SecControlValue::where('control_type', 'MATERIAL_GRADE')->where('enabled_flag', 'Y')->get();
        $lot_generations = SecControlValue::where('control_type', 'ITEM_CLASS')->where('enabled_flag', 'Y')->get();
        $procurment_types = SecControlValue::where('control_type', 'PROCURMENT_TYP')->where('enabled_flag', 'Y')->get();
        $unit_of_measurements = UnitOfMeasurement::all();
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        return view('Material.Transactions.Purchase.PurchaseOrder.create', compact('vendors', 'group_codes', 'part_types', 'hsnmasters', 'units', 'raw_material_types','unit_of_measurements', 'lot_generations', 'procurment_types','user'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

		$validator = Validator::make($request->all(), [
			'ven_cd' => 'required',
			'ship_to' => 'required',
			'cost_centre' => 'required',
		]);
		
		if($validator->fails()){
			$fields = $validator->failed();
			
			// Message for ven_cd validate
			if(isset($fields['ven_cd'])){
				return response()->json(['status' => 0, 'message' => 'Bill To is required!', 'data' => '']);
			}

            // Message for ship_to validate
			if(isset($fields['ship_to'])){
				return response()->json(['status' => 0, 'message' => 'Ship To is required!', 'data' => '']);
			}
			
			// Message for cost_centre validate
			if(isset($fields['cost_centre'])){
				return response()->json(['status' => 0, 'message' => 'Location is required!', 'data' => '']);
			}

		}
		
        // Save part master data
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];
            // Generate PO No
            $po_no = DB::select("select termsdb.generate_po_no('$request->po_type','$unit')");
            $po_no = $po_no[0]->generate_po_no;



            \DB::beginTransaction();

            // Head details Save            
            $PoHead = new PoHead();
            $PoHead->jo_po = $request->jo_po;
            $PoHead->po_type = $request->po_type;
            $PoHead->unit_cd = $unit;
            $PoHead->po_no = $po_no;
            $PoHead->po_dt = $request->po_dt;
            $PoHead->po_type2 = $request->po_type2;
            $PoHead->po_unit_type = $request->po_unit_type;
            $PoHead->amd_no = $request->amd_no;
            $PoHead->amd_dt = $request->amd_dt;
            $PoHead->amd_wef = $request->amd_wef;
            $PoHead->po_status = $request->po_status;
            $PoHead->po_value = $request->po_value;
            $PoHead->gst_rate = $request->gst_rate;
            $PoHead->valid_fr = $request->valid_fr;
            $PoHead->valid_to = $request->valid_to;
            $PoHead->cost_centre = $request->cost_centre;
            $PoHead->ven_cd = $request->ven_cd;
            $PoHead->ship_to = $request->ship_to;
            $PoHead->sales_tax_type = $request->sales_tax_type;
            $PoHead->desti = $request->desti;
            $PoHead->mode_del = $request->mode_del;
            $PoHead->cur_cd = $request->cur_cd;
            $PoHead->pf_type = $request->pf_type;
            $PoHead->pf_charge = $request->pf_charge;
            $PoHead->tot_per = $request->tot_per;
            $PoHead->ins_cd = $request->ins_cd;
            $PoHead->ins_amt = $request->ins_amt;
            $PoHead->freight1 = $request->freight1;
            $PoHead->freight = $request->freight;
            $PoHead->del_term = $request->del_term;
            $PoHead->credit_condition = $request->credit_condition;
            $PoHead->price_basis = $request->price_basis;
            $PoHead->pay_type = $request->pay_type;
            $PoHead->credit_condition = $request->credit_condition;
            $PoHead->transporter = $request->transporter;
            $PoHead->reference = $request->reference;
            $PoHead->tolerance_remark = $request->tolerance_remark;
            $PoHead->remarks = $request->remarks;
            $PoHead->prep_by = $request->prep_by;
            $PoHead->quotation_no = $request->quotation_no;
            $PoHead->created_by = $emp_id;
            $PoHead->last_updated_by = $emp_id;
            $PoHead->object_version_number = 0;
            $PoHead->entry_dt = date('Y-m-d H:i:s');
            $PoHead->save();

            // Reference Document
            $total_no_doc = 0;
            if($request->has('doc')){
                $total_no_doc = count($request->doc['doc_sl_no']);
            }
            if($total_no_doc > 0){
                for($x=0; $x< $total_no_doc; $x++){
                    // Upload Document
                    $original_file_name = $_FILES['doc']['name']['doc_file_name'][$x];
                    $image = $request->doc['doc_file_name'][$x];
                    $content_type = $image->getClientOriginalExtension();
                    $name = uniqid(rand()).'.'.$content_type;
                    $destinationPath = public_path('/po_docs');
                    $image->move($destinationPath, $name);
                    
                    $DocAttachRefDtlModel = new DocAttachRefDtlModel();
                    $DocAttachRefDtlModel->doc_sl_no = $request->doc['doc_sl_no'][$x];
                    $DocAttachRefDtlModel->unit_cd = $request->doc['unit_cd'][$x];
                    $DocAttachRefDtlModel->ref_doc_no = $po_no;
                    $DocAttachRefDtlModel->doc_id_ref = $PoHead->po_id;
                    $DocAttachRefDtlModel->ref_doc_type = $request->doc['ref_doc_type'][$x];
                    $DocAttachRefDtlModel->doc_file_name = $name;
                    $DocAttachRefDtlModel->path = '/po_docs/'.$name;
                    $DocAttachRefDtlModel->content_type = $content_type;
                    $DocAttachRefDtlModel->original_file_name = $original_file_name;
                    $DocAttachRefDtlModel->ref_doc_date = $request->doc['ref_doc_date'][$x];
                    $DocAttachRefDtlModel->remarks = $request->doc['remarks'][$x];
                    $DocAttachRefDtlModel->created_by = $emp_id;
                    $DocAttachRefDtlModel->last_updated_by = $emp_id;
                    $DocAttachRefDtlModel->object_version_number = 0;
                    $DocAttachRefDtlModel->save();
                }
            }



            // Save Annexure Tab Data
            $total_no_anne = 0;
            if($request->has('annexure')){
                $total_no_anne = count($request->annexure['s_no']);
            }
            if($total_no_anne > 0){
                for($x=0; $x< $total_no_anne; $x++){                
                    $AnnexPoMst = new AnnexPoMst();
                    $AnnexPoMst->po_no = $po_no;
                    $AnnexPoMst->po_id = $PoHead->po_id;
                    $AnnexPoMst->s_no = $request->annexure['s_no'][$x];
                    $AnnexPoMst->annex_type = $request->annexure['annex_type'][$x];
                    $AnnexPoMst->subject = $request->annexure['subject'][$x];
                    $AnnexPoMst->sales_desc = $request->annexure['sales_desc'][$x];
                    $AnnexPoMst->cancel_ter = @$request->annexure['cancel_ter'][$x];
                    $AnnexPoMst->created_by = $emp_id;
                    $AnnexPoMst->last_updated_by = $emp_id;
                    $AnnexPoMst->object_version_number = 0;
                    $AnnexPoMst->amd_no = 0;
                    $AnnexPoMst->save();
                }
            }



            // Save Payment Terms Tab Data
            $total_no_pay_term = 0;
            if($request->has('pay_term')){
                $total_no_pay_term = count($request->pay_term['pay_cd']);
            }else{
                return response()->json(['status' => 0, 'message' => 'PO Payment term details is required!', 'data' => '']);
            }
            if($total_no_pay_term > 0){
                for($x=0; $x< $total_no_pay_term; $x++){                
                    $MultiPayTerm = new MultiPayTerm();
                    $MultiPayTerm->po_no = $po_no;
                    $MultiPayTerm->po_id = $PoHead->po_id;
                    $MultiPayTerm->pay_cd = $request->pay_term['pay_cd'][$x];
                    $MultiPayTerm->days = $request->pay_term['days'][$x];
                    $MultiPayTerm->percentage = $request->pay_term['percentage'][$x];
                    $MultiPayTerm->remarks = $request->pay_term['remarks'][$x];
                    $MultiPayTerm->created_by = $emp_id;
                    $MultiPayTerm->last_updated_by = $emp_id;
                    $MultiPayTerm->object_version_number = 0;
                    $MultiPayTerm->amd_no = 0;
                    $MultiPayTerm->save();
                }
            }
            

            // Save Calculation Tab Data
            $total_no_pay_calcu = 0;
            if($request->has('po_calc')){
                $total_no_pay_calcu = count($request->po_calc['sno']);
            }else{
                return response()->json(['status' => 0, 'message' => 'PO Calculation details is required!', 'data' => '']);
            }
            
            if($total_no_pay_calcu > 0){
                for($x=0; $x< $total_no_pay_calcu; $x++){                
                    $PoCalc = new PoCalc();
                    $PoCalc->po_no = $po_no;
                    $PoCalc->po_id = $PoHead->po_id;
                    $PoCalc->sno = $request->po_calc['sno'][$x];
                    $PoCalc->head = $request->po_calc['head'][$x];
                    $PoCalc->oper = $request->po_calc['oper'][$x];
                    $PoCalc->cal_on = $request->po_calc['cal_on'][$x];
                    $PoCalc->unit = $request->po_calc['unit'][$x];
                    $PoCalc->based_on = $request->po_calc['based_on'][$x];
                    $PoCalc->amount = $request->po_calc['amount'][$x];
                    $PoCalc->created_by = $emp_id;
                    $PoCalc->last_updated_by = $emp_id;
                    $PoCalc->object_version_number = 0;
                    $PoCalc->amd_no = 0;
                    $PoCalc->save();
                }
            }



            // Save Item Details Tab Data
            $total_no_items = 0;
            if($request->has('item')){
                $total_no_items = count($request->item['pios_pois_no']);
            }else{
                return response()->json(['status' => 0, 'message' => 'Minimum one item details is required!', 'data' => '']);
            }
            if($total_no_items > 0){
                for($x=0; $x< $total_no_items; $x++){  
                    // Check item rate should be greater than 0
                    if($request->item['material_rate'][$x] <= 0){
                        \DB::rollback();
                        return response()->json(['status' => 0, 'message' => 'Row No: '.$x.', Material Rate should be greater than 0!', 'data' => '']);
                    }

                    // PROCESS SEQUENCE MUST BE GREATER THAN 0 IN CASE OF PO CATEGORY IS JOBWORK  AND PO TYPE IS JOBWORK ORDER
                    if($request->po_type == 'JW' && $request->jo_po == 'J'){
                        if($request->item['proc_seq'][$x] <= 0){
                            \DB::rollback();
                            return response()->json(['status' => 0, 'message' => 'Row No: '.$x.', Process sequence must be greater than 0 in case of po category is jobwork  and po type is jobwork order!', 'data' => '']);
                        }
                    }



                    $PoDetail = new PoDetail();
                    $PoDetail->po_head_po_no = $po_no;
                    $PoDetail->po_id = $PoHead->po_id;
                    $PoDetail->pios_pois_no = $request->item['pios_pois_no'][$x];
                    $PoDetail->pios_item_cd = $request->item['pios_item_cd'][$x];
                    $PoDetail->make_by = $request->item['make_by'][$x];
                    $PoDetail->item_specf = $request->item['item_specf'][$x];
                    $PoDetail->quot_no = $request->item['quot_no'][$x];
                    $PoDetail->uom = $request->item['uom'][$x];
                    $PoDetail->proc_seq = $request->item['proc_seq'][$x];
                    $PoDetail->proc_cd = $request->item['proc_cd'][$x];
                    $PoDetail->rec_weight = $request->item['rec_weight'][$x];
                    $PoDetail->qty = $request->item['qty'][$x];
                    $PoDetail->material_rate = $request->item['material_rate'][$x];
                    $PoDetail->rate_uom = $request->item['rate_uom'][$x];
                    $PoDetail->discount_percent = $request->item['discount_percent'][$x];
                    $PoDetail->discount_amt = $request->item['discount_amt'][$x];

                    $PoDetail->uc_amount = $request->item['uc_amount'][$x];
                    $PoDetail->gst_code = $request->item['gst_code'][$x];
                    $PoDetail->sgst_per = $request->item['sgst_per'][$x];
                    $PoDetail->sgst_amt = $request->item['sgst_amt'][$x];
                    $PoDetail->cgst_per = $request->item['cgst_per'][$x];
                    $PoDetail->cgst_amt = $request->item['cgst_amt'][$x];
                    $PoDetail->gst_percent = $request->item['gst_percent'][$x];

                    $PoDetail->gst_amt = $request->item['gst_amt'][$x];
                    $PoDetail->po_hsn_code = $request->item['po_hsn_code'][$x];
                    $PoDetail->goods_value = $request->item['goods_value'][$x];
                    $PoDetail->po_detail_others = $request->item['po_detail_others'][$x];
                    $PoDetail->landed_cost = $request->item['landed_cost'][$x];
                    $PoDetail->ed_date = $request->item['ed_date'][$x];
                    $PoDetail->tolerance_per = $request->item['tolerance_per'][$x];
                    $PoDetail->remarks = $request->item['remarks'][$x];

                    $PoDetail->created_by = $emp_id;
                    $PoDetail->last_updated_by = $emp_id;
                    $PoDetail->object_version_number = 0;
                    $PoDetail->po_head_amd_no = 0;


                    $PoDetail->save();

                    $schedule = $request->item['sl_no'][$x];


                    if (isset($schedule['schedule']['schedule_dt']) && count($schedule['schedule']['schedule_dt']) > 0) {

                        for($m=0; $m< count($schedule['schedule']['schedule_dt']); $m++){
                           
                            $po_delv_schd = new PoDelvSchd;
                            $po_delv_schd->po_no = $po_no;
                            $po_delv_schd->po_hd_id = $PoHead->po_id;
                            $po_delv_schd->po_dtl_line_id = $PoDetail->po_line_id;
                            $po_delv_schd->schd_date = $schedule['schedule']['schedule_dt'][$m];
                            $po_delv_schd->schd_qty = $schedule['schedule']['schedule_qty'][$m];
                            $po_delv_schd->item_cd = $request->item['pios_item_cd'][$x];
                            $po_delv_schd->amd_no = 0;
                            $po_delv_schd->save();
                        }


                    }

                }
            }
            
            \DB::commit();
            return response()->json(['status' => 1, 'message' => 'Purchase Order Added Successfully, PO No Is '.$po_no, 'data' => '']);
        }catch(\Exception $e) {
            \DB::rollback();
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Testimonial  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $PoHead = PoHead::where('po_id', $id)->with('getVenderDetails')->with('getShipVenderDetails')->with('getVenderAddDetails')->with('getShipVenderAddDetails')->with('getLocationDetails')->with('getDocDetails')->first();
        // dd($PoHead->getPoDetail[0]->getScheduleoDetail);
        $token = $_REQUEST['_ts'];
        $user = session()->get($token);
        return view('Material.Transactions.Purchase.PurchaseOrder.view', compact('PoHead','user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Testimonial  $promocode
     * @return \Illuminate\Http\Response
     */
    public function showUpdate($id){
        $PoHead = PoHead::where('po_id', $id)->with('getVenderDetails')->with('getShipVenderDetails')->with('getVenderAddDetails')->with('getShipVenderAddDetails')->with('getLocationDetails')->with('getDocDetails')->first();
        // dd($PoHead->getPoDetail[0]->getScheduleoDetail);
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        return view('Material.Transactions.Purchase.PurchaseOrder.update', compact('PoHead','user'));
    }

   

    public function saveUpdateData(Request $request){
        $validator = Validator::make($request->all(), [
			'ven_cd' => 'required',
			'ship_to' => 'required',
			'cost_centre' => 'required',
		]);
		
		if($validator->fails()){
			$fields = $validator->failed();
			
			// Message for ven_cd validate
			if(isset($fields['ven_cd'])){
				return response()->json(['status' => 0, 'message' => 'Bill To is required!', 'data' => '']);
			}

            // Message for ship_to validate
			if(isset($fields['ship_to'])){
				return response()->json(['status' => 0, 'message' => 'Ship To is required!', 'data' => '']);
			}
			
			// Message for cost_centre validate
			if(isset($fields['cost_centre'])){
				return response()->json(['status' => 0, 'message' => 'Location is required!', 'data' => '']);
			}
		}
		
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];

            // Generate PO No
            $po_no = $request->po_no;
            $amd_no = $request->amd_no;

            \DB::beginTransaction();

            // Delete All related datas
            DocAttachRefDtlModel::where('ref_doc_no',$po_no)->delete();
            AnnexPoMst::where('po_no',$po_no)->where('amd_no',$amd_no)->delete();
            MultiPayTerm::where('po_no',$po_no)->where('amd_no',$amd_no)->delete();
            PoCalc::where('po_no',$po_no)->where('amd_no',$amd_no)->delete();
            PoDetail::where('po_head_po_no',$po_no)->where('po_head_amd_no', $amd_no)->delete();
            PoDelvSchd::where('po_no',$po_no)->delete();

            // Head details Save            
            $PoHead = PoHead::where('po_id', $request->update_id)->first();
            $PoHead->jo_po = $request->jo_po;
            $PoHead->po_type = $request->po_type;
            $PoHead->unit_cd = $unit;
            $PoHead->po_dt = $request->po_dt;
            $PoHead->po_type2 = $request->po_type2;
            $PoHead->po_unit_type = $request->po_unit_type;
            $PoHead->amd_no = $request->amd_no;
            $PoHead->amd_dt = $request->amd_dt;
            $PoHead->amd_wef = $request->amd_wef;
            $PoHead->po_status = $request->po_status;
            $PoHead->po_value = $request->po_value;
            $PoHead->gst_rate = $request->gst_rate;
            $PoHead->valid_fr = $request->valid_fr;
            $PoHead->valid_to = $request->valid_to;
            $PoHead->cost_centre = $request->cost_centre;
            $PoHead->ven_cd = $request->ven_cd;
            $PoHead->ship_to = $request->ship_to;
            $PoHead->sales_tax_type = $request->sales_tax_type;
            $PoHead->desti = $request->desti;
            $PoHead->mode_del = $request->mode_del;
            $PoHead->cur_cd = $request->cur_cd;
            $PoHead->pf_type = $request->pf_type;
            $PoHead->pf_charge = $request->pf_charge;
            $PoHead->tot_per = $request->tot_per;
            $PoHead->ins_cd = $request->ins_cd;
            $PoHead->ins_amt = $request->ins_amt;
            $PoHead->freight1 = $request->freight1;
            $PoHead->freight = $request->freight;
            $PoHead->del_term = $request->del_term;
            $PoHead->credit_condition = $request->credit_condition;
            $PoHead->price_basis = $request->price_basis;
            $PoHead->pay_type = $request->pay_type;
            $PoHead->credit_condition = $request->credit_condition;
            $PoHead->transporter = $request->transporter;
            $PoHead->reference = $request->reference;
            $PoHead->tolerance_remark = $request->tolerance_remark;
            $PoHead->remarks = $request->remarks;
            $PoHead->quotation_no = $request->quotation_no;
            $PoHead->last_updated_by = $emp_id;
            $PoHead->chck_by = $request->chck_by;
            $PoHead->chck_dt = $request->chck_dt;
            $PoHead->frez_by = $request->frez_by;
            $PoHead->frez_dt = $request->frez_dt;
            $PoHead->appr_by = $request->appr_by;
            $PoHead->appr_dt = $request->appr_dt;
            $PoHead->save();

            // Reference Document
            $total_no_doc = 0;
            if($request->has('doc')){
                $total_no_doc = count($request->doc['doc_sl_no']);
            }
            if($total_no_doc > 0){
                for($x=0; $x< $total_no_doc; $x++){
                    //Upload Document
                    if($request->doc['doc_file_name_old'][$x] != ''){
                        $doc_file_name_old = $request->doc['doc_file_name_old'][$x];
                        $full_name = explode('.', $doc_file_name_old);
                        $name = $doc_file_name_old;
                        $original_file_name = $doc_file_name_old;
                        $content_type = $full_name[1];
                    }else{
                        $original_file_name = $_FILES['doc']['name']['doc_file_name'][$x];
                        $image = $request->doc['doc_file_name'][$x];
                        $content_type = $image->getClientOriginalExtension();
                        $name = uniqid(rand()).'.'.$content_type;
                        $destinationPath = public_path('/po_docs');
                        $image->move($destinationPath, $name);
                    }
                    
                    $DocAttachRefDtlModel = new DocAttachRefDtlModel();
                    $DocAttachRefDtlModel->doc_sl_no = $request->doc['doc_sl_no'][$x];
                    $DocAttachRefDtlModel->unit_cd = $request->doc['unit_cd'][$x];
                    $DocAttachRefDtlModel->ref_doc_no = $po_no;
                    $DocAttachRefDtlModel->doc_id_ref = $PoHead->po_id;
                    $DocAttachRefDtlModel->ref_doc_type = $request->doc['ref_doc_type'][$x];
                    $DocAttachRefDtlModel->doc_file_name = $name;
                    $DocAttachRefDtlModel->path = '/po_docs/'.$name;
                    $DocAttachRefDtlModel->content_type = $content_type;
                    $DocAttachRefDtlModel->original_file_name = $original_file_name;
                    $DocAttachRefDtlModel->ref_doc_date = $request->doc['ref_doc_date'][$x];
                    $DocAttachRefDtlModel->remarks = $request->doc['remarks'][$x];
                    $DocAttachRefDtlModel->created_by = $emp_id;
                    $DocAttachRefDtlModel->last_updated_by = $emp_id;
                    $DocAttachRefDtlModel->object_version_number = 0;
                    $DocAttachRefDtlModel->save();
                }
            }

            // Save Annexure Tab Data
            $total_no_anne = 0;
            if($request->has('annexure')){
                $total_no_anne = count($request->annexure['s_no']);
            }
            if($total_no_anne > 0){
                for($x=0; $x< $total_no_anne; $x++){                
                    $AnnexPoMst = new AnnexPoMst();
                    $AnnexPoMst->po_no = $po_no;
                    $AnnexPoMst->po_id = $PoHead->po_id;
                    $AnnexPoMst->s_no = $request->annexure['s_no'][$x];
                    $AnnexPoMst->annex_type = $request->annexure['annex_type'][$x];
                    $AnnexPoMst->subject = $request->annexure['subject'][$x];
                    $AnnexPoMst->sales_desc = $request->annexure['sales_desc'][$x];
                    $AnnexPoMst->cancel_ter = @$request->annexure['cancel_ter'][$x];
                    $AnnexPoMst->created_by = $emp_id;
                    $AnnexPoMst->last_updated_by = $emp_id;
                    $AnnexPoMst->object_version_number = 0;
                    $AnnexPoMst->amd_no = $request->amd_no;
                    $AnnexPoMst->save();
                }
            }

            // Save Payment Terms Tab Data
            $total_no_pay_term = 0;
            if($request->has('pay_term')){
                $total_no_pay_term = count($request->pay_term['pay_cd']);
            }
            if($total_no_pay_term > 0){
                for($x=0; $x< $total_no_pay_term; $x++){                
                    $MultiPayTerm = new MultiPayTerm();
                    $MultiPayTerm->po_no = $po_no;
                    $MultiPayTerm->po_id = $PoHead->po_id;
                    $MultiPayTerm->pay_cd = $request->pay_term['pay_cd'][$x];
                    $MultiPayTerm->days = $request->pay_term['days'][$x];
                    $MultiPayTerm->percentage = $request->pay_term['percentage'][$x];
                    $MultiPayTerm->remarks = $request->pay_term['remarks'][$x];
                    $MultiPayTerm->created_by = $emp_id;
                    $MultiPayTerm->last_updated_by = $emp_id;
                    $MultiPayTerm->object_version_number = 0;
                    $MultiPayTerm->amd_no = $request->amd_no;
                    $MultiPayTerm->save();
                }
            }
            

            // Save Calculation Tab Data
            $total_no_pay_calcu = 0;
            if($request->has('po_calc')){
                $total_no_pay_calcu = count($request->po_calc['sno']);
            }
            if($total_no_pay_calcu > 0){
                for($x=0; $x< $total_no_pay_calcu; $x++){                
                    $PoCalc = new PoCalc();
                    $PoCalc->po_no = $po_no;
                    $PoCalc->po_id = $PoHead->po_id;
                    $PoCalc->sno = $request->po_calc['sno'][$x];
                    $PoCalc->head = $request->po_calc['head'][$x];
                    $PoCalc->oper = $request->po_calc['oper'][$x];
                    $PoCalc->cal_on = $request->po_calc['cal_on'][$x];
                    $PoCalc->unit = $request->po_calc['unit'][$x];
                    $PoCalc->based_on = $request->po_calc['based_on'][$x];
                    $PoCalc->amount = $request->po_calc['amount'][$x];
                    $PoCalc->created_by = $emp_id;
                    $PoCalc->last_updated_by = $emp_id;
                    $PoCalc->object_version_number = 0;
                    $PoCalc->amd_no = $request->amd_no;
                    $PoCalc->save();
                }
            }

            // Save Item Details Tab Data
            $total_no_items = 0;
            if($request->has('item')){
                $total_no_items = count($request->item['pios_pois_no']);
            }else{
                return response()->json(['status' => 0, 'message' => 'Minimum one item details is required!', 'data' => '']);
            }
            if($total_no_items > 0){
                for($x=0; $x< $total_no_items; $x++){     
                    // Check item rate should be greater than 0
                    if($request->item['material_rate'][$x] <= 0){
                        \DB::rollback();
                        return response()->json(['status' => 0, 'message' => 'Row No: '.$x.', Material Rate should be greater than 0!', 'data' => '']);
                    }           
                    $PoDetail = new PoDetail();
                    $PoDetail->po_head_po_no = $po_no;
                    $PoDetail->po_id = $PoHead->po_id;
                    $PoDetail->pios_pois_no = $request->item['pios_pois_no'][$x];
                    $PoDetail->pios_item_cd = $request->item['pios_item_cd'][$x];
                    $PoDetail->make_by = $request->item['make_by'][$x];
                    $PoDetail->item_specf = $request->item['item_specf'][$x];
                    $PoDetail->quot_no = $request->item['quot_no'][$x];
                    $PoDetail->uom = $request->item['uom'][$x];
                    $PoDetail->proc_seq = $request->item['proc_seq'][$x];
                    $PoDetail->proc_cd = $request->item['proc_cd'][$x];
                    $PoDetail->rec_weight = $request->item['rec_weight'][$x];
                    $PoDetail->qty = $request->item['qty'][$x];
                    $PoDetail->material_rate = $request->item['material_rate'][$x];
                    $PoDetail->rate_uom = $request->item['rate_uom'][$x];
                    $PoDetail->discount_percent = $request->item['discount_percent'][$x];
                    $PoDetail->discount_amt = $request->item['discount_amt'][$x];
                    $PoDetail->uc_amount = $request->item['uc_amount'][$x];
                    $PoDetail->gst_code = $request->item['gst_code'][$x];
                    $PoDetail->sgst_per = $request->item['sgst_per'][$x];
                    $PoDetail->sgst_amt = $request->item['sgst_amt'][$x];
                    $PoDetail->cgst_per = $request->item['cgst_per'][$x];
                    $PoDetail->cgst_amt = $request->item['cgst_amt'][$x];
                    $PoDetail->gst_percent = $request->item['gst_percent'][$x];
                    $PoDetail->gst_amt = $request->item['gst_amt'][$x];
                    $PoDetail->po_hsn_code = $request->item['po_hsn_code'][$x];
                    $PoDetail->goods_value = $request->item['goods_value'][$x];
                    $PoDetail->po_detail_others = $request->item['po_detail_others'][$x];
                    $PoDetail->landed_cost = $request->item['landed_cost'][$x];
                    $PoDetail->ed_date = $request->item['ed_date'][$x];
                    $PoDetail->tolerance_per = $request->item['tolerance_per'][$x];
                    $PoDetail->remarks = $request->item['remarks'][$x];
                    $PoDetail->created_by = $emp_id;
                    $PoDetail->last_updated_by = $emp_id;
                    $PoDetail->object_version_number = 0;
                    $PoDetail->po_head_amd_no = $request->amd_no;
                    $PoDetail->save();

                    $schedule = $request->item['sl_no'][$x];


                    if (isset($schedule['schedule']['schedule_dt']) && count($schedule['schedule']['schedule_dt']) > 0) {

                        for($m=0; $m< count($schedule['schedule']['schedule_dt']); $m++){
                           
                            $po_delv_schd = new PoDelvSchd;
                            $po_delv_schd->po_no = $po_no;
                            $po_delv_schd->po_hd_id = $PoHead->po_id;
                            $po_delv_schd->po_dtl_line_id = $PoDetail->po_line_id;
                            $po_delv_schd->schd_date = $schedule['schedule']['schedule_dt'][$m];
                            $po_delv_schd->schd_qty = $schedule['schedule']['schedule_qty'][$m];
                            $po_delv_schd->item_cd = $request->item['pios_item_cd'][$x];
                            $po_delv_schd->amd_no = 0;
                            $po_delv_schd->save();
                        }


                    }
                }
            }
            
            \DB::commit();
            return response()->json(['status' => 1, 'message' => 'Purchase Order Updated Successfully, PO No Is '.$po_no, 'data' => '']);
        }catch(\Exception $e) {
            \DB::rollback();
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    // Cancel PO
    public function cancelPO(Request $request){
		
        // Cancel PO
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];
            $po_no = $request->po_no;
            $amd_no = $request->amd_no;

            // Check the this po updateable or not by amenment no
            $last_amen = DB::select( DB::raw("select get_latest_po_amend('$po_no');")); 
            $last_amen=@$last_amen[0]->get_latest_po_amend;
            if($last_amen != $amd_no){
                return response()->json(['status' => 0, 'message' => 'Only Latest Amenment PO Cancel Allowed!', 'data' => '']);
                exit;
            }

            \DB::beginTransaction();

            // Head details Save     
            PoHead::where('po_id', $request->po_id)->update(['po_status'=>'N']);  

            // Item details Save 
            PoDetail::where('po_id', $request->po_id)->update(['closed'=>'C']);
            
            
            \DB::commit();
            return response()->json(['status' => 1, 'message' => 'Purchase Order Cancel Successfully!', 'data' => '']);
        }catch(\Exception $e) {
            \DB::rollback();
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    // Amenment PO
    public function amenmentPO(Request $request){
		
        // Cancel PO
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];
            $po_no = $request->po_no;
            $amd_no = $request->amd_no;

            // Check the this po updateable or not by amenment no
            $last_amen = DB::select( DB::raw("select get_latest_po_amend('$po_no');")); 
            $last_amen=@$last_amen[0]->get_latest_po_amend;
            if($last_amen == $amd_no){
                $res= DB::select( DB::raw("call generate_po_amendment('$po_no','$amd_no','$unit');"));
                return response()->json(['status' => 1, 'message' => 'PO Amenment Successfull Of This PO:'.$po_no, 'data' => '']);
            }else{
                return response()->json(['status' => 0, 'message' => 'Only Latest Amenment PO Amenment Allowed!', 'data' => '']);
            }
        }catch(\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    // Check the this po updateable or not by amenment no
    public function checkLatestAmenPO(Request $request){
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];
            $po_no = $request->po_no;
            $amd_no = $request->amd_no;

            // Check the this po updateable or not by amenment no
            $last_amen = DB::select( DB::raw("select get_latest_po_amend('$po_no');")); 
            $last_amen=@$last_amen[0]->get_latest_po_amend;

            if((int)$last_amen != (int)$amd_no){
                return response()->json(['status' => 0, 'message' => 'Only Latest Amenment PO Update Allowed!', 'data' => '']);
            }else{
                return response()->json(['status' => 1, 'message' => '', 'data' => '']);
            }
        }catch(\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    // Calculate final po value with extra cost
    public function calculateFinalPoValue(Request $request){
        try{
            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $unit=$user['unitcode'];
            $emp_id=$user['emp_id'];
            
            $pf_type = $request->pf_type;
			$po_value = $request->po_value;
			$pf_charge = $request->pf_charge;
	        $gst_rate = $request->gst_rate;
			$ins_amt = $request->ins_amt;
			$freight_amt = $request->freight_amt; 
			$tot_per = $request->tot_per; 
            $othercharges = 0;
            $pf_charge_amt = 0;
            $ins_charge_amt = 0;
            $freight_charge_amt = 0;
            if($pf_type == 'P'){
				//PO Value = ((PO_Value*P/F Charge/100) + ((PO_Value*P/F Charge/100) *Max GST%)) + (Insurance Amount + (Insurance Amount*Max GST%)) + (AMT % Freight + (AMT % Freight*Max GST%))
				//$othercharges = (($po_value*$pf_charge/100) + (($po_value*$pf_charge)/100*$gst_rate) + (($ins_amt*$gst_rate) + ($freight_amt)) + ($freight_amt*$gst_rate)); 
                $pf_charge_per = $po_value*$pf_charge/100;
                $pf_charge_amt = ($pf_charge_per+$pf_charge_per)*$gst_rate;
                //$pf_charge_amt = ($pf_charge_per*$gst_rate/100)+$pf_charge_per;
                $ins_charge_amt = ($ins_amt*$gst_rate/100)+$ins_amt;
                $freight_charge_amt = ($freight_amt*$gst_rate/100)+$freight_amt; 

				$othercharges = $pf_charge_amt+$ins_charge_amt+$freight_charge_amt;
                
			}else if($pf_type == 'AMT'){
				//PO Value = PO Value + (P/F Charge + (P/F Charge*Max GST%)) + (Insurance Amount + (Insurance Amount*Max GST%)) + (AMT % Freight + (AMT % Freight*Max GST%))

                $pf_charge_amt = ($pf_charge*$gst_rate/100)+$pf_charge;
                $ins_charge_amt = ($ins_amt*$gst_rate/100)+$ins_amt;
                $freight_charge_amt = ($freight_amt*$gst_rate/100)+$freight_amt;

				$othercharges = $pf_charge_amt+$ins_charge_amt+$freight_charge_amt;
			}
            //select 1180 + ((10 + (10*18/100))+ (10+(10*18/100)) +(10 + (10*18/100)) ) + (1180 + ((10 + (10*18/100))+ (10+(10*18/100)) +(10 + (10*18/100)) ) )*2/100;
            $tds = $po_value*$tot_per/100;
            $final_rate = $po_value+$othercharges;
            $po_value = str_replace(',', '',number_format($final_rate+$tds,2));
            return response()->json(['status' => 1, 'message' => 'Calculate Final PO Value', 'data' => '', 'po_value' => $po_value]);
        }catch(\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }
    }

    /**
     * Get the vender GST rate type
     *
     * @param  $ven_cd
     * @return String
     */
    public function getVenderTypeByVenCd(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $unit=$user['unitcode'];
        $vend_unit = DB::table('vend_unit')->where('unit_code',$unit)->where('ven_cd',$request->ven_cd)->first(); 
        echo $vend_unit->party_tp;
    }
  
    /**
     * Get the vender GST rate type
     *
     * @param  $ven_cd
     * @return String
     */
    public function getUnitRateOfItem(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $unit=$user['unitcode'];
        $item_cd=$request->item_cd;
        $proc_seq=$request->proc_seq;
        $proc_cd=$request->proc_cd;
        $res=DB::select( DB::raw("select po_rate('$item_cd','$proc_seq',0,'$proc_cd','$unit')") );
        echo $res[0]->po_rate;
    }

    /**
     * Calculate GST Rate
     *
     * @param  Request
     * @return JSON
     */
    public function getGSTTypeRate(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $sgst_per = $request->sgst_per;
        $cgst_per = $request->cgst_per;
        $gst_rate = $request->gst_rate;
        $sales_tax_type = $request->sales_tax_type;
        $uc_amount = $request->uc_amount;

        $sgst_per_amt = round($uc_amount/$sgst_per*100);
        $cgst_per_amt = round($uc_amount/$cgst_per*100);
        $gst_rate_amt = round($uc_amount/$gst_rate*100);

        if(trim($sales_tax_type) == 'V'){
            return response()->json(['status' => 1, 'sgst_per' => $sgst_per,'sgst_per_amt' => $sgst_per_amt,'cgst_per' => $cgst_per,'cgst_per_amt' => $cgst_per_amt,'gst_rate' => 0,'gst_rate_amt' => 0]);
        }else if(trim($sales_tax_type) == 'C'){
            return response()->json(['status' => 1, 'sgst_per' => 0,'sgst_per_amt' => 0,'cgst_per' => 0,'cgst_per_amt' => 0,'gst_rate' => $gst_rate,'gst_rate_amt' => $gst_rate_amt]);
        }else{
            return response()->json(['status' => 1, 'sgst_per' => $sgst_per,'sgst_per_amt' => $sgst_per_amt,'cgst_per' => $cgst_per,'cgst_per_amt' => $cgst_per_amt,'gst_rate' => $gst_rate,'gst_rate_amt' => $gst_rate_amt]);
        }
    }
  
    /**
     * get item rate history
     *
     * @param  Request
     * @return JSON
     */
    public function getRateHistory(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $unit=$user['unitcode'];
        $item_code = $request->item_code;
        $ratehistorys=DB::select( DB::raw("SELECT PO_NO,PO_DT,AMD_NO,VEN_CD,NAME,COALESCE(MATERIAL_RATE,0)+COALESCE(PROS_RATE,0) RATE,DISCOUNT_PERCENT,QTY,
        pd.gst_percent igst,pd.cgst_per ,pd.sgst_per 
        FROM PO_HEAD,po_detail pd ,vendor_master vm 
        WHERE PO_NO=PO_HEAD_PO_NO
        AND AMD_NO=PO_HEAD_AMD_NO and VEN_CD=VENDOR_CODE AND PIOS_ITEM_CD='$item_code'
        AND UNIT_CD='$unit'
        ORDER BY PO_DT,PO_NO,AMD_NO") );

        $rate_his = '';
        if(count($ratehistorys) > 0){
            foreach($ratehistorys as $ratehistory){
                $rate_his .="<tr>";
                $rate_his .="<td>".$ratehistory->po_no."</td>";
                $rate_his .="<td>".$ratehistory->po_dt."</td>";
                $rate_his .="<td>".$ratehistory->amd_no."</td>";
                $rate_his .="<td>".$ratehistory->ven_cd."</td>";
                $rate_his .="<td>".$ratehistory->name."</td>";
                $rate_his .="<td>".$ratehistory->rate."</td>";
                $rate_his .="<td>".$ratehistory->discount_percent."</td>";
                $rate_his .="<td>".$ratehistory->qty."</td>";
                $rate_his .="<td>".$ratehistory->igst."</td>";
                $rate_his .="<td>".$ratehistory->cgst_per."</td>";
                $rate_his .="<td>".$ratehistory->sgst_per."</td>";
                $rate_his .="</tr>";
            }
        }else{
            $rate_his .="<tr><td colspan='11'>Rate History Not Found For This Item ".$item_code."</td></tr>";
        }
        
        return response()->json(['status' => 1, 'data' => $rate_his]);
    }
 
	/** 
     * Print report in PDF Format
     *
     * @return \Illuminate\Http\Response
     */
    // By Atul
    public function printReport(Request $request){
        $po_no = $request->ind_selected_ids ;
        $po_hdata = PoHead::join('vendor_master','vendor_master.vendor_code','=','po_head.ven_cd')->select('po_head.ven_cd','vendor_master.*')->where('po_no', $po_no)->first();
        $ven_con_dtl = PoHead::join('vendor_conttact','vendor_conttact.vendor_code','=','po_head.ven_cd')->select('po_head.ven_cd','vendor_conttact.*')->where('po_no', $po_no)->first();
		$po_hdata1 = PoHead::join('vendor_regd_address','vendor_regd_address.vendor_code','=','po_head.ven_cd')->select('po_head.*','vendor_regd_address.*')->where('po_no', $po_no)->first();
        $po_data = PoDetail::where('po_head_po_no', $po_no)->where('po_head_amd_no',$po_hdata1->amd_no)->get();
        
        $pay_terms = MultiPayTerm::where('po_no',$po_no)->where('amd_no',$po_hdata1->amd_no)->get();

        $water_mark = DB::table('mtl_parameter')->where('key_no',-3)->where('unit_cd',$po_hdata1->unit_cd)->first();
       
		// $token = @$_REQUEST['_ts'];
        // $user = session()->get($token);
		// $login_unit_code = $user['unitcode'];
		$unit_detail = Unit::where('code',$po_hdata1->unit_cd)->first();
        
        $comp = DB::table('company')->where('code',$unit_detail->comp_code)->first();


        $logo = asset('assets/images/'.$comp->logo);
       

        //return view('Material.Transactions.Purchase.PurchaseOrder.printreport1',compact('po_hdata','po_hdata1','po_data','unit_detail','logo','ven_con_dtl','pay_terms','water_mark'));
       
        $pdf = PDF::loadView('Material.Transactions.Purchase.PurchaseOrder.printreport1',compact('po_hdata','po_hdata1','po_data','unit_detail','logo','ven_con_dtl','pay_terms','water_mark'));
        // download PDF file with download method
        return $pdf->stream('Purchase_Order.pdf');
       
}

    /**
     * Get the po item balance quantity
     *
     * @param  $request
     * @return String
     */
    public function getIndentPOBalanceQty(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $unit=$user['unitcode'];
        $indent_no=$request->indent_no;
        $item_cd=$request->item_cd;
        $po_no=$request->po_no;
        $res=DB::select( DB::raw("select get_indent_po_bal('$indent_no','$item_cd', '$po_no')"));
        echo $res[0]->get_indent_po_bal;
    }

    /**
     * Get the current date time
     *
     * 
     * @return String
     */
    public function getCurrentDateTime(){
        echo date('Y-m-d H:i:s');
    }


    /**
     * Get the payment terms by vendor code
     *
     * @param  $request
     * @return String
     */
    public function getPaymentTermsByVen(Request $request){
        $token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $unit=$user['unitcode'];
        $ven_cd=$request->vendor_code;
        $vendor_details = Vendor::select('pay_term')->where('vendor_code', $ven_cd)->first();
        $pay_term = $vendor_details->pay_term;
        $pay_term_details = '';
        $html = '';
        if($pay_term){
            $pay_term_details = PaymentTermsModel::where('pay_term', $pay_term)->first();
            $html = '<tr id="annexuretr_1" class="termConditionRows">
                <td>
                    <input type="text" id="TSO1_payment_term_sno" class="cinput_field tbl_select disabled" name="pay_term[pay_cd][]" autocomplete="off" style="width:120px;" readonly="" value="'.$pay_term_details->sno.'">
                </td>    	
                <td style="text-align:center;">
                    <input type="text" class="cinput_field disabled" id="TSO1_payment_term_pay_term" style="width:120px;" readonly="" value="'.$pay_term_details->pay_term.'">
                </td>
                <td style="text-align:center;">
                    <input type="text" class="cinput_field" id="TSO1_payment_term_no_of_days" name="pay_term[days][]" style="width:120px;" value="'.$pay_term_details->no_of_days.'">
                </td>
                <td style="text-align:center;">
                    <input type="text" class="cinput_field pay_term_percentage" id="TSO1_payment_term_percent_type" name="pay_term[percentage][]" style="width:120px;" value="'.$pay_term_details->percent_type.'">
                </td>
                <td style="text-align:center;">
                    <textarea id="terms_description_1" name="pay_term[remarks][]" style="width:220px; height:23px;" class="item_cinput_field"></textarea>
                </td>
                <td style="text-align:center;">
                    <a href="javascript:void(0);" onclick="removePaymentTermRow(1)" title="Remove Row"><img src="http://termserp.local/assets/icons/delete.png"></a>
                </td>
            </tr>';
            return response()->json(['status' => 1, 'message' => '', 'data' => $html]);
        }
        return response()->json(['status' => 0, 'message' => 'Pay term not available in this vendor', 'data' => '']);
    }



    public function verifyPo(Request $request){


        try{

            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $emp_code = $user['emp_id'];

            $po_no = $request->po_no;
            $rec = \DB::table('approval_autho_view')->select('emp_cd','ename')->where(['form_nm' => 'PO', 'autho_level' => 'VR', 'autho_seq' => '2', 'emp_cd' => $emp_code])->first();



            if($rec){

                $res =  PoHead::where('po_no',$po_no)->update(['appr_by' => $rec->emp_cd,'appr_dt' => date('Y-m-d H:i:s')]);
                // dd($res,$rec->emp_cd,$indent);
                return response()->json(['status' => 1, 'code' => $rec->emp_cd,'name' => $rec->ename,'date' => date('Y-m-d H:i:s')]);
            }
            else{
                return response()->json(['status' => 0, 'message' => 'Record not found']);
            }

        }catch(\Exception $e){
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }

    }



    public function approvePo(Request $request){


        try{

            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $emp_code = $user['emp_id'];

            $po_no = $request->po_no;
            $rec = \DB::table('approval_autho_view')->select('emp_cd','ename')->where(['form_nm' => 'PO', 'autho_level' => 'AP', 'autho_seq' => '3', 'emp_cd' => $emp_code])->first();



            if($rec){

                $res =  PoHead::where('po_no',$po_no)->update(['frez_by' => $rec->emp_cd,'frez_dt' => date('Y-m-d H:i:s')]);
                // dd($res,$rec->emp_cd,$indent);
                return response()->json(['status' => 1, 'code' => $rec->emp_cd,'name' => $rec->ename,'date' => date('Y-m-d H:i:s')]);
            }
            else{
                return response()->json(['status' => 0, 'message' => 'Record not found']);
            }

        }catch(\Exception $e){
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }

    }


    public function checkedPo(Request $request){

        try{

            $token = @$_REQUEST['_ts'];
            $user = session()->get($token);
            $emp_code = $user['emp_id'];

            $po_no = $request->po_no;
            $rec = \DB::table('approval_autho_view')->select('emp_cd','ename')->where(['form_nm' => 'PO', 'autho_level' => 'CH', 'autho_seq' => '1', 'emp_cd' => $emp_code])->first();



            if($rec){

                $res =  PoHead::where('po_no',$po_no)->update(['chck_by' => $rec->emp_cd,'chck_dt' => date('Y-m-d H:i:s')]);
                // dd($res,$rec->emp_cd,$indent);
                return response()->json(['status' => 1, 'code' => $rec->emp_cd,'name' => $rec->ename,'date' => date('Y-m-d H:i:s')]);
            }
            else{
                return response()->json(['status' => 0, 'message' => 'Record not found']);
            }

        }catch(\Exception $e){
            return response()->json(['status' => 0, 'message' => $e->getMessage(), 'data' => '']);
        }

    }

}