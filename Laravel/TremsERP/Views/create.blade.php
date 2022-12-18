@extends('template.base')

@section('title', 'MIL - TERMS Master')

@section('styles')
<style>
	.flexigrid div.form-div{font-size:11px;}
	.no-padding{padding:0px !important;}
	ul.action_buts_main {
		float: left;
	}
	.item_total td{
		background-color: #d9ebf5 !important;
    	padding: 0 5px !important;
	}
	.item_rate_history td {
		background-color: #fff;
	}

	/** Sticky column */
	.sticky_table {
		position: relative;
		overflow: auto;
		border: 1px solid black;
		white-space: nowrap;
	}

	.sticky-col {
		position: -webkit-sticky;
		position: sticky;
		background-color: white;
		z-index: 99;
	}

	.first-col {
		width: 120px;
		min-width: 120px;
		max-width: 120px;
		left: -2px;
	}

	.second-col {
		width: 100px;
		min-width: 100px;
		max-width: 100px;
		left: 117px;
	}
	.third-col {
		width: 170px;
		min-width: 170px;
		max-width: 170px;
		left: 210px;
	}

	div#main-table-box {
		overflow: auto !important;
		max-height: 450px !important;
	}

	/** Stiky column */

	#main-table-box table th {
		position: -webkit-sticky; 
		position: sticky;
		top: -5px;
		z-index: 10; 
		background: #fafafa url(images/fhbg.gif) repeat-x bottom;
	}
</style>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content main  -->
    <section class="content-header" style="margin-bottom: 80px;">
	<form method="POST" enctype="multipart/form-data" id="create_po">
	<input type="hidden" name="_ts" value="{{@$_REQUEST['_ts']}}" />
