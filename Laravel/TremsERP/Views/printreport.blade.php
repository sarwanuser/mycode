<!DOCTYPE html>
<html>
<head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th,td{
        border: 1px solid #000;
		font-size:9px;
    }
    @page{
        margin: 5px 0 0px;
        size: A4 landscape;
        font-family: arial, sans-serif;
		color:#000;
		margin-top:220px;
    }
    header{
        position: fixed;
        left: 0px;
        right: 0px;
        height: 60px;
        padding-left: 9px;
        padding-right: 9px;
        top : 5px;
        top:-199px;
    }
    main{
        margin-bottom: 50px;
        margin-left: 10px;
        margin-right: 10px;
    }
    .data-table{font-size: 9px;}
    .nborder-l{border-left:0px;}
    .nborder-r{border-right:0px;}
    .nborder-b{border-bottom:0px;}
    .nborder-t{border-top:0px;}
    .nborder{border:0px;}
</style>
</head>
<body>

    <header>
		<table>
			<tr>
				
			<td style='width:200px; padding-left:10px;'><img src="{{$logo}}" alt="Company Logo" width='180px;' height='100px;' /></td>
			<th style=" font-size: 11px; padding:0px; margin:0px;"><b>{{$unit_detail->name}}</b><br>{{$unit_detail->address}}<br>Phone : {{$unit_detail->telephone}}</th>
			<th style="text-align:center; font-size: 13px; padding:0px; margin:0px;">
			{{(($po_hdata1->jo_po =='P') ? 'Purchase Order' : 'Job Work Order' )}}
		</td>
			<td style="padding: 0px; height:100px;">
			<table>
				<tr>
					<td style='font-size:11px; padding-left:5px; border:none; '> <b>GST Reg No.:</b></td>
					<td style='font-size:11px; padding-left:5px; border:none; '> {{$unit_detail->gst_reg_no}}</td>
				</tr>
				<tr>
					<td style='font-size:11px;border:none;padding-left:5px; '><b>Pan No.:</b></td>
					<td style='font-size:11px;border:none;padding-left:5px; '>{{$unit_detail->pan_no}}</td>
				</tr>
				<tr>
					<td style='border:none; padding-left:5px; font-size:11px;'><b>CIN No.:</b></td>
					<td style='border:none; padding-left:5px; font-size:11px;'>{{$unit_detail->cin_no}}</td>
				</tr>
			</table>
			</td>
            </tr>
			<tr>
                <td style=" border-bottom:none;" colspan="2">
                    <table>
                        <tr>
                            <td style='border:none; font-size:10px; width:70px;'><b>Vendor Name:</b></th>
							<td style='border:none; font-size:10px;'>({{$po_hdata1->ven_cd}})  {{@getVendRegNameMaster($po_hdata1->ven_cd)}}</td>
						</tr>
						<tr>
                            <td style='border:none;font-size:10px;height: 50px;'><b>Address:</b></td>
							<td style='border:none;height: 50px;'>{{$po_hdata1->address1}},{{$po_hdata1->city}}<br>{{$po_hdata1->state}}</td>
						</tr>
						<tr>
                            <td style='border:none; font-size:10px;'></td>
							<td style='border:none; font-size:10px;'><b>GSTIN :</b>{{$po_hdata->gst_reg_no}}&nbsp;&nbsp;&nbsp;<b>PAN :</b>{{$po_hdata->pan_no}}</th>
						</tr>
                    </table>
                </td>
				<td style="padding: 0px; border-bottom:none;">
                    <table>
                        <tr>
							<td style='border:none; font-size:10px;'><b>Ship To :</b> <br>({{$po_hdata1->ven_cd}})  {{@getVendRegNameMaster($po_hdata1->ven_cd)}}</td>
						</tr>
						<tr>
							<td style='border:none; font-size:10px; height: 50px;'>{{$po_hdata1->address1}},{{$po_hdata1->city}}<br>{{$po_hdata1->state}}</td>
						</tr>
						<tr>
							<td style='border:none; font-size:10px;'><b>GSTIN :</b>{{$po_hdata->gst_reg_no}}</td>
						</tr>
                    </table>
                </td>
				<td style="padding: 0px; border-bottom:none;">
                    <table>
						<tr>
                            <td style='border:none; font-size:10px;'><b>PO No:</b> {{$po_hdata1->po_no}}</td>
							<td style='border:none; font-size:10px;'><b>PO Date: </b> {{!empty($po_hdata1->po_dt) ? date("d/m/Y",strtotime($po_hdata1->po_dt)) : "" }}</td>
                        </tr>
                        <tr>
                            <td style='border:none; font-size:10px;'><b>Amd. No : </b>{{$po_hdata1->amd_no}}</td>
							<td style='border:none; font-size:10px;'><b>Amd. Date </b>{{!empty($po_hdata1->amd_dt) ? date("d/m/Y",strtotime($po_hdata1->amd_dt)) : "" }}
							</td>
                        </tr>
                        <tr>
							<td style='border:none; font-size:10px;'><b>W.E.F. : </b>{{!empty($po_hdata1->valid_fr) ? date("d/m/Y",strtotime($po_hdata1->valid_fr)) : "" }}</td>
							<td style='border:none; font-size:10px;'><b>Valid Upto:</b> {{!empty($po_hdata1->valid_to) ? date("d/m/Y",strtotime($po_hdata1->valid_to)) : "" }}</td>
                        </tr>
						<tr>
							@if($po_hdata1->po_type2 == 'C')
								<td style="text-align:center; border:none; font-size:10px;" colspan='2'><b>Close PO</b> </td>
							@elseif($po_hdata1->po_type2 == 'O')
								<td style="text-align:center; border:none; font-size:10px;" colspan='2'><b>Open PO</b></td>
							@endif
						</tr>
                    </table>
                </td>   
            </tr>
		</table>
    </header>
    <main>
        <table>
			<thead>
                <tr>
                    <th rowspan="2">S. No.</th>
				    <th rowspan="1" colspan="2">ITEM</th>
				    <th rowspan="2">HSN Code</th>
				    <th rowspan="2">Process</th>
				    <th rowspan="2">UOM</th>
				    <th rowspan="2">QTY</th>
                    <th rowspan="2">Rate /Unit <br>{{@getCurrSign($po_hdata1->cur_cd)}}</th>
                    <th rowspan="2">Desc.(%)</th>
                    <th rowspan="2">Amount </th>
                    <th rowspan="1" colspan="2">CGST</th>
                    <th rowspan="1" colspan="2">SGST</th>
                    <th rowspan="1" colspan="2">IGST</th>
                    <th rowspan="2">Total Amt.</th>
                </tr> 
                <tr>
					<th rowspan="1" colspan="1"> Code</th>
					<th rowspan="1" colspan="1">Description</th>
                    <th rowspan="1" colspan="1">%</th>
                    <th rowspan="1" colspan="1">AMT</th>
                    <th rowspan="1" colspan="1">%</th>
                    <th rowspan="1" colspan="1">AMT</th>
                    <th rowspan="1" colspan="1">%</th>
                    <th rowspan="1" colspan="1">AMT</th>
                </tr>
            </thead>
            <tbody>
            @php $i=1; $total=0; $tax=0; $cgst=0;$sgst=0;$igst=0; @endphp
            @foreach($po_data as $data)
            @php 
				$amount = $data->qty*$data->material_rate;
				$total = $total+$amount;
				$cgst = $cgst+$data->cgst_amt;
				$sgst = $sgst+$data->sgst_amt;
				$igst = $igst+$data->gst_amt;
				$tax = $tax+$data->goods_value;
            @endphp
                <tr>
                    <td style='text-align:center'>{{$i++}}</td>
                    <td>{{$data->pios_item_cd}}</td>
                    <td>{{@getItemDecByCode($data->pios_item_cd)}}</td>
                    <td>{{$data->po_hsn_code}}</td>
					<td>{{$data->long_descrip}}</td>
                    <td>{{$data->uom}}</td>
                    <td style='text-align:right'>{{$data->qty}}</td>
					<td style='text-align:right'>{{number_format($data->material_rate, 2)}}</td>
					<td style='text-align:right'>{{$data->discount_percent}}</td>
					<td style='text-align:right'>{{number_format($amount, 2)}}</td>
                    <td style='text-align:center'>{{$data->cgst_per}}</td>
                    <td style='text-align:right'>{{number_format($data->cgst_amt, 2)}}</td>
                    <td style='text-align:center'>{{$data->sgst_per}}</td>
                    <td style='text-align:right'>{{number_format($data->sgst_amt, 2)}}</td>
                    <td style='text-align:center'>{{$data->gst_percent}}</td>
                    <td style='text-align:right'>{{number_format($data->gst_amt, 2)}}</td>
                    <td style='text-align:right'>{{number_format($data->goods_value, 2)}}</td>     
                </tr> 

            @endforeach 
				<tr>
                    <td style='text-align:center; height:170px;'></td>
                    <td></td>
                    <td></td>
                    <td></td>
					<td></td>
                    <td></td>
                    <td style='text-align:right'></td>
					<td style='text-align:right'></td>
					<td style='text-align:right'></td>
					<td style='text-align:right'></td>
                    <td></td>
                    <td style='text-align:right'></td>
                    <td ></td>
                    <td style='text-align:right'></td>
                    <td></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>     
                </tr>   
            </tbody>
				<tr>
				<td rowspan='2' style='padding-left:4px;' ><b>In Words: </b></td>
				<td rowspan='2' colspan='7' style='padding-left:4px;'><b>{{@numberTowords($tax)}}</b></td>
				<td rowspan='2' ><b>Grand Total</b></td>
				<td rowspan='2' style='text-align:right;;'><b>{{number_format($total, 2)}}</b></td>
				<td rowspan='2' colspan='2' style='text-align:right;'><b>{{number_format($cgst, 2)}}</b></td>
				<td rowspan='2' colspan='2' style='text-align:right;'><b>{{number_format($sgst, 2)}}</b></td>
				<td rowspan='2' colspan='2' style='text-align:right;'><b>{{number_format($igst, 2)}}</b></td>
				<td rowspan='1' style='text-align:right;'><b>{{number_format($tax, 2)}}</b></td>
			</tr>
			<tr>
				<td rowspan='1' >TCS % :</td>
			</tr>
		</table>
			<table>
			<tr>
				<td style="padding: 0px;">
					<table>
						<tr>
							<td style='border:none; width:20px;'><b>Freight</b></td>
							<td style='border:none;'>{{$po_hdata1->freight1}}&nbsp;&nbsp;&nbsp;{{$po_hdata1->freight}}</td>
							<td style='border:none; width:100px;'><b>Packing/Forwarding</b></td>
							<td style='border:none;' >{{$po_hdata1->pf_type}}&nbsp;&nbsp;&nbsp;{{$po_hdata1->pf_charge}}</td>
						</tr>
						@php
							if($po_hdata1->mode_del != ''){
							$modeship = getsecvalue($po_hdata1->mode_del,'SHIP_MODE');
							}else{
								$modeship = '';
							}                                
                 @endphp
						<tr>
							<td style='border:none;'><b>Insurance</b></td>
							<td style='border:none;'>{{@getSecContDes('INS_CD',$po_hdata1->ins_cd)}}&nbsp;&nbsp;&nbsp;{{$po_hdata1->ins_amt}}</td>
							<td style='border:none;'><b>Delivery Type</b></td>
							<td style='border:none;'></td>
							<td style='border:none; width:100px;'><b>Dispatch Mode</b></td>
							<td style='border:none;'>{{$modeship}}</td>
						</tr>
						<tr>
							<td style='border:none; width:90px;'><b>Payment Term</b></td>
							<td style='border:none;' >After {{$po_hdata1->pay_term}} Days</td>
						</tr>
					</table>
				</td>
				<td  >
				</td>
			</tr>
			<tr>
				<td ><b>Remarks </b> {{$po_hdata1->remarks}}</td>
				<td style="text-align:right;"><b>Grand Total:</b> {{number_format($tax, 2)}}</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>{{@!empty($po_hdata1->prep_by) ? getUserDetailsByEmpCode($po_hdata1->prep_by)['user_name'] : ''}}<br><br>Prepared By</td>
				<td>{{@!empty($po_hdata1->chck_by) ? getUserDetailsByEmpCode($po_hdata1->chck_by)['user_name'] : ''}}<br><br>Checked By</td>
				<td>{{@!empty($po_hdata1->appr_by) ? getUserDetailsByEmpCode($po_hdata1->appr_by)['user_name'] : ''}}<br><br>Verified By</td>
				<td>For {{$unit_detail->name}}<br><br>Authorised Signatory</td>
			</tr>
		</table>
		<table>
			<tr>
				<td style='border:none;'><p>Note * New item in the amd. Po., In case of Rate Amd. Old Rate is mentioned in the ( )</p>
					<p>Po No. must be mentioned in your challan / bill.</p>
				</td>
				<td style='border:none;' style="text-align:right;">Page 1 of 1</td>
			</tr>
		</table>
</table>
</main>
</body>
</html>