@csrf
		<!-- Top Bar Section -->
		<div class="row">
			<div class="col-md-4 col-sm-12 m-t-10">
				<h4 style="margin: 0;">Create Purchase Order</h4>  
			</div>
			<div class="col-md-8 col-sm-12 m-t-10">
				<ul class="action_buts_main right">
					<li style="margin:0 0px 0;"> 
						<button type="button" class="flx_btn"> <a href="" onclick="return confirm('Do You Want To Cancel the PO?')" class="disabled">Cancel PO </a></button> 
					</li>
					<li style="margin:0 0px 0;">
						<button type="button" data-toggle="modal" data-target="#annexure_modal" class="flx_btn disabled ven_code_loc_en"> Annexure </button> 

						<!-- Term Condition Modal -->
						<div id="annexure_modal" class="modal bd-example-modal-lg fade show" role="dialog" style="margin: 0 auto;">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
							<div class="modal-body">
								<div class="row">
								<div class="flexigrid col-md-12 no-padding" style='width: 100%;'>
									<div class="mDiv">
										<div class="ftitle">
											<ul class="action_buts_main m-0 inline p-0">
												<li>
												<a href="javascript:void(0);" onclick="addAnnexure();" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1" class='plane_table'>					
												<thead>
													<tr class="hDiv">
														<th>
															<div class="text-left field-sorting" rel="account_title">
																Sr No.					
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Annexure Type
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Subject
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Remarks
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Cancel
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Action
															</div>
														</th>
													</tr>
												</thead>
												<tbody id="annexure_detail">

												</tbody>
											</table>
										</div>
									</div>
								</div>      		
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" data-dismiss="modal" type="button">OK</button>
										<button class="flx_btn" data-dismiss="modal" type="button">Cancel</button>
									</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						<!-- Term Condition Modal -->
					</li>
					<li style="margin:0 0px 0;">
						<button type="button" class="flx_btn disabled ven_code_loc_en" onclick="openPaymentTerms();" > Payment Terms </button>

						<!-- Payment Terms Modal -->
						<div id="payment_terms_modal" class="modal bd-example-modal-lg fade show" role="dialog" style="margin: 0 auto;">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
							<div class="modal-body">
								<div class="row">
								<div class="flexigrid col-md-12 no-padding" style='width: 100%;'>
									<div class="mDiv">
										<div class="ftitle">
											<ul class="action_buts_main m-0 inline p-0">
												<li>
												<a href="javascript:void(0);" onclick="addPaymentTerm();" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1" class='plane_table'>					
												<thead>
													<tr class="hDiv">
														<th>
															<div class="text-left field-sorting" rel="account_title">
																Payment Term				
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Payment Term Name
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																No Of Days
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Percentage
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Remarks
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Action
															</div>
														</th>
													</tr>
												</thead>
												<tbody id="payment_terms_detail">

												</tbody>
											</table>
										</div>
									</div>
								</div>      		
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<span class="pay_terms_per_error" style="color:red; font-size:10px; display:none;">Total Percentage is must be 100 % only</span><br>
										<button class="flx_btn" onclick="closePaymentTerms();" type="button">OK</button>
										<button class="flx_btn" onclick="closePaymentTerms();" type="button">Cancel</button>
									</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						<!-- Payment Terms Modal -->
					</li>
					<li style="margin:0 0px 0;">
						<button type="button" class="flx_btn disabled ven_code_loc_en" data-toggle="modal" data-target="#calculation_modal"> Calculation </button>

						<!-- Calculation Modal -->
						<div id="calculation_modal" class="modal bd-example-modal-lg fade show" role="dialog" style="margin: 0 auto;">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
							<div class="modal-body">
								<div class="row">
								<div class="flexigrid col-md-12 no-padding" style='width: 100%;'>
									<div class="mDiv">
										<div class="ftitle">
											<!--<ul class="action_buts_main m-0 inline p-0">
												<li>
												<a href="javascript:void(0);" onclick="addcalculation();" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>-->
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1" class='plane_table'>					
												<thead>
													<tr class="hDiv">
														<th>
															<div class="text-left field-sorting" rel="account_title">
																Sr No.					
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Head
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Operator
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																GST
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Unit
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Based On
															</div>
														</th>
														<th>
															<div class="text-left field-sorting">
																Amount
															</div>
														</th>
													</tr>
												</thead>
												<tbody id="calculation_detail">
													<tr id="" class="calculationRows">
														<td>
															<input id="sno" name="po_calc[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="1" readonly/>
														</td>    	
														<td style="text-align:center;">
															<select class="cinput_field disabled" id="head" name="po_calc[head][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
																@endphp
																@foreach($po_cal_heads as $po_cal_head)
																	<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == 'BASIC') {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="oper" name="po_calc[oper][]" style="width:120px;">
																<option value="P">+</option>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" name="po_calc[cal_on][]" value="Y" checked/>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="unit" name="po_calc[unit][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
																@endphp
																@foreach($po_cal_units as $po_cal_unit)
																	<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == 'AMT') {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="based_on" name="po_calc[based_on][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_basedons = getSecContDesByType('PO_CALCULATION_BASED_ON');
																@endphp
																@foreach($po_cal_basedons as $po_cal_basedon)
																	<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == 'ONT') {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td>
														<input id="cal_basic_amount" name="po_calc[amount][]" type="text" class="item_cinput_field" style="width:120px;" readonly="">
														</td>
													</tr>

													<tr id="" class="calculationRows">
														<td>
															<input id="sr_no_2" name="po_calc[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="2" readonly/>
														</td>    	
														<td style="text-align:center;">
															<select class="cinput_field disabled" id="head" name="po_calc[head][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
																@endphp
																@foreach($po_cal_heads as $po_cal_head)
																	<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == 'SGST') {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="oper" name="po_calc[oper][]" style="width:120px;">
																<option value="">+</option>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" name="po_calc[cal_on][]" value="Y" checked/>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="unit" name="po_calc[unit][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
																@endphp
																@foreach($po_cal_units as $po_cal_unit)
																	<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == 'AMT') {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="based_on" name="po_calc[based_on][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_basedons = getSecContDesByType('PO_CALCULATION_BASED_ON');
																@endphp
																@foreach($po_cal_basedons as $po_cal_basedon)
																	<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == 'ONT') {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td>
														<input id="cal_sgst_amount" name="po_calc[amount][]" type="text" class="item_cinput_field" style="width:120px;" readonly="">
														</td>
													</tr>

													<tr id="" class="calculationRows">
														<td>
															<input id="sr_no_3" name="po_calc[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="3" readonly/>
														</td>    	
														<td style="text-align:center;">
															<select class="cinput_field disabled" id="head" name="po_calc[head][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
																@endphp
																@foreach($po_cal_heads as $po_cal_head)
																	<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == 'CGST') {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="oper" name="po_calc[oper][]" style="width:120px;">
																<option value="">+</option>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" name="po_calc[cal_on][]" value="Y" checked/>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="unit" name="po_calc[unit][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
																@endphp
																@foreach($po_cal_units as $po_cal_unit)
																	<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == 'AMT') {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="based_on" name="po_calc[based_on][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_basedons = getSecContDesByType('PO_CALCULATION_BASED_ON');
																@endphp
																@foreach($po_cal_basedons as $po_cal_basedon)
																	<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == 'ONT') {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td>
														<input id="cal_cgst_amount" name="po_calc[amount][]" type="text" class="item_cinput_field" style="width:120px;" readonly="">
														</td>
													</tr>

													<tr id="" class="calculationRows">
														<td>
															<input id="sr_no_4" name="po_calc[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="4" readonly/>
														</td>    	
														<td style="text-align:center;">
															<select class="cinput_field disabled" id="head" name="po_calc[head][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
																@endphp
																@foreach($po_cal_heads as $po_cal_head)
																	<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == 'IGST') {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="oper" name="po_calc[oper][]" style="width:120px;">
																<option value="">+</option>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" name="po_calc[cal_on][]" value="Y" checked/>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="unit" name="po_calc[unit][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
																@endphp
																@foreach($po_cal_units as $po_cal_unit)
																	<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == 'AMT') {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="based_on" name="po_calc[based_on][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_basedons = getSecContDesByType('PO_CALCULATION_BASED_ON');
																@endphp
																@foreach($po_cal_basedons as $po_cal_basedon)
																	<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == 'ONT') {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td>
														<input id="cal_igst_amount" name="po_calc[amount][]" type="text" class="item_cinput_field" style="width:120px;" readonly="">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>      		
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" data-dismiss="modal" type="button">OK</button>
										<button class="flx_btn" data-dismiss="modal" type="button">Cancel</button>
									</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						<!-- Calculation Modal -->
					</li>
					<li style="margin:0 0px 0;">
						<button type="button" class="flx_btn ven_code_loc_en disabled" data-toggle="modal" data-target="#item_details_modal" data-backdrop="static" data-keyboard="false" onclick="addPOItemDetailsOnlyFirstT();"> Item Details </button>

						<!-- Item Details Modal -->
						<div id="item_details_modal" class="modal fade show" role="dialog" style="margin: 0 auto;">
						<div class="modal-dialog modal-xl" style="min-height: 500px;">
							<div class="modal-content">
							<div class="modal-body">
								<div class="row">
								<div class="flexigrid col-md-12 no-padding" style='width: 100%;'>
									<div class="mDiv">
										<div class="ftitle">
											<ul class="action_buts_main m-0 inline p-0">
												<li>
												<a href="javascript:void(0);" onclick="addMorePOItem();" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1" class='plane_table sticky_table'>					
												<thead>
													<tr>
														<th colspan="2" rowspan="1"></th>
													</tr>
													<tr class="hDiv">
														<th colspan="1" rowspan="2" class="sticky-col first-col">
															<div class="text-left field-sorting" rel="account_title">
																Indent No.					
															</div>
														</th>
														<th colspan="1" rowspan="2" class="sticky-col second-col">
															<div class="text-left field-sorting">
																Item Code
															</div>
														</th>
														<th colspan="1" rowspan="2" class="sticky-col third-col">
															<div class="text-left field-sorting">
																Item Name
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Make By
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting" title="Specification">
																Specif
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Rate History
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Quotation Reference
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																UOM
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Process Sequence
															</div>
														</th>
														<th colspan="1" rowspan="2"> 
															<div class="text-left field-sorting">
																Process Code
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Process Description
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Weight
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Quantity
															</div>
														</th>
														<th colspan="1" rowspan="1">
															Material Rate
														</th>
														<th colspan="1" rowspan="1">
															Unit
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Discount %
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Discount Amount
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Amount
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Gst Code
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																SGST %
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																SGST Amount
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																CGST %
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Cgst Amount
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Igst %
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Igst Amount
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																HSN Code
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Goods Value
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Others
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Order Cost
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Required Delivery Date
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Tolerance %
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Remarks
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Cancel
															</div>
														</th>
														
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Action
															</div>
														</th>
													</tr>
												</thead>
												<tbody id="po_item_detail_main"></tbody>
												<tfoot class="item_total">
													<td>Total: </td>
													<td colspan="16"></td>
													<td><span id="amt_total">0</span></td>
													<td></td>
													<td><span id="sgst_per_max">0</span></td>
													<td><span id="sgst_amt_total">0</span></td>
													<td><span id="cgst_per_max">0</span></td>
													<td><span id="cgst_amt_total">0</span></td>
													<td><span id="gst_per_max">0</span></td>
													<td><span id="gst_amt_total">0</span></td>
													<td></td>
													<td><span id="po_indent_item_total">0</span></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tfoot>
											</table>
										</div>
									</div>
								</div>      		
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" onclick="closeItemDetails();" type="button">OK</button>
										<button class="flx_btn" data-dismiss="modal" type="button">Cancel</button>
									</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						<!-- Item Details Modal -->
					</li>
					<!--<li>
						<a href="#" title="Edit"><img src="{{ URL::asset('assets/icons/edit.png') }}"/></a>
					</li>-->
					<li>
						<button type="submit" class="submit_button"> 
							<img src="{{ URL::asset('assets/icons/save.png') }}"/>
						</button>
					</li>
					<!--<li>
						<a href="#" title="Save And Close"><img src="{{ URL::asset('assets/icons/save_close.png') }}"/></a>
					</li>-->
					<li>
						<a href="{{url('/purchaseorder?_ts='.@$_REQUEST['_ts'])}}" title="Cancel"><img src="{{ URL::asset('assets/icons/cancel.png') }}"/></a>
					</li>
				</ul>
			</div>
		</div>
		<!-- /Top Bar Section -->
			
		<!-- Form Details Section -->
		<div class="row">
			<div class="span12 columns col-md-12">
				<div class="well">
					<div class="flexigrid crud-form" style='width: 100%;'>
						<div class="mDiv">
							<div class="ftitle">
								<div class='ftitle-left'>
									Add Purchase Order		
								</div>
								<div class='clear'></div>
							</div>
						</div>
						<div id='main-table-box'>
							<div class='form-div cformdiv'>
								<div class="row">
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Type	</lable>
										<select class="cinput_field" id="jo_po" name="jo_po" onchange="POTypeVailidation();">
											@php
												$potheads = getSecContDesByType('PO_HEAD');
											@endphp
											@foreach($potheads as $pothead)
												<option value="{{ $pothead->control_code }}" @if($pothead->control_code == 'P') {{ 'selected' }} @endif>{{ $pothead->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error jo_po"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Category</lable>
										<select class="cinput_field" id="po_type" name="po_type" onchange="POCatVailidation();">
											@php
												$potypes = getSecContDesByType('PO_TYPE');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == 'IN') {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_type"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Unit Code</lable>
										<input type="text" disabled class="cinput_field" name="unit_cd" id="unit_cd" value="{{ @$user['unitcode'] }}"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Unit Name</lable>
										<input type="text" class="cinput_field" value="{{ @$user['unitname'] }}" readOnly/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO NO.</lable>
										<input type="text" id="po_no" class="cinput_field" name="po_no" readOnly/>
										<span class="validate_error group_code"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Date	</lable>
										<input type="date" class="cinput_field disabled" value="{{ date('Y-m-d') }}" name="po_dt" id="po_dt" tabindex="-1" readyOnly/>
									</div>
									
									<div class="col-sm-12 col-md-3 cfieldmain">
										<lable class="cinput_lable">Open/Close</lable>
										<select class="cinput_field" id="po_type2" name="po_type2" onchange="clearItemDetails();">
											@php
												$potypes = getSecContDesByType('PO_TYPE2');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == 'C') {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_type2"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Type	</lable>
										<select class="cinput_field" id="po_unit_type" name="po_unit_type">
											@php
												$potypes = getSecContDesByType('PO_UNIT_TYPE');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == 'U') {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_unit_type"></span>
									</div>
									
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amendment No</lable>
										<input type="text" readOnly class="cinput_field" name="amd_no" value="0"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amendment Date</lable>
										<input type="date" class="cinput_field disabled" value="" name="amd_dt" readOnly/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amend With Effect Of</lable>
										<input type="date" class="cinput_field disabled" id="amd_wef" name="amd_wef" readOnly/>
										
										<span class="validate_error group_code"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Status</lable>
										<select class="cinput_field disabled" id="po_status" name="po_status">
											<option value=""></option>
											@php
												$potypes = getSecContDesByType('PO_STATUS');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == 'Y') {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_status"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable lable-width-38">PO Value</lable>
										<input type="text" class="cinput_field" name="po_value" id="po_value" value="0" style="float:left;" readOnly/>
										<img src="/assets/icons/btn_loader.gif" class="input_loader po_value_load" style="display: none;">
									</div>
									
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Valid From</lable>
										<input type="date" class="cinput_field" value="{{ date('Y-m-d') }}" onchange="VailidateFromToDate()" name="valid_fr" id="valid_fr"/>
										<span class="validate_error valid_fr"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Valid To</lable>
										<input type="date" class="cinput_field"  value="{{date('Y-m-d', strtotime('+180 days'))}}" onchange="VailidateFromToDate()" name="valid_to" id="valid_to"/>
										<span class="validate_error valid_to"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Location</lable>
										<input type="text" id="TSO6_location_locat_code" class="cinput_field tbl_select" name="cost_centre" onclick="getTableSelectData('TSO6','','location', 'locat_code,long_desc', 'locat_code,long_desc','75','678','TSO6_location_locat_code',{'unit_code':'{{ @$user['unitcode']}}'},{},{},{},'','','','long_desc##ASC');" autocomplete="off"/>
										<img src="/assets/icons/btn_loader.gif" class="input_loader TSO6_tbl_select_loader" style="display: none;">
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Location Description</lable>
										<input type="text" id="TSO6_location_long_desc" class="cinput_field" name="group_code" disabled />
									</div>
								</div>
								<!-- Start Tab Area -->
								<div class="card formTabs">
									<div class="card-header bg-white">
										<ul class="nav nav-tabs card-header-tabs float-left">
											<li class="nav-item">
												<a class="nav-link active" href="#vendor_details" data-toggle="tab">Vendor Details</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#others" data-toggle="tab">Others</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#refe_doc" data-toggle="tab">Reference Document</a>
											</li>
										</ul>
									</div>
									<div class="card-body">
										<div class="tab-content text-justify">
											<div class="tab-pane active" id="vendor_details">
												<div class="row">
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Ship To</lable>
														<input type="text" id="TSO1_vendor_master_view_vendor_code" class="cinput_field tbl_select" name="ven_cd" onclick="getTableSelectData('TSO1','','vendor_master_view', 'vendor_code,name,state,city,address', 'vendor_code,name,state,city,address','125','432','TSO1_vendor_master_view_vendor_code',{'unit_code':'{{ @$user['unitcode']}}', 'vendor_status':'{{'OK'}}'}, {},{},{},'getPayTermByVenCode','','','name##ASC');" autocomplete="off"/>
														<!--<span class="reload_page" onclick="location.reload();"><i class="side-menu__icon fa fa-refresh"></i></span>-->
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-6 cfieldmain">
														<lable class="cinput_lable lable-width-25">Vendor Name</lable>
														<input type="text" disabled class="cinput_field enput-width-74" id="TSO1_vendor_master_view_name"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor City</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_city"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor State</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_state"/>
													</div>
													<div class="col-sm-12 col-md-9 cfieldmain">
														<lable class="cinput_lable lable-width-11">Vendor Address</lable>
														<input type="text" disabled class="cinput_field enput-width-83" id="TSO1_vendor_master_view_address"/>
													</div>

													<!-- Ship To -->
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Bill To</lable>
														<input type="text" id="TSO10_vendor_master_view_vendor_code" class="cinput_field tbl_select" name="ship_to" onclick="getTableSelectData('TSO10','','vendor_master_view', 'vendor_code,name,state,city,address', 'vendor_code,name,state,city,address','125','432','TSO10_vendor_master_view_vendor_code',{'unit_code':'{{ @$user['unitcode']}}', 'vendor_status':'{{'OK'}}'}, {},{},{},'','','','name##ASC');" autocomplete="off"/>
														<!--<span class="reload_page" onclick="location.reload();"><i class="side-menu__icon fa fa-refresh"></i></span>-->
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO10_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-6 cfieldmain">
														<lable class="cinput_lable lable-width-25">Vendor Name</lable>
														<input type="text" disabled class="cinput_field enput-width-74" id="TSO10_vendor_master_view_name"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor City</lable>
														<input type="text" disabled class="cinput_field" id="TSO10_vendor_master_view_city"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor State</lable>
														<input type="text" disabled class="cinput_field" id="TSO10_vendor_master_view_state"/>
													</div>
													<div class="col-sm-12 col-md-9 cfieldmain">
														<lable class="cinput_lable lable-width-11">Vendor Address</lable>
														<input type="text" disabled class="cinput_field enput-width-83" id="TSO10_vendor_master_view_address"/>
													</div>
													<!-- /Ship To -->
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor Type</lable>
														<input type="text" class="cinput_field disabled" id="sales_tax_type" name="sales_tax_type" readOnly/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Destination	</lable>
														<input type="text" class="cinput_field" name="desti" value="{{ getUnitShortName() }}" t-maxc='50'/>
														<span class="validate_error desti"></span>
													</div>

													<!--<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Reference PO</lable>
														<input type="text" id="TSO8_po_head_po_no" class="cinput_field tbl_select" name="group_code" onclick="getTableSelectData('TSO8','','po_head', 'po_no,amd_no', 'po_no','125','725','TSO8_po_head_po_no',{'unit_cd':'{{ @$user['unitcode']}}'});" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO8_tbl_select_loader" style="display: none;">
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<button type="button" class="flx_btn"> Populate PO Detail </button>
													</div>-->

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable lable-width-36">Dispatch Mode</lable>
														<select class="cinput_field" id="mode_del" name="mode_del">
															@php
																$base_ons = getSecContDesByType('SHIP_MODE');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == 'ROD') {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Currency	</lable>
														<select class="cinput_field" id="cur_cd" name="cur_cd">
															@php
																$currencys = getTableDatas('currency');
															@endphp
															@foreach($currencys as $currency)
																<option value="{{ $currency->currency_code }}" @if($currency->currency_code == 'INR') {{ 'selected' }} @endif>{{ $currency->currency_desc }}</option>
															@endforeach
														</select>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">P/F Type	</lable>
														<select class="cinput_field" id="pf_type" name="pf_type" onchange="calculateTotalAmounts();">
															<option value=""></option>
															@php
																$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
															@endphp
															@foreach($po_cal_units as $po_cal_unit)
																<option value="{{ $po_cal_unit->control_code }}">{{ $po_cal_unit->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error pf_type"></span>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">P/F Charge	</lable>
														<input type="number" class="cinput_field" name="pf_charge" id="pf_charge" onblur="calculateTotalAmounts();" value="0" step="any" t-maxc='18'/>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">TCS%	</lable>
														<input type="number" class="cinput_field" name="tot_per" id="tot_per" onblur="calculateTotalAmounts();" value="" step="any" t-maxc='3' onkeyup="notZeroVallue(this)"/>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Insurance</lable>
														<select class="cinput_field" id="ins_cd" name="ins_cd">
															<option value=""></option>
															@php
																$ins_cds = getSecContDesByType('INS_CD');
															@endphp
															@foreach($ins_cds as $ins_cd)
																<option value="{{ $ins_cd->control_code }}">{{ $ins_cd->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error ins_cd"></span>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Insurance Amount	</lable>
														<input type="number" class="cinput_field" name="ins_amt" id="ins_amt" onblur="calculateTotalAmounts();" t-maxc='18' value="0" step="any"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Freight By	</lable>
														<select class="cinput_field" id="freight1" name="freight1">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('FREIGHT1');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == 'Paid') {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Freight Amount</lable>
														<input type="number" class="cinput_field" id="freight_amt" name="freight" value="" onkeyup="notZeroVallue(this)" onblur="calculateTotalAmounts();" t-maxc='18'/> 
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Save Max GST%</lable>
														<input type="text" class="cinput_field" id="gst_rate" name="gst_rate" value="0" readOnly/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Delivery Term	</lable>
														<select class="cinput_field" id="del_term" name="del_term">
															<option value=""></option>
															@php
																$del_terms = getSecContDesByType('DEL_TERM');
															@endphp
															@foreach($del_terms as $del_term)
																<option value="{{ $del_term->control_code }}" @if($del_term->control_code == 'EXW') {{ 'selected' }} @endif>{{ $del_term->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error del_term"></span>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Price Basis</lable>
														<select class="cinput_field" id="price_basis" name="price_basis">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('PRICE_BASIS_PO');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}">{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Payment Type</lable>
														<select class="cinput_field" id="pay_type" name="pay_type">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('PAY_TYPE_PO');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == 'LC') {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>													
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Based On</lable>
														<select class="cinput_field" id="credit_condition" name="credit_condition">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('CREDIT_CONDITION');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" selected>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error base_on"></span>
													</div>
													<div class="col-sm-12 col-md-3 cfieldmain">
														<lable class="cinput_lable">Transporter</lable>
														<input type="text" id="TSO11_vendor_registered_vendor_code" class="cinput_field tbl_select" name="transporter" onclick="getTableSelectData('TSO11','','vendor_registered', 'vendor_code,name', 'vendor_code,name','132','548','TSO11_vendor_registered_vendor_code',{'unit_code':'{{ @$user['unitcode']}}', 'vendor_type':'R', 'vendor_status':'OK'});" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO11_tbl_select_loader" style="display: none;">
														<span class="validate_error transporter"></span>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Transporter Name</lable>
														
														<input type="text" id="TSO11_vendor_registered_name" class="cinput_field" readOnly/>
													</div>
													
												</div>
											</div>
											<div class="tab-pane" id="others">
											<div class="row">
													
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Project/ Job No.</lable>
														<input type="text" id="TSO1_tr_hrecpt_entry_no" class="cinput_field tbl_select" name="reference" onclick="getTableSelectData('TSO1','','tr_hrecpt', 'entry_no,entry_dt,doc_no,doc_dt', 'entry_no,doc_no','125','722','TSO1_tr_hrecpt_entry_no',{'status':'V', 'unit_cd':'{{ @$user['unitcode']}}'});" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
													</div>
													<!--<div class="col-sm-6 col-md-5 cfieldmain">
														<lable class="cinput_lable">Tolerance+(%) (For Receiving)	</lable>
														<input type="number" class="cinput_field" name="tot_per"/>
														
														<span class="validate_error tot_per"></span>
													</div>-->

													<div class="col-sm-12 col-md-5 cfieldmain">
														<lable class="cinput_lable">Tolerance Remark	</lable>
														<textarea class="cinput_field" name="tolerance_remark"></textarea>
														
														<span class="validate_error sub_grp_cd"></span>
													</div>

													<div class="col-sm-12 col-md-4 cfieldmain">
														<lable class="cinput_lable">Remarks	</lable>

														<textarea class="cinput_field" name="remarks"></textarea>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Prepared By	</lable>
														<input type="text" id="TSO12_emp_master_hd_emp_number" class="cinput_field" name="prep_by" autocomplete="off" value="{{ @$user['emp_id'] }}" readOnly/>										
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Prepared By Name	</lable>
														<input type="text" class="cinput_field" id="TSO12_emp_master_hd_emp_first_name" value="{{@$user['name']}}" readOnly/>
													</div>
													<div class="col-sm-12 col-md-4 cfieldmain">
														<lable class="cinput_lable ">Quotation No.	</lable>
														<input type="text" class="cinput_field" name="quotation_no"/>
														<span class="validate_error quotation_no"></span>
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked By	</lable>
														<input type="text" id="TSO13_emp_master_hd_emp_number" class="cinput_field tbl_select disabled" name="chck_by" onclick="getTableSelectData('TSO13','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','550','349','TSO13_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'] }}'});" autocomplete="off" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO13_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked By Name		</lable>
														<input type="text" class="cinput_field disabled" id="TSO13_emp_master_hd_emp_first_name" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked Date	</lable>
														<input type="date" class="cinput_field" name="chck_dt" value="" readOnly/>
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified By	</lable>
														<input type="text" id="TSO14_emp_master_hd_emp_number" class="cinput_field tbl_select disabled" name="appr_by" onclick="getTableSelectData('TSO14','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','590','349','TSO14_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'] }}'});" autocomplete="off" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO14_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified By Name	</lable>
														<input type="text" class="cinput_field" id="TSO14_emp_master_hd_emp_first_name" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified Date		</lable>
														<input type="date" class="cinput_field" name="appr_dt" value="" readOnly/>
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved By	</lable>
														<input type="text" id="TSO15_emp_master_hd_emp_number" class="cinput_field tbl_select disabled" name="frez_by" onclick="getTableSelectData('TSO15','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','630','349','TSO15_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'] }}'});" autocomplete="off" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO15_tbl_select_loader" style="display: none;">
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved By Name	</lable>
														<input type="text" class="cinput_field"  id="TSO15_emp_master_hd_emp_first_name" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved Date	</lable>
														<input type="date" class="cinput_field" name="frez_dt" value="" readOnly/>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="refe_doc">
												<div class="row">
													<div class="bDiv flexigrid_table_res" >
														<div class="mDiv">
															<div class="ftitle">
																Add Item Details 
																<ul class="action_buts_main m-0 inline p-0">
																	<li>
																	<a href="javascript:void(0);" onclick="return addMorePODoc();" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
																	</li>
																</ul>
															</div>
															<div title="Minimize/Maximize" class="ptogtitle">
																<span></span>
															</div>
														</div>
														<table id="flex1" class='plane_table'>
															<thead>
																<tr class="hDiv">
																	<th>
																		<div title="Document Sequence No" class="text-left field-sorting" rel="account_title">
																			S.No					
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
																		<div class="text-left field-sorting " rel="address">
																			Reference Document No				
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Reference Document Type					
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Document File Name					
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Reference Document Date					
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Remarks					
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Download				
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting " rel="address">
																			Remove		
																		</div>
																	</th>
																</tr>
															</thead>
															<tbody id="po_doc_details">
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End Tab Area -->
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<!-- /Form Details Section -->


		         <!-- Table Select Option Popup -->
<div class="tbl_select_option_p tbl_select_option_TSO4">
    <div class="search_area">
        <header class="tbl_select_search_head_TSO4">Batch Doc Iss</header>
        <input type="text" value="" class="search_input tbl_select_search_TSO5 cinput_field" placeholder="Search Item"/>
        <img src="{{ URL::asset('assets/icons/btn_loader.gif') }}" class=" tbl_select_search_loader" style="display: none; width: 20px;">
        <i class="fa fa-times close_tbl_select" aria-hidden="true" onclick="closeTableSelectOptopn();"></i>
    </div>
    <div class="data_tbl_area_TSO4">
        
    </div>
    <div id="tso_pagination_TSO4" class="tso_pagination_style">
        
    </div>
</div>
<!-- /Table Select Option Popup -->

	</form>
	</section>
</div><!-- /.content-wrapper -->
  

@endsection

@section('scripts')
	<script>
		//window.onbeforeunload = function() { return "Are you sure you want to leave?"; }
		
		// Add Annexure
		var AnnexureRowsCount = 1;
		function addAnnexure(){
			$('#annexure_detail').append(`<tr id="annexuretr_${AnnexureRowsCount}" class="termConditionRows">
				<td>
					<input id="sr_no_${AnnexureRowsCount}" name="annexure[s_no][]" type="text" class="item_cinput_field" style="width:75px;" value="${AnnexureRowsCount}" readonly/>
				</td>    	
				<td style="text-align:center;">
					<select class="cinput_field" id="annex_type_${AnnexureRowsCount}" name="annexure[annex_type][]" style="width:120px;">
						<option value=""></option>
						@php
							$annexure_types = getSecContDesByType('ANNEXURE_TYPE');
						@endphp
						@foreach($annexure_types as $annexure_type)
							<option value="{{ $annexure_type->control_code }}">{{ $annexure_type->meaning }}</option>
						@endforeach
					</select>
				</td>
				<td style="text-align:center;" style="width:200px;">
					<textarea id="subject${AnnexureRowsCount}" name="annexure[subject][]" style="width:220px; height:23px;" class="item_cinput_field"></textarea>
				</td>
				<td style="text-align:center;" style="width:200px;">
					<textarea id="sales_desc${AnnexureRowsCount}" name="annexure[sales_desc][]" style="width:220px; height:23px;" class="item_cinput_field"></textarea>
				</td>
				<td style="text-align:center;">
					<input type="checkbox" name="annexure[cancel_ter][]" value="Y"/>
				</td>
				<td style="text-align:center;">
					<a href="javascript:void(0);" onclick="removeAnnexureRow(${AnnexureRowsCount})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
				</td>
			</tr>`); 
			AnnexureRowsCount++;
		}

		function removeAnnexureRow(rowId){
			$("#annexuretr_"+rowId).remove();
			AnnexureRowsCount--;
		}

		// Add Calculation
		var CalculationRowsCount = 5;
		function addcalculation(){
			$('#calculation_detail').append(`<tr id="cal_row_${CalculationRowsCount}" class="calculationRows">
			<td>
				<input id="sr_no_${CalculationRowsCount}" name="calculation[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="${CalculationRowsCount}" readonly/>
			</td>    	
			<td style="text-align:center;">
				<select class="cinput_field" id="annexure_type_${CalculationRowsCount}" name="calculation[head][]" style="width:120px;">
					<option value=""></option>
					@php
						$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
					@endphp
					@foreach($po_cal_heads as $po_cal_head)
						<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == 'IGST') {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
					@endforeach
				</select>
			</td>
			<td style="text-align:center;" style="width:200px;">
				<select class="cinput_field" id="annexure_type_${CalculationRowsCount}" name="calculation[oper][]" style="width:120px;">
					<option value="P">+</option>
				</select>
			</td>
			<td style="text-align:center;">
				<input type="checkbox" checked name="calculation[cal_on][]" value="Y"/>
			</td>
			<td style="text-align:center;" style="width:200px;">
				<select class="cinput_field" id="annexure_type_${CalculationRowsCount}" name="calculation[unit][]" style="width:120px;">
					<option value=""></option>
					@php
						$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
					@endphp
					@foreach($po_cal_units as $po_cal_unit)
						<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == 'AMT') {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
					@endforeach
				</select>
			</td>
			<td style="text-align:center;" style="width:200px;">
				<select class="cinput_field" id="annexure_type_${CalculationRowsCount}" name="calculation[based_on][]" style="width:120px;">
					<option value=""></option>
					@php
						$po_cal_basedons = getSecContDesByType('PO_CALCULATION_BASED_ON');
					@endphp
					@foreach($po_cal_basedons as $po_cal_basedon)
						<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == 'ONT') {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
					@endforeach
				</select>
			</td>
			<td>
			<input id="sr_no_${CalculationRowsCount}" name="calculation[amount][]" type="text" class="item_cinput_field" style="width:120px;" readonly="">
			</td>
			<td style="text-align:center;">
				<a href="javascript:void(0);" onclick="removeCalculationRow(${CalculationRowsCount})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
			</td>
		</tr>`); 
			CalculationRowsCount++;
		}

		function removeCalculationRow(rowId){
			$("#cal_row_"+rowId).remove();
			CalculationRowsCount--;
		}
		

		// Add Payment Term
		var PaymentTermRowsCount = 2;
		var PaymentTermTop = 152;
		var PaymentTermLeft = 197;
		function addPaymentTerm(){
			$('#payment_terms_detail').append(`<tr id="annexuretr_${PaymentTermRowsCount}" class="termConditionRows">
				<td>
					<input type="text" id="TSO${PaymentTermRowsCount}_payment_term_sno" class="cinput_field tbl_select" name="pay_term[pay_cd][]" onclick="getTableSelectData('TSO${PaymentTermRowsCount}','','payment_term', 'sno,pay_term,no_of_days,percent_type', 'pay_term','${PaymentTermTop}','${PaymentTermLeft}','TSO${PaymentTermRowsCount}_payment_term_sno');" autocomplete="off" style="width:120px;" readOnly />
					<img class="TSO${PaymentTermRowsCount}_tbl_select_loader input_loader" style="display: none;" src="/assets/icons/btn_loader.gif"/>
				</td>    	
				<td style="text-align:center;">
					<input type="text" class="cinput_field" id="TSO${PaymentTermRowsCount}_payment_term_pay_term" style="width:120px;" readOnly />
				</td>
				<td style="text-align:center;" style="width:200px;">
					<input type="text" class="cinput_field" id="TSO${PaymentTermRowsCount}_payment_term_no_of_days" name="pay_term[days][]" style="width:120px;" />
				</td>
				<td style="text-align:center;" style="width:200px;">
					<input type="text" class="cinput_field pay_term_percentage" id="TSO${PaymentTermRowsCount}_payment_term_percent_type" name="pay_term[percentage][]" style="width:120px;" />
				</td>
				<td style="text-align:center;">
					<textarea id="terms_description_${PaymentTermRowsCount}" name="pay_term[remarks][]" style="width:220px; height:23px;" class="item_cinput_field"></textarea>
				</td>
				<td style="text-align:center;">
					<a href="javascript:void(0);" onclick="removePaymentTermRow(${PaymentTermRowsCount})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
				</td>
			</tr>`); 
			PaymentTermRowsCount++;
			PaymentTermTop +=40;
		}

		function removePaymentTermRow(rowId){
			$("#annexuretr_"+rowId).remove();
			PaymentTermRowsCount--;
		}
		

		// Add new row
		var rowcount = 1;
		function addMorePODoc(){
    		$('#po_doc_details').append(`<tr class="po_doc_detailRow_${rowcount}">
				<td style="text-align:center;">
					<input type="text" name="doc[doc_sl_no][]" class="item_cinput_field NLW_3D" value="${rowcount}" readOnly/>
				</td>
				<td>
					<input type="text" class="item_cinput_field datepicker NLW_5D" value="{{ @$user['unitcode'] }}" name="doc[unit_cd][]" readOnly/>
				</td>
				<td>
					<input type="text" class="item_cinput_field datepicker" value="{{ @$user['unitname'] }}" readOnly/>
				</td>
				<td style="text-align:center;">
					<input type="text" class="item_cinput_field NLW_8D" value="" id="" name="doc[ref_doc_no][]" readOnly/>
				</td>
				<td style="text-align:center;">
				<input type="text" class="item_cinput_field NLW_10D" value="Purchase Order" name="doc[ref_doc_type][]" readOnly />
				</td>
				<td style="text-align:center;">
					<input type="file" class="item_cinput_field NLW_20D" name="doc[doc_file_name][]" readOnly />
				</td>
				<td style="text-align:center;">
					<input type="text" class="item_cinput_field NLW_10D" value="{{ date('d-m-Y') }}" name="doc[ref_doc_date][]" readOnly />
				</td>
				<td style="text-align:center;">
					<input type="text" class="item_cinput_field" value="" id="doc_remarks_1" name="doc[remarks][]" />
				</td>
				<td style="text-align:center;">
					
				</td>
				<td style="text-align:center;">
					<a href="javascript:void(0);" onclick="removePODoc(${rowcount})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
				</td>
			</tr>`); 
			rowcount++;
    	}

		// Remove Last One Row
		function removePODoc(rowId){
			var rcon = confirm("Do you want to delete this record?");
			if(rcon == true){
				rowcount--;
				$(".po_doc_detailRow_"+rowId).remove();
			}
		}

		// Set the function for get the item by indent
		function setFunForItemByIndent(rowid,indentf=''){
			jQuery("#TSOI1-"+rowid+"_po_indent_pend_view_ind_no").css({"border":"1px solid #555"});
			var indent_no = jQuery("#TSOI1-"+rowid+"_po_indent_pend_view_ind_no").val();
			if(indent_no == ''){
				return false;
			}
			if(indentf == 1){
				jQuery("#TSOI2-"+rowid+"_po_indent_pend_view_item_cd").val('');
			}
			
			var selected_vaues = [];
			jQuery(".po_indent_item_cd").each(function(){
				selected_vaues.push(jQuery(this).val());
			});
			var func = "getTableSelectData('TSOI2-"+rowid+"','','po_indent_item_view', 'item_cd,item_desc,item_type,bal_qty,rec_uom,gst_code,hsn_no,igst,cgst,sgst,proc_seq,proc_cd,rate', 'item_cd,item_type,rec_uom,item_desc,gst_code,hsn_no','54','483','TSOI2-"+rowid+"_po_indent_item_view_item_cd',{'ind_no':'"+indent_no+"'}, {}, {}, {}, 'setItemRelatedVal', '"+selected_vaues+"','item_cd');";
			jQuery(".po_item_with_int_"+rowid).attr("onclick",func);
			jQuery(".po_item_with_int_"+rowid).attr("id","TSOI2-"+rowid+"_po_indent_item_view_item_cd");

			var ven_cd = jQuery("#TSO1_vendor_master_view_vendor_code").val();
			var item_cd = jQuery("#TSOI2-"+rowid+"_item_stock_hsn_view_item_cd").val();
			var func_quo_ref = "getTableSelectData('TSOI7-"+rowid+"','','quot_dtl', 'quot_no', 'quot_no','171','590','TSOI7-"+rowid+"_quot_dtl_quot_no',{'item_code':"+item_cd+"});";
			jQuery("#TSOI7-"+rowid+"_quot_dtl_quot_no").attr("onclick",func_quo_ref);
		}

		// Set others value of iteam related
		function getAllPassValues(item_cd,item_desc,rec_uom,ind_wt,bal_qty,proc_seq,proc_cd,proc_desc,hsn_no,bal_qty,rate,rowid){
			var row_id = rowid.split('-');
			jQuery("#TSOI3-"+row_id[1]+"_process_sheet_view_proc_seq").val(proc_seq);
			jQuery("#TSOI3-"+row_id[1]+"_process_sheet_view_proc_code").val(proc_cd);
			jQuery("#TSOI3-"+row_id[1]+"_process_sheet_view_short_descrip").val(proc_desc);
			jQuery("#TSOI2-"+row_id[1]+"_po_indent_item_view_rec_unit").val(rec_uom);

			// Get item rate
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			jQuery.ajax({
				type: 'get',
				url: baseUrl + '/purchaseorder/itemrate',
				data: {'_ts':_ts, 'item_cd': item_cd, 'proc_seq':proc_seq, 'proc_cd':proc_cd},
				success: function(res) {
					// TSOI2-${rowcount}_po_indent_item_view_rate
					jQuery("#TSOI2-"+row_id[1]+"_po_indent_item_view_rate").val(parseFloat(res));
					jQuery("#po_indent_item_amount_"+row_id[1]).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
					jQuery(".po_indent_item_total_"+row_id[1]).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
					jQuery(".po_indent_item_total_"+row_id[1]).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
				}
			});
		}

		// Calculate Other Charges
		function calculateOtherCharge(){
			var pf_type = jQuery("#pf_type").val();
			var po_value = jQuery("#po_value").val();
			var pf_charge = jQuery("#pf_charge").val();
			var gst_rate = jQuery("#gst_rate").val();
			var ins_amt = jQuery("#ins_amt").val();
			var freight_amt = jQuery("#freight_amt").val();
			var tot_per = jQuery("#tot_per").val();
			jQuery(".po_value_load").show();
			// Get po final value
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			jQuery.ajax({
				type: 'post',
				url: baseUrl + '/purchaseorder/calfinalpoval',
				data: {'_ts':_ts, 'pf_type': pf_type, 'po_value':po_value, 'pf_charge':pf_charge, 'gst_rate':gst_rate, 'ins_amt':ins_amt, 'freight_amt':freight_amt, 'tot_per':tot_per},
				success: function(res) {
					console.log(res);
					jQuery(".po_value_load").hide();
					jQuery("#po_value").val(res.po_value);
				}
			});
			
		}

		// Set total amounts on all rows
		function calculateTotalAmounts(){
			var amt_total = 0;
			var sgst_amt_total = 0;
			var cgst_amt_total = 0;
			var gst_amt_total = 0;
			var po_indent_item_total = 0;
			var uc_amount = 0;
			jQuery(".po_value_load").show();
			// Amount
			jQuery(".uc_amount").each(function(){
				if(jQuery(this).val() != ''){
					amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#amt_total").text(parseFloat(amt_total).toFixed(2));

			// SGST Amount
			jQuery(".sgst_amt").each(function(){
				if(jQuery(this).val() != ''){
					sgst_amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#sgst_amt_total").text(parseFloat(sgst_amt_total).toFixed(2));

			// SGST Max per
			var sgst_per_max = 0;
			jQuery('.sgst_per').each(function() {
				console.log(sgst_per_max+"SGST loop");
				if(parseFloat(sgst_per_max) < parseFloat($(this).val())){
					sgst_per_max = parseFloat($(this).val());
					console.log(sgst_per_max+"SGST if");
				}
			});
			jQuery("#sgst_per_max").text(sgst_per_max);

			// CGST Amount
			jQuery(".cgst_amt").each(function(){
				if(jQuery(this).val() != ''){
					cgst_amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#cgst_amt_total").text(parseFloat(cgst_amt_total).toFixed(2));

			// CGST Max per
			var cgst_per_max = 0;
			jQuery('.cgst_per').each(function() {
				if(parseFloat(cgst_per_max) < parseFloat($(this).val())){
					cgst_per_max = parseFloat($(this).val());
				}
			});
			jQuery("#cgst_per_max").text(parseInt(cgst_per_max));

			// GST Amount
			jQuery(".gst_amt").each(function(){
				if(jQuery(this).val() != ''){
					gst_amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#gst_amt_total").text(parseFloat(gst_amt_total).toFixed(2));

			// GST Max per
			var gst_per_max = 0;
			jQuery('.gst_percent').each(function() {
				if(parseFloat(gst_per_max) < parseFloat($(this).val())){
					gst_per_max = parseFloat($(this).val());
				}
			});
			jQuery("#gst_per_max").text(parseInt(gst_per_max));

			// Item Total Amount
			jQuery(".po_indent_item_total").each(function(){
				if(jQuery(this).val() != ''){
					po_indent_item_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#po_indent_item_total").text(parseFloat(po_indent_item_total).toFixed(2));

			// Basic Amount
			jQuery(".uc_amount").each(function(){
				if(jQuery(this).val() != ''){
					uc_amount += parseFloat(jQuery(this).val());
				}
			});

			// Set calculate popup data
			jQuery("#cal_basic_amount").val(parseFloat(uc_amount).toFixed(2));
			jQuery("#cal_sgst_amount").val(parseFloat(sgst_amt_total).toFixed(2));
			jQuery("#cal_cgst_amount").val(parseFloat(cgst_amt_total).toFixed(2));
			jQuery("#cal_igst_amount").val(parseFloat(gst_amt_total).toFixed(2));

			// Set total po value
			jQuery("#po_value").val(parseFloat(parseFloat(uc_amount)+parseFloat(sgst_amt_total)+parseFloat(cgst_amt_total)+parseFloat(gst_amt_total)).toFixed(2));
			jQuery("#gst_rate").val(parseInt(sgst_per_max)+parseInt(cgst_per_max)+parseInt(gst_per_max));
			jQuery(".po_value_load").hide();
			calculateOtherCharge();
		}

		// Calculate discount of item amount
		function calculateDiscountItemAmount(rowid){
			var qty = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").val();
			var check_qty = jQuery("#bal_qty_check_"+rowid).val();
			if(parseFloat(qty) > parseFloat(check_qty)){
				jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").val(parseFloat(check_qty));
				//jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").focus();
				jQuery("#bal_qty_check_error_"+rowid).show();
				return false;
			}else{
				jQuery("#bal_qty_check_error_"+rowid).hide();
			}
			var rate = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_rate").val();
			var amount = parseFloat(rate)*parseFloat(qty);
			var sales_tax_type = jQuery("#sales_tax_type").val();
			jQuery("#po_indent_item_amount_"+rowid).val(parseFloat(amount));

			var discount = jQuery("#discount_percent_"+rowid).val();
			var discount_amt = parseFloat(amount)*parseFloat(discount)/100;
			jQuery("#discount_amt_"+rowid).val(parseFloat(discount_amt));
			jQuery("#po_indent_item_amount_"+rowid).val(parseFloat(parseFloat(amount)-discount_amt).toFixed(2));
			po_indent_item_total = parseFloat(amount)-discount_amt;
			var gst_code = jQuery("#TSOI5-"+rowid+"_gst_rate_master_gst_code").val();
			if(gst_code != ''){
				// SGST Rate
				var sgst_rate = jQuery("#TSOI5-"+rowid+"_gst_rate_master_sgst_per").val();
				var sgst_amt = po_indent_item_total*parseFloat(sgst_rate)/100;
				jQuery("#sgst_amt_"+rowid).val(parseFloat(sgst_amt).toFixed(2));

				// CGST Rate
				var cgst_rate = jQuery("#TSOI5-"+rowid+"_gst_rate_master_cgst_per").val();
				var cgst_amt = po_indent_item_total*parseFloat(cgst_rate)/100;
				jQuery("#cgst_amt_"+rowid).val(parseFloat(cgst_amt).toFixed(2));

				// GST Rate
				var gst_rate = jQuery("#TSOI5-"+rowid+"_gst_rate_master_gst_rate").val();
				var gst_amt = po_indent_item_total*parseFloat(gst_rate)/100;
				jQuery("#gst_amt_"+rowid).val(parseFloat(gst_amt).toFixed(2));
				if(jQuery.trim(sales_tax_type) == 'V'){
					po_indent_item_total += parseFloat(sgst_amt)+parseFloat(cgst_amt);
				}else if(jQuery.trim(sales_tax_type) == 'C'){
					po_indent_item_total += gst_amt;
				}
			}
			jQuery(".po_indent_item_total_"+rowid).val(parseFloat(po_indent_item_total).toFixed(2));
			jQuery(".po_indent_item_total_"+rowid).val(parseFloat(po_indent_item_total).toFixed(2));

			calculateTotalAmounts();
			itemDetailsVailidation(rowid);
		}

		// Item rate calculation
		function itemGSTRateCalculation(gst_code,gst_desc,sgst_per,cgst_per,gst_rate,rowid){
			var row_id = rowid.split('-');
			row_id = row_id[1];

			// Check Sales Tax Type
			var sales_tax_type = jQuery("#sales_tax_type").val();
			var uc_amount = jQuery("#po_indent_item_amount_"+row_id).val();
			
			// Get item GST rate with amount
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			jQuery.ajax({
				type: 'get',
				url: baseUrl + '/purchaseorder/getgstratetype',
				data: {'_ts':_ts, 'sgst_per': sgst_per, 'cgst_per':cgst_per, 'gst_rate':gst_rate, 'sales_tax_type':sales_tax_type, 'uc_amount':uc_amount},
				success: function(res) {
					jQuery("#TSOI5-"+row_id+"_gst_rate_master_sgst_per").val(res.sgst_per);
					jQuery("#sgst_amt_"+row_id).val(res.sgst_per_amt);

					jQuery("#TSOI5-"+row_id+"_gst_rate_master_cgst_per").val(res.cgst_per);
					jQuery("#cgst_amt_"+row_id).val(res.cgst_per_amt);

					jQuery("#TSOI5-"+row_id+"_gst_rate_master_gst_rate").val(res.gst_rate);
					jQuery("#gst_amt_"+row_id).val(res.gst_rate_amt);
					setTimeout(() => {
						calculateDiscountItemAmount(row_id);
					}, 100);
				}
			});
			
		}

		// Get item rate
		function getItemRate(item_cd, row_id){
			var proc_seq = jQuery("#TSOI3-"+row_id+"_process_sheet_view_proc_seq").val();
			var proc_cd = jQuery("#TSOI3-"+row_id+"_process_sheet_view_proc_code").val();
			// Get item rate
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			jQuery.ajax({
				type: 'get',
				url: baseUrl + '/purchaseorder/itemrate',
				data: {'_ts':_ts, 'item_cd': item_cd, 'proc_seq':proc_seq, 'proc_cd':proc_cd},
				success: function(res) {
					jQuery("#TSOI2-"+row_id+"_po_indent_item_view_rate").val(parseFloat(res));
					jQuery("#po_indent_item_amount_"+row_id).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
					jQuery(".po_indent_item_total_"+row_id).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
					jQuery(".po_indent_item_total_"+row_id).val(parseFloat(parseFloat(res)*parseFloat(bal_qty)).toFixed(2));
				}
			});
		}

		// Set Item related values without indent no
		function setItemRelatedVal(item_cd,item_desc,item_type,bal_qty,rec_uom,gst_code,hsn_no,igst,cgst,sgst,proc_seq,proc_cd,rate,rowid){
			
			var row_id = rowid.split('-');
			row_id = row_id[1];
			
			var pro_seq_f = "getTableSelectData('TSOI3-"+row_id+"','','process_sheet_view', 'proc_seq,proc_code,short_descrip', 'proc_code,short_descrip','171','590','TSOI3-"+row_id+"_process_sheet_view_proc_seq',{'part_code':'"+item_cd+"'});";
			jQuery("#TSOI2-"+row_id+"_po_indent_item_view_rec_uom").val(rec_uom);
			jQuery("#TSOI2-"+row_id+"_po_indent_item_view_rec_unit").val(rec_uom);			
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_gst_code").val(gst_code);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_sgst_per").val(sgst);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_cgst_per").val(cgst);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_gst_rate").val(igst);
			// jQuery("#TSOI2-"+row_id+"_po_indent_item_view_hsn_no").val(hsn_no);
			jQuery("#TSOI6-"+row_id+"_hsn_master_hsn_no").val(hsn_no);
			jQuery("#TSOI2-"+row_id+"_po_indent_item_view_bal_qty").val(bal_qty);
			jQuery("#TSOI2-"+row_id+"_item_stock_hsn_view_item_desc").val(item_desc);
			jQuery("#bal_qty_check_"+row_id).val(bal_qty);
			jQuery("#TSOI3-"+row_id+"_process_sheet_view_proc_seq").attr("onclick",pro_seq_f);
			
			jQuery(".view_bal_qty_"+row_id).val(bal_qty);

			// Set item rate
			//getItemRate(item_cd, row_id);

			itemGSTRateCalculation(gst_code,'gst_desc',sgst,cgst,igst,rowid);
		}

		// Set Item related values direct item select
		function setItemRelatedVal1(item_cd,item_desc,item_type,rec_uom,gst_code,hsn_no,igst,cgst,sgst,rowid){
			var row_id = rowid.split('-');
			row_id = row_id[1];

			var pro_seq_f = "getTableSelectData('TSOI3-"+row_id+"','','process_sheet_view', 'proc_seq,proc_code,short_descrip', 'proc_code,short_descrip','171','590','TSOI3-"+row_id+"_process_sheet_view_proc_seq',{'part_code':'"+item_cd+"'});";
			jQuery("#TSOI2-"+row_id+"_po_indent_item_view_rec_uom").val(rec_uom);
			jQuery("#TSOI2-"+row_id+"_po_indent_item_view_rec_unit").val(rec_uom);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_gst_code").val(gst_code);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_sgst_per").val(sgst);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_cgst_per").val(cgst);
			jQuery("#TSOI5-"+row_id+"_gst_rate_master_gst_rate").val(igst);
			// jQuery("#TSOI2-"+row_id+"_po_indent_item_view_hsn_no").val(hsn_no);
			jQuery("#TSOI6-"+row_id+"_hsn_master_hsn_no").val(hsn_no);
			jQuery("#TSOI2-"+row_id+"_item_stock_hsn_view_item_desc").val(item_desc);
			jQuery("#TSOI3-"+row_id+"_process_sheet_view_proc_seq").attr("onclick",pro_seq_f);

			// Set item rate
			getItemRate(item_cd, row_id);

			itemGSTRateCalculation(gst_code,'gst_desc',sgst,cgst,igst,rowid);
		}

		// Item details vailidation
		function itemDetailsVailidation(rowid){
			var item_cd = jQuery('#TSOI2-'+rowid+'_item_stock_hsn_view_item_cd').val();
			var item_vailidat_status = 0

			if(item_cd == ''){
				jQuery('#TSOI2-'+rowid+'_item_stock_hsn_view_item_cd').css({"border":"1px solid red"});
				jQuery('#TSOI2-'+rowid+'_item_stock_hsn_view_item_cd_error').show();	
				item_vailidat_status = 1

			}
			else{
				jQuery('#TSOI2-'+rowid+'_item_stock_hsn_view_item_cd').css({"border":"1px solid #555"});
				jQuery('#TSOI2-'+rowid+'_item_stock_hsn_view_item_cd_error').hide();
			}

			var item_rate = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_rate").val();
			if(item_rate <= 0){
				//jQuery("#po_indent_item_rate_"+rowid).focus();
				jQuery("#TSOI2-"+rowid+"_po_indent_item_view_rate").css({"border":"1px solid red"});
				jQuery("#po_indent_item_rate_error_"+rowid).show();
				item_vailidat_status = 1
			}else{
				jQuery("#TSOI2-"+rowid+"_po_indent_item_view_rate").css({"border":"1px solid #555"});
				jQuery("#po_indent_item_rate_error_"+rowid).hide();
			}

			var po_type2 = jQuery("#po_type2").val();
			var item_qty = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").val();
			if(jQuery.trim(po_type2) == 'C'){
				if(item_qty <= 0){
					//jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").focus();
					jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").css({"border":"1px solid red"});
					jQuery("#po_indent_item_qty_error_"+rowid).show();
					item_vailidat_status = 1
				}else{
					jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").css({"border":"1px solid #555"});
					jQuery("#po_indent_item_qty_error_"+rowid).hide();
				}
			}
			

			return item_vailidat_status;
		}

		// This function use for add one row only first time
		function addPOItemDetailsOnlyFirstT(){
			var item_details_html = jQuery("#po_item_detail_main").html();
			if(item_details_html == ''){
				addMorePOItem();
			}
		}

		// PROCESS SEQUENCE MUST BE GREATER THAN 0 IN CASE OF PO CATEGORY IS JOBWORK  AND PO TYPE IS JOBWORK ORDER
		function checkSequence(){
			jQuery(".check_proc_seq").each(function(){
				var jo_po_val = jQuery("#jo_po").val();
				var po_type_val = jQuery("#po_type").val();
				if(jo_po_val == 'J' && po_type_val == 'JW'){
					var proc_seq = jQuery(this).val();
					if(proc_seq <= 0){
						openError('Process sequence must be greater than 0 in case of po category is jobwork  and po type is jobwork order');
						return false;
					}else{
						return true;
					}
				}
			});
			
		}


		// Add new item row
		var rowcount = 1;
		function addMorePOItem(){
			var rowid_vailid = parseInt(rowcount)-1;
			var val_status = itemDetailsVailidation(rowid_vailid);
			var secqu_check = checkSequence();
			if(secqu_check == false){
				return false;
			}
			if(val_status == 1){
				return false;
			}
			var ven_code = jQuery("#TSO1_vendor_registered_vendor_code").val();

			// Process seq list enable only if po type ='JW'
			var po_type = jQuery("#po_type").val();
			var proc_seq_status = 'disabled';
			if(jQuery.trim(po_type) == 'JW'){
				proc_seq_status = '';
			}

			// if PO Type2 = Open then qty entry not allowed
			var po_type2 = jQuery("#po_type2").val();
			var item_qty_status = '';
			var item_qty_val = '0';
			if(jQuery.trim(po_type2) == 'O'){
				item_qty_status = 'readOnly';
				item_qty_val = '';
			}

			// Indent no is required in Po typre= Indent,Capital,Consumable,Repair Maint.
			var po_type = jQuery("#po_type").val();
			var indent_no_status = '';
			if(jQuery.trim(po_type) == 'RM' || jQuery.trim(po_type) == 'IM' || jQuery.trim(po_type) == 'JW' || jQuery.trim(po_type) == 'JT'){
				indent_no_status = 'disabled';
			}

			// PROCESS SEQUENCE MUST BE GREATER THAN 0 IN CASE OF PO CATEGORY IS JOBWORK  AND PO TYPE IS JOBWORK ORDER
			var jo_po_val = jQuery("#jo_po").val();
			var po_type_val = jQuery("#po_type").val();
			if(jo_po_val == 'J' && po_type_val == 'JW'){
				var proc_seq = 1;
			}else{
				var proc_seq = 0;
			}

			var proc_seq = 0;
			selected_items = [];
			jQuery('input[name="item[pios_item_cd][]"]').each(function(){
				selected_items.push(jQuery(this).val());
			});
			

			var select_item = "getTableSelectData('TSOI2-"+rowcount+"','','item_stock_hsn_view', 'item_cd,item_desc,item_type,rec_uom,gst_code,hsn_no,igst,cgst,sgst', 'item_cd,item_type,rec_uom,item_desc,gst_code,hsn_no','54','483','TSOI2-"+rowcount+"_item_stock_hsn_view_item_cd',{'close_st':'N'},{},{},{},'setItemRelatedVal1','"+selected_items+"','item_cd');"
			if(jQuery.trim(po_type) == 'IN'){
				var select_item = "indentNoRequired('"+rowcount+"');"
			}

			// Set Required Delivery Date
			var ed_date = jQuery("#valid_to").val();
			
			var location_code = jQuery("#TSO6_location_locat_code").val();
    		$('#po_item_detail_main').append(`<tr class="po_item_detailRow_${rowcount}">
				<td class="sticky-col first-col">
					<input type="text" id="TSOI1-${rowcount}_po_indent_pend_view_ind_no" class="tbl_select ${indent_no_status} NLW_12D" name="item[pios_pois_no][]" onclick="getTableSelectData('TSOI1-${rowcount}','','po_indent_pend_view', 'ind_no,ind_dt,ind_type,item_cd,item_desc', 'ind_no,ind_type,item_cd,item_desc','54','631','TSOI1-${rowcount}_po_indent_pend_view_ind_no',{'unit_cd':'{{ @$user['unitcode']}}', 'po_type':'${po_type}'});" onfocus="setFunForItemByIndent(${rowcount},1)" autocomplete="off" readonly="">

					<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI1-${rowcount}_tbl_select_loader" style="display: none;">
				</td>


				<input type = "hidden" name = "item[sl_no][]" value = "">

				

				<td class="sticky-col second-col">
				<input type="text" id="TSOI2-${rowcount}_item_stock_hsn_view_item_cd" class="tbl_select po_indent_item_cd po_item_with_int_${rowcount} NLW_9D" name="item[pios_item_cd][]" onclick="${select_item}" autocomplete="off" readonly="">
				<br><span id="TSOI2-${rowcount}_item_stock_hsn_view_item_cd_error" style='font-size:10px; color:red; display:none;'>Please select Item Code</span>
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI2-${rowcount}_tbl_select_loader" style="display: none;">
				</td>

				<td class="sticky-col third-col">
				<input type="text" id="TSOI2-${rowcount}_item_stock_hsn_view_item_desc" class="item_cinput_field" readOnly>
			</td>  
			<td>
				<input type="text" id="" class="item_cinput_field NLW_15D" name="item[make_by][]" t-maxc='100'>
			</td>
			<td>
				<button class="flx_btn" onclick="openItemSpecfPopup(${rowcount});" type="button">Specf</button>
				<!-- Item Specf Modal -->
				<div id="item_specf_modal_${rowcount}" class="modal fade show" role="dialog" style="margin: 0 auto;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-3 col-md-3">
										<lable title="Specification">Specif	</lable>
									</div>
									<div class="col-sm-8 col-md-8">
										<textarea rows="4" cols="50" name="item[item_specf][]" t-maxc='100'></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" onclick="closeItemSpecfPopup(${rowcount});" type="button">OK</button>
										<button class="flx_btn" onclick="closeItemSpecfPopup(${rowcount});" type="button">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Item Specf Modal -->
			</td>
			<td>
				<button class="flx_btn" onclick="getItemRateHistory(${rowcount});" type="button">RH</button>
				<!-- Item RH Modal -->
				<div id="item_RH_modal_${rowcount}" class="modal fade show" role="dialog" style="margin: 0 auto;">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12 col-md-12">
									<div class="row">
											<div class="flexigrid col-md-12 no-padding" style="width: 100%;">
												<div class="mDiv" style="width: 98.5%; margin: 0 auto -5px;">
													<div class="ftitle">
														
													</div>
												</div>
												<div id="main-table-box" class="main-table-box">
													<div class="bDiv flexigrid_table_res">
														<table id="flex1" class='plane_table'>
															<thead>
																<tr class="hDiv">
																	<th>
																		<div class="text-left field-sorting" rel="account_title">
																			Po Number				
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			Po Date
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			Amd. Number
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
																			Rate
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			Discount %
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			Quantity
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			IGST
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			CGST
																		</div>
																	</th>
																	<th>
																		<div class="text-left field-sorting">
																			SGST
																		</div>
																	</th>
																</tr>
															</thead>
															<tbody class="item_rate_history" id="item_rate_history_${rowcount}">
																
															</tbody>
														</table>
													</div>
												</div>
											</div>      		
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" onclick="closeItemRHPopup(${rowcount});" type="button">OK</button>
										<button class="flx_btn" onclick="closeItemRHPopup(${rowcount});" type="button">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Item RH Modal -->
			</td>
			<td>
				<input type="text" id="TSOI7-${rowcount}_quot_dtl_quot_no" class="tbl_select NLW_15D" name="item[quot_no][]" onclick="getTableSelectData('TSOI7-${rowcount}','','quot_dtl', 'quot_no', 'quot_no','86','593','TSOI7-${rowcount}_quot_dtl_quot_no');" autocomplete="off" readonly="">
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI7-${rowcount}_tbl_select_loader" style="display: none;">
			</td>
			<td>
				<input type="text" class="item_cinput_field NLW_3D" name="item[uom][]" id="TSOI2-${rowcount}_po_indent_item_view_rec_uom" readOnly> 
				<!--<select class="item_cinput_field" name="item[uom][]">
					<option></option>
					@foreach($unit_of_measurements as $unit_of_measurement)
						<option value="{{$unit_of_measurement->measure_unit}}">{{$unit_of_measurement->measure_unit}}</option>
					@endforeach
				</select>-->
			</td>
			<td>
				<input type="text" id="TSOI3-${rowcount}_process_sheet_view_proc_seq" class="tbl_select ${proc_seq_status} NLW_3D check_proc_seq" name="item[proc_seq][]" onclick="getTableSelectData('TSOI3-${rowcount}','','process_sheet_view', 'proc_seq,proc_code,short_descrip', 'proc_code,short_descrip','171','590','TSOI3-${rowcount}_process_sheet_view_proc_seq');" autocomplete="off" readonly="" value="${proc_seq}"/>
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI3-${rowcount}_tbl_select_loader" style="display: none;">
			</td>
			<td>
				<input type="text" id="TSOI3-${rowcount}_process_sheet_view_proc_code" class="item_cinput_field NLW_4D" name="item[proc_cd][]" readOnly value=".">
			</td>  
			<td>
				<input type="text" id="TSOI3-${rowcount}_process_sheet_view_short_descrip" class="item_cinput_field NLW_18D" readOnly>
			</td> 
			<td>
				<input type="text" id="TSOI2-${rowcount}_po_indent_item_view_ind_wt" class="item_cinput_field NLW_12D" name="item[rec_weight][]" t-maxc='18'>
			</td>
			<td>
				<input type="number" id="TSOI2-${rowcount}_po_indent_item_view_bal_qty" onblur="calculateDiscountItemAmount(${rowcount});" class="item_cinput_field NLW_18D enter_bal_qty" value="${item_qty_val}" name="item[qty][]" ${item_qty_status} step="any" t-maxc='18' onkeyup="notZeroVallue(this)">
				<input type="hidden" class="bal_qty_val view_bal_qty_${rowcount}" value="0" />
				<br><span id="po_indent_item_qty_error_${rowcount}" style='font-size:10px; color:red; display:none;'>Material Quantity must be greater then 0</span>
				<input type="hidden" id="bal_qty_check_${rowcount}" name="item[srv_qty][]" />
				<span id="bal_qty_check_error_${rowcount}" style='font-size:10px; color:red; display:none;'>Material Quantity must not be greater then balance quantity!</span>
			</td> 
			<td>
			
				<input type="number" id="TSOI2-${rowcount}_po_indent_item_view_rate" class="item_cinput_field NLW_12D" onblur="calculateDiscountItemAmount(${rowcount});" value="0" name="item[material_rate][]" step="any" t-maxc='18'>
				<br><span id="po_indent_item_rate_error_${rowcount}" style='font-size:10px; color:red; display:none;'>Material rate must be greater then 0</span>
			</td>
			<td>
				<input type="text" class="item_cinput_field NLW_3D" name="item[rate_uom][]" id="TSOI2-${rowcount}_po_indent_item_view_rec_unit" readOnly> 
				<!--<input type="text" id="TSOI4-${rowcount}_unit_of_measurements_measure_unit" class="tbl_select" name="item[rate_uom][]" onclick="getTableSelectData('TSOI4-${rowcount}','','unit_of_measurements', 'measure_unit,description', 'measure_unit,description','171','740','TSOI4-${rowcount}_unit_of_measurements_measure_unit');" autocomplete="off" readonly="">
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI4-${rowcount}_tbl_select_loader" style="display: none;">-->
			</td> 
			<td>
				<input type="number" id="discount_percent_${rowcount}" class="item_cinput_field amount_field NLW_6D" onblur="calculateDiscountItemAmount(${rowcount});" value="0" min="0" name="item[discount_percent][]" step="any" t-maxc='6'>
			</td> 
			<td>
				<input type="number" id="discount_amt_${rowcount}" class="item_cinput_field NLW_8D" name="item[discount_amt][]" readOnly step="any">
			</td>
			<td>
				<input type="number" id="po_indent_item_amount_${rowcount}" class="item_cinput_field uc_amount" name="item[uc_amount][]" readOnly step="any">
			</td>
			<td>
				<input type="text" id="TSOI5-${rowcount}_gst_rate_master_gst_code" class="tbl_select NLW_7D" name="item[gst_code][]" onclick="getTableSelectData('TSOI5-${rowcount}','','gst_rate_master', 'gst_code,gst_desc,sgst_per,cgst_per,gst_rate', 'gst_code,gst_desc','96','586','TSOI5-${rowcount}_gst_rate_master_gst_code',{},{},{},{},'itemGSTRateCalculation');" autocomplete="off" readonly="">
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI5-${rowcount}_tbl_select_loader" style="display: none;">
			</td>
			<td>
				<input type="number" id="TSOI5-${rowcount}_gst_rate_master_sgst_per" class="item_cinput_field sgst_per NLW_3D" name="item[sgst_per][]" readOnly step="any">
			</td>
			<td> 
				<input type="number" id="sgst_amt_${rowcount}" class="item_cinput_field sgst_amt NLW_8D" name="item[sgst_amt][]" readOnly step="any"> 
			</td>
			<td>
				<input type="number" id="TSOI5-${rowcount}_gst_rate_master_cgst_per" class="item_cinput_field cgst_per NLW_3D" name="item[cgst_per][]" readOnly step="any">
			</td>
			<td>
				<input type="number" id="cgst_amt_${rowcount}" class="item_cinput_field cgst_amt NLW_8D" name="item[cgst_amt][]" readOnly step="any">
			</td>
			<td>
				<input type="number" id="TSOI5-${rowcount}_gst_rate_master_gst_rate" class="item_cinput_field gst_percent" name="item[gst_percent][]" readOnly step="any">
			</td>
			<td>
				<input type="number" id="gst_amt_${rowcount}" class="item_cinput_field gst_amt NLW_8D" name="item[gst_amt][]" readOnly step="any">
			</td>
			<td>
				<!--<input type="text" class="item_cinput_field NLW_8D" name="item[po_hsn_code][]" id="TSOI2-${rowcount}_po_indent_item_view_hsn_no" readOnly step="any">-->

				<input type="text" id="TSOI6-${rowcount}_hsn_master_hsn_no" class="tbl_select" name="item[po_hsn_code][]" onclick="getTableSelectData('TSOI6-${rowcount}','','hsn_master', 'hsn_no,hsn_desc,hsn_type', 'hsn_no,hsn_desc','171','740','TSOI6-${rowcount}_hsn_master_hsn_no');" autocomplete="off" readonly="">
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI6-${rowcount}_tbl_select_loader" style="display: none;">
			</td>
			<td>
				<input type="number" class="item_cinput_field po_indent_item_total po_indent_item_total_${rowcount}" name="item[goods_value][]" readOnly step="any">
			</td>
			<td>
				<input type="number" class="item_cinput_field NLW_10D" name="item[po_detail_others][]" step="any">
			</td>
			<td>
				<input type="number" class="item_cinput_field po_indent_item_total_${rowcount}" name="item[landed_cost][]" readOnly step="any">
			</td>
			<td>
				<button class="flx_btn" onclick="openSchedulepopup(${rowcount});" type="button">Schedule</button>

				<div id="schedule-${rowcount}" class="modal fade show" role="dialog" style="width: 700px;margin: 0 auto;">
						<div class="modal-dialog" style="max-width: 650px;">
							<div class="modal-content">
								<div class="modal-body">
									<div class="row">
										<div class="flexigrid col-md-12" style='width: 100%;'>
											<div class="mDiv" style = "padding:0px;">
												<div class="ftitle">
													<ul class="action_buts_main m-0 inline p-0">
														<li>
															<a href="javascript:void(0);" onclick="addScheduleForm('${rowcount}');" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
														</li>
														<li>
															Item Name: <span class = "item_name"></span>
														</li>
														<li>
															Item Description: <span class = "item_desc"></span>
														</li>
														<li>
															Quantity: <span class = "item_qty"></span>
														</li>					
													</ul>
												</div>
												<div title="Minimize/Maximize" class="ptogtitle">
													<span></span>
												</div>
											</div>
											<div id='main-table-box' class="main-table-box">
												<div class="bDiv flexigrid_table_res" >
													<table id="flex1" class='plane_table'>					
														<thead>
															<tr class="hDiv">
																<th>
																	<div class="text-left field-sorting" rel="account_title">
																		Schedule Date					
																	</div>
																</th>
																<th>
																	<div class="text-left field-sorting" rel="account_title">
																		Schedule Qty					
																	</div>
																</th>
																<th>
																	<div class="text-left field-sorting" rel="account_title">
																		Action					
																	</div>
																</th>
															</tr>
														</thead>
														<tbody id="schedule_part_form-${rowcount}" class="schedule_part_form">
														</tbody>
													</table>
												</div>
											</div>
										</div>      		
									</div>
									<div class="row">
										<div class="col-md-12" style="text-align: right;">
											<button class="flx_btn" onclick="closeSchedulepopup(${rowcount});" type="button">OK</button>
											<button class="flx_btn" onclick="closeSchedulepopup(${rowcount});" type="button">Cancel</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

			</td>
			<td>
				<input type="date" value="${ed_date}" class="item_cinput_field" name="item[ed_date][]" onchange="VailidateAmdEffDateValid(this, 'Required Delivery Date')">
			</td>
			<td>
				<input type="number" class="item_cinput_field NLW_4D" name="item[tolerance_per][]" t-maxc='4' step="any" onkeyup="notZeroVallue(this)">
			</td>
			<td>
				<button class="flx_btn" onclick="openItemRemarksPopup(${rowcount});" type="button">Remarks</button>
				<!-- Item Remarks Modal -->
				<div id="item_remarks_modal_${rowcount}" class="modal fade show" role="dialog" style="margin: 0 auto;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-3 col-md-3">
										<lable>Remarks	</lable>
									</div>
									<div class="col-sm-8 col-md-8">
										<textarea rows="4" cols="50" name="item[remarks][]" t-maxc='100'></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 no-padding" style="text-align: right;">
										<button class="flx_btn" onclick="closeItemRemarksPopup(${rowcount});" type="button">OK</button>
										<button class="flx_btn" onclick="closeItemRemarksPopup(${rowcount});" type="button">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Item Remarks Modal -->
			</td>

			<td>
				<input type="checkbox" id="" class="item_cinput_field" name="item[hide][]" disabled >
			</td>

			<td>
			<a href="javascript:void(0);" onclick="removePOItemRow(${rowcount})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
			</td></tr>`); 
			rowcount++;
    	}

    	
		// Check blank rows in schedule popup
		function checkBlankRows(){
			var rowcount = jQuery(".schedule_part_form input").length;
			if(rowcount>0){
				jQuery(".schedule_part_form input").each(function(){
					var input_data = jQuery(this).val();
					if(input_data == ''){
						return false;
					}else{
						return true;
					}
				});
			}else{
				return true;
			}
		}

    	var schedule_row = 1;
    	function addScheduleForm(apend){
			if(!checkBlankRows()){
				openError("Schedule date and quantity required!");
				return false;
			}
    		
			$('#schedule_part_form-'+apend).append(`<tr class="schedule_rows schedulerows_${schedule_row}">
   
				<td style="text-align:center;" style="width:200px;">
					<input  name="item[sl_no][${apend-1}][schedule][schedule_dt][]" value="" type="date" class="item_cinput_field" style="width:110px;" onchange="VailidateAmdEffDateValid(this,'Schedule Date')"/>
				</td>

				<td style="text-align:center;" style="width:200px;">
					<input id="schedule_qty_${schedule_row}" name="item[sl_no][${apend-1}][schedule][schedule_qty][]" value="" type="number" class="item_cinput_field schedule_qty" onkeyup="notZeroVallue(this)"/>
				</td>

				<td style="text-align:center;">
					<a href="javascript:void(0);" onclick="removeScheduleRow(${schedule_row})" title="Remove Row"><img src="{{ URL::asset('assets/icons/delete.png') }}"></a>
				</td>

				</tr>`); 

			schedule_row++;

    	}


    	function closeSchedulepopup(apend){

    	
    		var validate_qty = parseFloat(jQuery('#TSOI2-'+apend+'_po_indent_item_view_bal_qty').val())

    		if(jQuery('#po_type2').val() == 'C'){

    			var length = jQuery('.schedule_rows').length;
				var val = 0;
				if(length > 0){
					$('.schedule_qty').each(function(){
					    val += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
					});
					if(parseFloat(val) != validate_qty){

						openError('Sum of schedule quantity should be equal to the quantity.');
						return false;

					}
					jQuery("#schedule-"+apend).modal('hide');
				}else{
					jQuery("#schedule-"+apend).modal('hide');
				}

    		}


    	}



    	function removeScheduleRow(schedule_row){

    		var rcon = confirm("Do you want to delete this record?");
			if(rcon == true){
				$(".schedulerows_"+schedule_row).remove();
				schedule_row--;
			}


    	}


    	//prevent type 0
		$(document).on('keyup keydown change','.schedule_qty',function(){
          if (/^0/.test(this.value)) {
            this.value = this.value.replace(/^0/, "")
          }
        })


        //prevent type after reached max length
	    validationLength = 12;
	    $(document).on('keyup keydown change','.schedule_qty',function(){

	        if($(this).val().length > validationLength){

	            val=$(this).val().substr(0,$(this).val().length-1);
	            $(this).val(val);
	        };
	    });

		// Remove Last One Row for item 
		function removePOItemRow(rowId){
			var rcon = confirm("Do you want to delete this record?");
			if(rcon == true){
				rowcount--;
				$(".po_item_detailRow_"+rowId).remove();
				calculateTotalAmounts();
			} 
		}
		
		function chekvalue()
		{
			var po_typ = jQuery("#jo_po").val();
			var po_cat = jQuery("#po_type").val();
			if(po_typ == 'J' || po_cat == 'JW')
			{
				openError("Process Sequence should not be Zero ");
				return false;
			}
		}
		jQuery(document).ready(function(){
			// Set vailidation vailid from and vailid to
			var from_date = jQuery("#valid_fr").val();
			jQuery("#valid_to").attr("min",from_date);
			jQuery("#valid_fr").change(function(){
				var from_date = jQuery(this).val();
				jQuery("#valid_to").attr("min",from_date);
			});

			jQuery("#valid_to").change(function(){
				var to_date = jQuery(this).val();
				jQuery("#valid_fr").attr("max",to_date);
			});

			// Set the sales tax type by vender code 
			jQuery("#TSO1_vendor_master_view_vendor_code").focus(function(){
				var ven_cd = jQuery(this).val();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				jQuery.ajax({
					type: 'get',
					url: baseUrl + '/purchaseorder/getvendertype',
					data: {'_ts':_ts, 'ven_cd': ven_cd},
					success: function(res) {
						jQuery("#sales_tax_type").val(res);
						jQuery("#TSO1_vendor_master_view_vendor_code").addClass('disabled');
					}
				});
			});

			// Set function for get location respective unit code
			jQuery("body").delegate('.select_unit_part', 'focus', function(){
				var unit_code = jQuery(this).val();
				var rowcount = jQuery(this).attr('rowcount');
				var posi_top = jQuery(this).attr('posi_top');
				var sel_location = "getTableSelectData('TSO5-"+rowcount+"','','location', 'locat_code,short_desc', 'locat_code,short_desc','"+posi_top+"','840','TSO5-"+rowcount+"_unit_code',{'unit_code':'"+unit_code+"'});";
				
				jQuery(this).parent().parent().find(".select_location_part").val('');
				jQuery(this).parent().parent().find(".select_location_part").attr('onclick',sel_location);
			});

		});
	</script>
	<script src="{{ URL::asset('assets/js/Material/PurchaseOrder/ctable_script.js') }}"></script>

@endsection