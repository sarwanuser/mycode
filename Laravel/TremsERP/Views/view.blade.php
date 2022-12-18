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
</style>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content main  -->
    <section class="content-header" style="margin-bottom: 80px;">
	<form method="POST" enctype="multipart/form-data" id="create_po">
	
		<!-- Top Bar Section -->
		<div class="row">
			<div class="col-md-4 col-sm-12 m-t-10">
				<h4 style="margin: 0;">View Purchase Order</h4>  
			</div>
			<div class="col-md-8 col-sm-12 m-t-10">
				<ul class="action_buts_main right">

					
					@if(empty($PoHead->chck_by))

		               <li>
		                  <button type="button" title="Save" class="flx_btn" id = "check">Check</button>
		               </li>

		               @endif

		               @if(empty($PoHead->appr_by) && !empty($PoHead->chck_by))

		               <li>
		                  <button type="button" title="Save" class="flx_btn" id = "verify">Verify</button>
		               </li>

		               @endif

		               @if(!empty($PoHead->appr_by) && !empty($PoHead->chck_by) && empty($PoHead->frez_by))

		               <li>
		                  <button type="button" title="Save" class="flx_btn" id = "approved">Approve</button>
		               </li>

		               @endif



					<li style="margin:0 0px 0;"> 
						<button type="button" class="flx_btn"> <a href="" id="cancel_po_act" class="disabled1">Cancel PO </a></button> 
					</li>
					<li style="margin:0 0px 0;"> 
						<button type="button" class="flx_btn"> <a href="" id="amenment_btn" class="">Amendment PO </a></button> 
					</li>
					<li style="margin:0 0px 0;">
						<button type="button" data-toggle="modal" data-target="#annexure_modal" class="flx_btn ven_code_loc_en"> Annexure </button> 

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
												<a href="javascript:void(0);" title="Add New Row" class="disabled"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1">					
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
													</tr>
												</thead>
												<tbody id="annexure_detail">
													@php $sin = 1; @endphp
													@foreach($PoHead->getAnnexPoMst as $annex)
														<tr id="annexuretr_{{$sin}}" class="termConditionRows">
															<td>
																<input id="sr_no_{{$sin}}" name="annexure[s_no][]" type="text" class="item_cinput_field" style="width:75px;" value="{{$sin}}" readonly/>
															</td>    	
															<td style="text-align:center;">
																<select class="cinput_field disabled" id="annex_type_{{$sin}}" name="annexure[annex_type][]" style="width:120px;">
																	<option value=""></option>
																	@php
																		$annexure_types = getSecContDesByType('ANNEXURE_TYPE');
																	@endphp
																	@foreach($annexure_types as $annexure_type)
																		<option value="{{ $annexure_type->control_code }}" @if($annexure_type->control_code == $annex->annex_type) {{'selected'}} @endif>{{ $annexure_type->meaning }}</option>
																	@endforeach
																</select>
															</td>
															<td style="text-align:center;" style="width:200px;">
																<textarea id="subject{{$sin}}" name="annexure[subject][]" style="width:220px; height:23px;" class="item_cinput_field disabled">{{ $annex->subject }}</textarea>
															</td>
															<td style="text-align:center;" style="width:200px;">
																<textarea id="sales_desc{{$sin}}" name="annexure[sales_desc][]" style="width:220px; height:23px;" class="item_cinput_field disabled">{{ $annex->sales_desc }}</textarea>
															</td>
															<td style="text-align:center;">
																<input type="checkbox" name="annexure[cancel_ter][]" value="Y" @if($annex->cancel_ter == 'Y') {{'checked'}} @endif class="disabled"/>
															</td>
														</tr>
														@php $sin++; @endphp
													@endforeach
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
						<button type="button" class="flx_btn ven_code_loc_en" onclick="openPaymentTerms();" > Payment Terms </button>

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
												<a href="javascript:void(0);" title="Add New Row" class="disabled"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1">					
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
													</tr>
												</thead>
												<tbody id="payment_terms_detail">
													@php $PaymentTermRowsCount = 1; @endphp
													@foreach($PoHead->getMultiPayTerm as $payterm)
													<tr id="annexuretr_{{$PaymentTermRowsCount}}" class="termConditionRows">
														<td>
															<input type="text" id="TSO{{$PaymentTermRowsCount}}_payment_term_sno" class="cinput_field tbl_select disabled" name="pay_term[pay_cd][]" value="{{$payterm->pay_cd}}" onclick="getTableSelectData('TSO{{$PaymentTermRowsCount}}','','payment_term', 'sno,pay_term,no_of_days,percent_type', 'pay_term','${PaymentTermTop}','${PaymentTermLeft}','TSO{{$PaymentTermRowsCount}}_payment_term_sno');" autocomplete="off" style="width:120px; readOnly />

															<img class="input_loader TSO{{$PaymentTermRowsCount}}_tbl_select_loader" style="display: none;" src="/assets/icons/btn_loader.gif">
														</td>    	
														<td style="text-align:center;">
															<input type="text" class="cinput_field disabled" id="TSO{{$PaymentTermRowsCount}}_payment_term_pay_term" style="width:120px;" readOnly value="{{ getPayTermById($payterm->pay_cd) }}" />
														</td>
														<td style="text-align:center;" style="width:200px;">
															<input type="text" class="cinput_field disabled" id="TSO{{$PaymentTermRowsCount}}_payment_term_no_of_days" name="pay_term[days][]" style="width:120px;" value="{{$payterm->days}}" />
														</td>
														<td style="text-align:center;" style="width:200px;">
															<input type="text" class="cinput_field pay_term_percentage disabled" id="TSO{{$PaymentTermRowsCount}}_payment_term_percent_type" name="pay_term[percentage][]" value="{{$payterm->percentage}}" style="width:120px;" />
														</td>
														<td style="text-align:center;">
															<textarea id="terms_description_{{$PaymentTermRowsCount}}" name="pay_term[remarks][]" style="width:220px; height:23px;" class="item_cinput_field disabled">{{$payterm->remarks}}</textarea>
														</td>
													</tr>
													@endforeach
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
						<button type="button" class="flx_btn ven_code_loc_en" data-toggle="modal" data-target="#calculation_modal"> Calculation </button>

						<!-- Calculation Modal -->
						<div id="calculation_modal" class="modal bd-example-modal-lg fade show" role="dialog" style="margin: 0 auto;">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
							<div class="modal-body">
								<div class="row">
								<div class="flexigrid col-md-12 no-padding" style='width: 100%;'>
									<div class="mDiv">
										<div class="ftitle">

										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1">					
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
													@foreach($PoHead->getPoCalc as $pocalc)
													<tr id="" class="calculationRows">
														<td>
															<input id="sno" name="po_calc[sno][]" type="text" class="item_cinput_field" style="width:75px;" value="{{ $pocalc->sno }}" readonly/> 
														</td>    	
														<td style="text-align:center;">
															<select class="cinput_field disabled" id="head" name="po_calc[head][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_heads = getSecContDesByType('PO_CALCULATION_HEAD');
																@endphp
																@foreach($po_cal_heads as $po_cal_head)
																	<option value="{{ $po_cal_head->control_code }}" @if($po_cal_head->control_code == $pocalc->head) {{'selected'}}@endif>{{ $po_cal_head->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="oper" name="po_calc[oper][]" style="width:120px;">
																<option value="P">+</option>
															</select>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" class="disabled" name="po_calc[cal_on][]" value="Y" @if($pocalc->cal_on == 'Y'){{ 'checked' }} @endif/>
														</td>
														<td style="text-align:center;" style="width:200px;">
															<select class="cinput_field disabled" id="unit" name="po_calc[unit][]" style="width:120px;">
																<option value=""></option>
																@php
																	$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
																@endphp
																@foreach($po_cal_units as $po_cal_unit)
																	<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == $pocalc->unit) {{'selected'}} @endif>{{ $po_cal_unit->meaning }}</option>
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
																	<option value="{{ $po_cal_basedon->control_code }}" @if($po_cal_basedon->control_code == $pocalc->based_on) {{'selected'}} @endif>{{ $po_cal_basedon->meaning }}</option>
																@endforeach
															</select>
														</td>
														<td>
														<input id="cal_basic_amount" name="po_calc[amount][]" type="text" class="item_cinput_field" style="width:120px;" value="{{$pocalc->amount}}" readonly="">
														</td>
													</tr>
													@endforeach
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
						<button type="button" class="flx_btn ven_code_loc_en" data-toggle="modal" data-target="#item_details_modal"> Item Details </button>

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
												<a href="javascript:void(0);" class="disabled" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
												</li>					
											</ul>
										</div>
										<div title="Minimize/Maximize" class="ptogtitle">
											<span></span>
										</div>
									</div>
									<div id='main-table-box' class="main-table-box">
										<div class="bDiv flexigrid_table_res" >
											<table id="flex1" class='sticky_table'>					
												<thead>
													<tr>
														<th colspan="2" rowspan="1"></th>
													</tr>
													<tr class="hDiv">
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting" rel="account_title">
																Indent No.					
															</div>
														</th>
														<th colspan="1" rowspan="2">
															<div class="text-left field-sorting">
																Item Code
															</div>
														</th>
														<th colspan="1" rowspan="2">
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
															<div class="text-left field-sorting">
																Specification
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
													</tr>
												</thead>
												<tbody id="po_item_detail_main">
													@php $rowcount = 1; @endphp
													@foreach($PoHead->getPoDetail as $po_detail)
														<tr class="po_item_detailRow_{{ $rowcount }}">
														<td class="sticky-col first-col">
															<input type="text" id="TSOI1-{{ $rowcount }}_po_indent_view_ind_no" class="tbl_select disabled NLW_12D" value="{{ $po_detail->pios_pois_no }}" name="item[pios_pois_no][]" onclick="getTableSelectData('TSOI1-{{ $rowcount }}','','po_indent_view', 'ind_no,ind_dt,ind_type', 'ind_no,ind_type','166','246','TSOI1-{{ $rowcount }}_po_indent_view_ind_no',{'unit_cd':'{{ @$user['unitcode'][0]}}'});" onfocus="setFunForItemByIndent({{ $rowcount }},1)" autocomplete="off" readonly="">
															<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI1-{{ $rowcount }}_tbl_select_loader" style="display: none;">
														</td>

														<td class="sticky-col second-col">
															<input type="text" id="TSOI2-{{ $rowcount }}_item_stock_hsn_view_item_cd" class="tbl_select po_indent_item_cd disabled po_item_with_int_{{ $rowcount }}" name="item[pios_item_cd][]"  value="{{ $po_detail->pios_item_cd }}" autocomplete="off" readonly="">
															<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI2-{{ $rowcount }}_tbl_select_loader" style="display: none;">
														</td>

														<td class="sticky-col third-col">
															<input type="text" id="TSOI2-{{ $rowcount }}_item_stock_hsn_view_item_desc" value="{{ getItemDecByCode($po_detail->pios_item_cd) }}" class="item_cinput_field disabled" readOnly>
														</td>  
														<td>
															<input type="text" id="" class="item_cinput_field disabled" name="item[make_by][]" value="{{ $po_detail->make_by }}">
														</td>
														<td>
															<button class="flx_btn" onclick="openItemSpecfPopup({{ $rowcount }});" type="button">Specf</button>
															<!-- Item Specf Modal -->
															<div id="item_specf_modal_{{ $rowcount }}" class="modal fade show" role="dialog" style="margin: 0 auto;">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-body">
																			<div class="row">
																				<div class="col-sm-3 col-md-3">
																					<lable>Specification	</lable>
																				</div>
																				<div class="col-sm-8 col-md-8">
																					<textarea rows="4" cols="50" name="item[item_specf][]"> {{ $po_detail->item_specf }}</textarea>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-12 no-padding" style="text-align: right;">
																					<button class="flx_btn" onclick="closeItemSpecfPopup({{ $rowcount }});" type="button">OK</button>
																					<button class="flx_btn" onclick="closeItemSpecfPopup({{ $rowcount }});" type="button">Cancel</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<!-- /Item Specf Modal -->
														</td>
														<td>
															<button class="flx_btn" onclick="getItemRateHistory({{ $rowcount }});" type="button">RH</button>
															<!-- Item RH Modal -->
															<div id="item_RH_modal_{{ $rowcount }}" class="modal fade show" role="dialog" style="margin: 0 auto;">
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
																									<table id="flex1">
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
																										<tbody class="item_rate_history" id="item_rate_history_{{ $rowcount }}">
																											
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
																					<button class="flx_btn" onclick="closeItemRHPopup({{ $rowcount }});" type="button">OK</button>
																					<button class="flx_btn" onclick="closeItemRHPopup({{ $rowcount }});" type="button">Cancel</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<!-- /Item RH Modal -->
														</td>
														<td>
															<input type="text" id="TSOI7-{{ $rowcount }}_quot_dtl_quot_no" class="tbl_select disabled" name="item[quot_no][]" onclick="getTableSelectData('TSOI7-{{ $rowcount }}','','quot_dtl', 'quot_no', 'quot_no','171','590','TSOI7-{{ $rowcount }}_quot_dtl_quot_no');" value="{{ $po_detail->quot_no }}" autocomplete="off" readonly="">
															<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI7-{{ $rowcount }}_tbl_select_loader" style="display: none;">
														</td>
														<td>
															<input type="text" class="item_cinput_field disabled NLW_3D" name="item[uom][]" id="TSOI2-{{ $rowcount }}_po_indent_item_view_rec_uom" value="{{ $po_detail->uom }}" readOnly> 
														</td>
														<td>
															<input type="text" id="TSOI3-{{ $rowcount }}_process_sheet_view_proc_seq" class="tbl_select disabled" name="item[proc_seq][]" onclick="getTableSelectData('TSOI3-{{ $rowcount }}','','process_sheet_view', 'proc_seq,proc_code,short_descrip', 'proc_code,short_descrip','171','590','TSOI3-{{ $rowcount }}_process_sheet_view_proc_seq');" value="{{ $po_detail->proc_seq }}" autocomplete="off" readonly="">
															<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI3-{{ $rowcount }}_tbl_select_loader" style="display: none;">
														</td>
														<td>
															<input type="text" id="TSOI3-{{ $rowcount }}_process_sheet_view_proc_code" class="item_cinput_field disabled" name="item[proc_cd][]" value="{{ $po_detail->proc_cd }}" readOnly>
														</td>  
														<td>
															<input type="text" id="TSOI3-{{ $rowcount }}_process_sheet_view_short_descrip" value="{{ getSeqDesByCode($po_detail->proc_seq) }}" class="item_cinput_field disabled" readOnly>
														</td> 
														<td>
															<input type="number" id="TSOI2-{{ $rowcount }}_po_indent_item_view_ind_wt" value="{{ $po_detail->rec_weight }}" class="item_cinput_field disabled" name="item[rec_weight][]" onkeyup="notZeroVallue(this)">
														</td>
														<td>
															<input type="number" id="TSOI2-{{ $rowcount }}_po_indent_item_view_bal_qty" onblur="calculateDiscountItemAmount({{ $rowcount }});" class="item_cinput_field disabled" onkeyup="itemDetailsVailidation({{ $rowcount }})" value="{{ $po_detail->qty }}" name="item[qty][]" ${item_qty_status}>
															<br><span id="po_indent_item_qty_error_{{ $rowcount }}" style='font-size:10px; color:red; display:none;'>Material Quantity must be greater then 0</span>
														</td> 
														<td>
															<input type="number" id="po_indent_item_rate_{{ $rowcount }}" class="item_cinput_field disabled" onblur="calculateDiscountItemAmount({{ $rowcount }});" onkeyup="itemDetailsVailidation({{ $rowcount }})" value="{{ $po_detail->material_rate }}" name="item[material_rate][]">
															<br><span id="po_indent_item_rate_error_{{ $rowcount }}" style='font-size:10px; color:red; display:none;'>Material rate must be greater then 0</span>
														</td>
														<td>
															<input type="text" class="item_cinput_field disabled" name="item[rate_uom][]" id="TSOI2-{{ $rowcount }}_po_indent_item_view_rec_unit" value="{{ $po_detail->rate_uom }}" readOnly> 
														</td> 
														<td>
															<input type="number" id="discount_percent_{{ $rowcount }}" class="item_cinput_field amount_field disabled" onblur="calculateDiscountItemAmount({{ $rowcount }});" min="0" value="{{ $po_detail->discount_percent }}" name="item[discount_percent][]">
														</td> 
														<td>
															<input type="number" id="discount_amt_{{ $rowcount }}" class="item_cinput_field disabled" value="{{ $po_detail->discount_amt }}" name="item[discount_amt][]" readOnly>
														</td>
														<td>
															<input type="number" id="po_indent_item_amount_{{ $rowcount }}" class="item_cinput_field uc_amount disabled" value="{{ $po_detail->uc_amount }}" name="item[uc_amount][]" readOnly>
														</td>
														<td>
															<input type="text" id="TSOI5-{{ $rowcount }}_gst_rate_master_gst_code" class="tbl_select disabled" value="{{ $po_detail->gst_code }}" name="item[gst_code][]" onclick="getTableSelectData('TSOI5-{{ $rowcount }}','','gst_rate_master', 'gst_code,gst_desc,sgst_per,cgst_per,gst_rate', 'gst_code,gst_desc','171','740','TSOI5-{{ $rowcount }}_gst_rate_master_gst_code',{},{},{},{},'itemGSTRateCalculation');" autocomplete="off" readonly="">
															<img src="/assets/icons/btn_loader.gif" class="input_loader TSOI5-{{ $rowcount }}_tbl_select_loader" style="display: none;">
														</td>
														<td>
															<input type="number" id="TSOI5-{{ $rowcount }}_gst_rate_master_sgst_per" value="{{ $po_detail->sgst_per }}" class="item_cinput_field disabled" name="item[sgst_per][]" readOnly>
														</td>
														<td> 
															<input type="number" id="sgst_amt_{{ $rowcount }}" class="item_cinput_field sgst_amt disabled" value="{{ $po_detail->sgst_amt }}" name="item[sgst_amt][]" readOnly> 
														</td>
														<td>
															<input type="number" id="TSOI5-{{ $rowcount }}_gst_rate_master_cgst_per" class="item_cinput_field disabled" value="{{ $po_detail->cgst_per }}" name="item[cgst_per][]" readOnly>
														</td>
														<td>
															<input type="number" id="cgst_amt_{{ $rowcount }}" class="item_cinput_field cgst_amt disabled" value="{{ $po_detail->cgst_amt }}" name="item[cgst_amt][]" readOnly>
														</td>
														<td>
															<input type="number" id="TSOI5-{{ $rowcount }}_gst_rate_master_gst_rate" class="item_cinput_field disabled" value="{{ $po_detail->gst_percent }}" name="item[gst_percent][]" readOnly>
														</td>
														<td>
															<input type="number" id="gst_amt_{{ $rowcount }}" class="item_cinput_field gst_amt disabled" value="{{ $po_detail->gst_amt }}" name="item[gst_amt][]" readOnly>
														</td>
														<td>
															<input type="text" class="item_cinput_field disabled NLW_8D" value="{{ $po_detail->po_hsn_code }}" name="item[po_hsn_code][]" id="TSOI2-{{ $rowcount }}_po_indent_item_view_hsn_no" readOnly>
														</td>
														<td>
															<input type="number" class="item_cinput_field po_indent_item_total po_indent_item_total_{{ $rowcount }} disabled" value="{{ $po_detail->goods_value }}" name="item[goods_value][]" readOnly>
														</td>
														<td>
															<input type="number" class="item_cinput_field disabled" value="{{ $po_detail->po_detail_others }}" name="item[po_detail_others][]" >
														</td>
														<td>
															<input type="number" class="item_cinput_field disabled po_indent_item_total_{{ $rowcount }}" value="{{ $po_detail->landed_cost }}" name="item[landed_cost][]" readOnly>
														</td>
														<td>
															<button class="flx_btn"  type="button" data-toggle="modal" data-target="#schedule-popup">Schedule</button>

															<div id="schedule-popup" class="modal fade show" role="dialog" style="width: 700px;margin: 0 auto;">
		<div class="modal-dialog" style="max-width: 650px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row">
						<div class="flexigrid col-md-12" style='width: 100%;'>
							<div class="mDiv" style = "padding:0px;">
								<div class="ftitle">
									
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
												
											</tr>
										</thead>
										<tbody >
											@foreach($po_detail->getScheduleoDetail as $schedule_detail)
											<tr>
												<td style="text-align:center;" style="width:200px;">
													<input  type="date" value = "{{$schedule_detail->schd_date}}" class="item_cinput_field" style="width:110px;" readonly="" />
												</td>
												<td style="text-align:center;" style="width:200px;">
													<input  type="number" value = "{{$schedule_detail->schd_qty}}" class="item_cinput_field schedule_qty" readonly="" />
												</td>
											</tr>

											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>      		
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: right;">
							<button class="flx_btn" data-dismiss = "modal" type="button">OK</button>
							<button class="flx_btn" data-dismiss = "modal" type="button">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


														</td>
														<td>
															<input type="text" class="item_cinput_field disabled" value="@if($po_detail->ed_date != ''){{ date('d-m-Y', strtotime($po_detail->ed_date)) }} @endif" name="item[ed_date][]">
														</td>
														<td>
															<input type="text" class="item_cinput_field disabled NLW_4D" value="{{ $po_detail->tolerance_per }}" name="item[tolerance_per][]" t-maxc='4'>
														</td>
														<td>
															<button class="flx_btn" onclick="openItemRemarksPopup({{ $rowcount }});" type="button">Remarks</button>
															<!-- Item Remarks Modal -->
															<div id="item_remarks_modal_{{ $rowcount }}" class="modal fade show" role="dialog" style="margin: 0 auto;">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-body">
																			<div class="row">
																				<div class="col-sm-3 col-md-3">
																					<lable>Remarks	</lable>
																				</div>
																				<div class="col-sm-8 col-md-8">
																					<textarea rows="4" cols="50" name="item[remarks][]">{{ $po_detail->remarks }}</textarea>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-12 no-padding" style="text-align: right;">
																					<button class="flx_btn" onclick="closeItemRemarksPopup({{ $rowcount }});" type="button">OK</button>
																					<button class="flx_btn" onclick="closeItemRemarksPopup({{ $rowcount }});" type="button">Cancel</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<!-- /Item Remarks Modal -->
														</td>
														<td>
															<input type="checkbox" id="" class="item_cinput_field" name="item[hide][]" @if($po_detail->closed == 'C') {{'checked'}}@endif disabled >
														</td></tr>
														@php $rowcount++; @endphp
													@endforeach
												</tbody>
												<tfoot class="item_total">
													<td>Total: </td>
													<td colspan="16"></td>
													<td><span id="amt_total">0</span></td>
													<td></td>
													<td></td>
													<td><span id="sgst_amt_total">0</span></td>
													<td></td>
													<td><span id="cgst_amt_total">0</span></td>
													<td></td>
													<td><span id="gst_amt_total">0</span></td>
													<td></td>
													<td><span id="po_indent_item_total">0</span></td>
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
										<button class="flx_btn" data-dismiss="modal" type="button">OK</button>
										<button class="flx_btn" data-dismiss="modal" type="button">Cancel</button>
									</div>
								</div>
							</div>
							</div>
						</div>
						</div>
						<!-- Item Details Modal -->
					</li>
					@if(!isset($_REQUEST['_per']))
					<li>
						<a href="/purchaseorder/update/{{ $PoHead->po_id }}?_ts={{@$_REQUEST['_ts']}}&_fap={{@$_REQUEST['_fap']}}" title="Edit" id="go_to_edit_page"><img src="{{ URL::asset('assets/icons/edit.png') }}"/></a>
					</li>
					<!--<li>
						<button type="submit" class="submit_button"> 
							<img src="{{ URL::asset('assets/icons/save.png') }}"/>
						</button>
					</li>-->
					<!--<li>
						<a href="#" title="Save And Close"><img src="{{ URL::asset('assets/icons/save_close.png') }}"/></a>
					</li>-->
					<li>
						@if(@$_REQUEST['_fap'] == 1)
							<a href="{{url('/approvalscreen')}}/{{@$user['approvalscreen']}}?_ts={{@$_REQUEST['_ts']}}" title="Cancel"><img src="{{ URL::asset('assets/icons/cancel.png') }}"/></a>
						@else
							<a href="{{url('/purchaseorder?_ts='.@$_REQUEST['_ts'])}}" title="Cancel"><img src="{{ URL::asset('assets/icons/cancel.png') }}"/></a>
						@endif
					</li>
					@endif
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
									View Purchase Order		
								</div>
								<div class='clear'></div>
							</div>
						</div>
						<div id='main-table-box'>
							<div class='form-div cformdiv'>
								<div class="row">
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Type	</lable>
										<select class="cinput_field disabled" id="jo_po" name="jo_po" onchange="POTypeVailidation();">
											<option value=""></option>
											@php
												$potheads = getSecContDesByType('PO_HEAD');
											@endphp
											@foreach($potheads as $pothead)
												<option value="{{ $pothead->control_code }}" @if($pothead->control_code == $PoHead->jo_po) {{ 'selected' }} @endif>{{ $pothead->meaning }}</option>
											@endforeach
										</select>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Category</lable>
										<select class="cinput_field disabled" id="po_type" name="po_type" onchange="POTypeVailidation();">
											<option value=""></option>
											@php
												$potypes = getSecContDesByType('PO_TYPE');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == $PoHead->po_type) {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_type"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Unit Code</lable>
										<input type="text" disabled class="cinput_field disabled" name="unit_cd" id="unit_cd" value="{{ $PoHead->unit_cd }}"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Unit Name</lable>
										<input type="text" class="cinput_field disabled" value="{{ getUnitName($PoHead->unit_cd) }}" readOnly/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO NO.</lable>
										<input type="text" id="po_no" class="cinput_field " name="po_no" value="{{ $PoHead->po_no }}" readOnly/>
										<input type="hidden" value="{{ $PoHead->po_id }}" id="po_id" />
										<span class="validate_error group_code"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Date	</lable>
										<input type="date" class="cinput_field disabled" value="{{ date('Y-m-d', strtotime($PoHead->po_dt)) }}" name="po_dt" id="po_dt" readyOnly/>
									</div>
									
									<div class="col-sm-12 col-md-3 cfieldmain">
										<lable class="cinput_lable">Open/Close</lable>
										<select class="cinput_field disabled" id="po_type2" name="po_type2">
											<option value=""></option>
											@php
												$potypes = getSecContDesByType('PO_TYPE2');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == $PoHead->po_type2) {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_type2"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Type	</lable>
										<select class="cinput_field disabled" id="po_unit_type" name="po_unit_type">
											<option value=""></option>
											@php
												$potypes = getSecContDesByType('PO_UNIT_TYPE');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == $PoHead->po_unit_type) {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_unit_type"></span>
									</div>
									
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amendment No</lable>
										<input type="text" readOnly class="cinput_field disabled" id="amd_no" name="amd_no" value="{{ $PoHead->amd_no }}"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amendment Date</lable>
										<input type="date" class="cinput_field disabled" value="@if(!empty($PoHead->amd_dt)){{ date('Y-m-d', strtotime($PoHead->amd_dt)) }}@endif" name="amd_dt"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">Amend With Effect Of</lable>
										<input type="date" class="cinput_field disabled" id="amd_wef" value="@if(!empty($PoHead->amd_wef)){{ date('Y-m-d', strtotime($PoHead->amd_wef)) }}@endif" name="amd_wef"/>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">PO Status</lable>
										<input type="hidden" value="{{$PoHead->po_status}}" id="po_status_val" />
										<select class="cinput_field disabled" id="po_status" name="po_status">
											<option value=""></option>
											@php
												$potypes = getSecContDesByType('PO_STATUS');
											@endphp
											@foreach($potypes as $potype)
												<option value="{{ $potype->control_code }}" @if($potype->control_code == $PoHead->po_status) {{ 'selected' }} @endif>{{ $potype->meaning }}</option>
											@endforeach
										</select>
										<span class="validate_error po_status"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable lable-width-38">PO Value</lable>
										<input type="text" class="cinput_field disabled" name="po_value" id="po_value" value="{{ $PoHead->po_value }}" style="float:left;" readOnly/>
									</div>
									
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Valid From</lable>
										<input type="date" class="cinput_field disabled" value="{{ date('Y-m-d', strtotime($PoHead->valid_fr)) }}" name="valid_fr" id="valid_fr"/>
										<span class="validate_error valid_fr"></span>
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Valid To</lable>
										<input type="date" class="cinput_field disabled" value="{{ date('Y-m-d', strtotime($PoHead->valid_to)) }}" name="valid_to" id="valid_to"/>
										<span class="validate_error valid_to"></span>
									</div>

									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable">*Location</lable>
										<input type="text" id="TSO6_location_locat_code" class="cinput_field tbl_select disabled" name="cost_centre" value="{{ $PoHead->cost_centre }}" onclick="getTableSelectData('TSO6','','location', 'locat_code,long_desc', 'locat_code,long_desc','285','1248','TSO6_location_locat_code',{'unit_code':'{{ @$user['unitcode'][0]}}'});" autocomplete="off"/>
										<img src="/assets/icons/btn_loader.gif" class="input_loader TSO6_tbl_select_loader" style="display: none;">
									</div>
									<div class="col-sm-6 col-md-3 cfieldmain">
										<lable class="cinput_lable disabled">Location Description</lable>
										<input type="text" id="TSO6_location_long_desc" class="cinput_field" name="group_code" value="{{ $PoHead->getLocationDetails->short_desc }}" disabled />
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
														<input type="text" id="TSO1_vendor_master_view_vendor_code" class="cinput_field tbl_select disabled" name="ven_cd" onclick="getTableSelectData('TSO1','','vendor_master_view', 'vendor_code,name,state,city,address', 'vendor_code,name,state,city,address','402','281','TSO1_vendor_master_view_vendor_code',{'unit_code':'{{ @$user['unitcode'][0]}}', 'vendor_status':'{{'OK'}}'});" value="{{ $PoHead->ven_cd }}" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-6 cfieldmain">
														<lable class="cinput_lable lable-width-25">Vendor Name</lable>
														<input type="text" disabled class="cinput_field enput-width-74" id="TSO1_vendor_master_view_name" value="{{ $PoHead->getVenderDetails->name }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor City</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_city" value="{{ $PoHead->getVenderAddDetails->city }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor State</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_state" value="{{ $PoHead->getVenderAddDetails->state }}"/>
													</div>
													<div class="col-sm-12 col-md-9 cfieldmain">
														<lable class="cinput_lable lable-width-11">Vendor Address</lable>
														<input type="text" disabled class="cinput_field enput-width-83" id="TSO1_vendor_master_view_address" value="{{ $PoHead->getVenderAddDetails->address1 }}"/>
													</div>
													<!-- Ship To -->
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Bill To</lable>
														<input type="text" id="TSO1_vendor_master_view_vendor_code" class="cinput_field tbl_select disabled" name="ship_to" onclick="getTableSelectData('TSO1','','vendor_master_view', 'vendor_code,name,state,city,address', 'vendor_code,name,state,city,address','402','281','TSO1_vendor_master_view_vendor_code',{'unit_code':'{{ @$user['unitcode'][0]}}', 'vendor_status':'{{'OK'}}'});" value="{{ $PoHead->ship_to }}" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-6 cfieldmain">
														<lable class="cinput_lable lable-width-25">Vendor Name</lable>
														<input type="text" disabled class="cinput_field enput-width-74" id="TSO1_vendor_master_view_name" value="{{ @$PoHead->getShipVenderDetails->name }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor City</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_city" value="{{ @$PoHead->getShipVenderAddDetails->city }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor State</lable>
														<input type="text" disabled class="cinput_field" id="TSO1_vendor_master_view_state" value="{{ @$PoHead->getShipVenderAddDetails->state }}"/>
													</div>
													<div class="col-sm-12 col-md-9 cfieldmain">
														<lable class="cinput_lable lable-width-11">Vendor Address</lable>
														<input type="text" disabled class="cinput_field enput-width-83" id="TSO1_vendor_master_view_address" value="{{ @$PoHead->getShipVenderAddDetails->address1 }}"/>
													</div>
													<!-- /Ship To -->

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Vendor Type</lable>
														<input type="text" class="cinput_field disabled" id="sales_tax_type" name="sales_tax_type" value="{{ $PoHead->sales_tax_type }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Destination	</lable>
														<input type="text" class="cinput_field disabled" name="desti" value="{{ $PoHead->desti }}"/>
														<span class="validate_error desti"></span>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable lable-width-36">Dispatch Mode</lable>
														<select class="cinput_field" id="mode_del" name="mode_del disabled">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('SHIP_MODE');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == $PoHead->mode_del) {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Currency	</lable>
														<select class="cinput_field" id="cur_cd" name="cur_cd disabled">
															<option value=""></option>
															@php
																$currencys = getTableDatas('currency');
															@endphp
															@foreach($currencys as $currency)
																<option value="{{ $currency->currency_code }}" @if($currency->currency_code == $PoHead->cur_cd) {{ 'selected' }} @endif>{{ $currency->currency_desc }}</option>
															@endforeach
														</select>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">P/F Type	</lable>
														<select class="cinput_field disabled" id="pf_type" name="pf_type">
															<option value=""></option>
															@php
																$po_cal_units = getSecContDesByType('PO_CALCULATION_UNIT');
															@endphp
															@foreach($po_cal_units as $po_cal_unit)
																<option value="{{ $po_cal_unit->control_code }}" @if($po_cal_unit->control_code == $PoHead->pf_type) {{ 'selected' }} @endif>{{ $po_cal_unit->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error pf_type"></span>
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">P/F Charge	</lable>
														<input type="number" class="cinput_field disabled" name="pf_charge" id="pf_charge" value="{{ $PoHead->pf_charge }}" />
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">TCS%	</lable>
														<input type="number" class="cinput_field disabled" name="tot_per" value="{{ $PoHead->tot_per }}" id="tot_per" />
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Insurance</lable>
														<select class="cinput_field disabled" id="ins_cd" name="ins_cd">
															<option value=""></option>
															@php
																$ins_cds = getSecContDesByType('INS_CD');
															@endphp
															@foreach($ins_cds as $ins_cd)
																<option value="{{ $ins_cd->control_code }}" @if($ins_cd->control_code == $PoHead->ins_cd) {{'selected'}} @endif>{{ $ins_cd->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Insurance Amount	</lable>
														<input type="number" class="cinput_field disabled" name="ins_amt" id="ins_amt" value="{{ $PoHead->ins_amt }}"/>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Freight By	</lable>
														<select class="cinput_field disabled" id="freight1" name="freight1">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('FREIGHT1');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == $PoHead->freight1) {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">AMT % Freight</lable>
														<input type="text" class="cinput_field disabled" name="freight" value="{{ $PoHead->freight }}" />
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Save Max GST%</lable>
														<input type="text" class="cinput_field disabled" name="gst_rate" value="{{ $PoHead->gst_rate }}" />
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Delivery Term	</lable>
														<select class="cinput_field disabled" id="del_term" name="del_term">
															<option value=""></option>
															@php
																$del_terms = getSecContDesByType('DEL_TERM');
															@endphp
															@foreach($del_terms as $del_term)
																<option value="{{ $del_term->control_code }}" @if($del_term->control_code == $PoHead->del_term) {{ 'selected' }} @endif>{{ $del_term->meaning }}</option>
															@endforeach
														</select>
														<span class="validate_error del_term"></span>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Price Basis</lable>
														<select class="cinput_field disabled" id="price_basis" name="price_basis">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('PRICE_BASIS_PO');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == $PoHead->price_basis) {{'selected'}} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">*Payment Type</lable>
														<select class="cinput_field disabled" id="pay_type" name="pay_type">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('PAY_TYPE_PO');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == $PoHead->pay_type) {{ 'selected' }} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>													
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Based On</lable>
														<select class="cinput_field disabled" id="credit_condition " name="credit_condition ">
															<option value=""></option>
															@php
																$base_ons = getSecContDesByType('CREDIT_CONDITION');
															@endphp
															@foreach($base_ons as $base_on)
																<option value="{{ $base_on->control_code }}" @if($base_on->control_code == $PoHead->credit_condition) {{'selected'}} @endif>{{ $base_on->meaning }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-12 col-md-3 cfieldmain">
														<lable class="cinput_lable">Transporter</lable>
														<input type="text" id="TSO11_vendor_master_view_vendor_code" class="cinput_field tbl_select disabled" value="{{ $PoHead->transporter }}" name="transporter" onclick="getTableSelectData('TSO11','','vendor_master_view', 'vendor_code,address', 'vendor_code,address','439','649','TSO11_vendor_master_view_vendor_code',{'unit_code':'{{ @$user['unitcode'][0]}}'});" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO11_tbl_select_loader" style="display: none;">
													</div>

													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Transporter Name</lable>
														
														<input type="text" id="TSO11_vendor_master_view_address" class="cinput_field disabled" value="{{ @$PoHead->getTranspoter->name }}" readOnly/>
													</div>
													
												</div>
											</div>
											<div class="tab-pane" id="others">
											<div class="row">
													
													<div class="col-sm-6 col-md-3 cfieldmain">
														<lable class="cinput_lable">Project/ Job No.</lable>
														<input type="text" id="TSO1_tr_hrecpt_entry_no" class="cinput_field tbl_select disabled" name="reference" value="{{ $PoHead->reference }}" onclick="getTableSelectData('TSO1','','tr_hrecpt', 'entry_no,entry_dt,doc_no,doc_dt', 'entry_no,doc_no','404','556','TSO1_tr_hrecpt_entry_no',{'status':'V', 'unit_cd':'{{ @$user['unitcode'][0]}}'});" autocomplete="off"/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
													</div>
													<!--<div class="col-sm-6 col-md-5 cfieldmain">
														<lable class="cinput_lable">Tolerance+(%) (For Receiving)	</lable>
														<input type="number" class="cinput_field disabled" name="tot_per" value="{{ $PoHead->tot_per }}"/>
													</div>-->

													<div class="col-sm-12 col-md-5 cfieldmain">
														<lable class="cinput_lable">Tolerance Remark	</lable>
														<textarea class="cinput_field disabled" name="tolerance_remark"> {{ $PoHead->tolerance_remark }} </textarea>
													</div>

													<div class="col-sm-12 col-md-4 cfieldmain">
														<lable class="cinput_lable">Remarks	</lable>

														<textarea class="cinput_field disabled" name="remarks">{{ $PoHead->remarks }}</textarea>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Prepared By	</lable>
														<input type="text" id="TSO12_emp_master_hd_emp_number" class="cinput_field disabled" name="prep_by" autocomplete="off" value="{{ $PoHead->prep_by }}" readOnly/>										
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Prepared By Name	</lable>
														<input type="text" class="cinput_field disabled" id="TSO12_emp_master_hd_emp_first_name" value="{{ getUserNameByCode($PoHead->prep_by) }}" readOnly/>
													</div>
													<div class="col-sm-12 col-md-4 cfieldmain">
														<lable class="cinput_lable disabled">Quotation No.	</lable>
														<input type="text" class="cinput_field" name="quotation_no" value="{{ $PoHead->quotation_no }}"/>
														<span class="validate_error quotation_no"></span>
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked By	</lable>
														<input type="text" id="checked_by" class="cinput_field tbl_select disabled" name="chck_by" onclick="getTableSelectData('TSO13','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','550','349','TSO13_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'][0] }}'});" autocomplete="off" value="{{$PoHead->chck_by}}" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO13_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked By Name		</lable>
														<input type="text" id = "checked_name" class="cinput_field disabled" id="TSO13_emp_master_hd_emp_first_name" value="{{ getUserNameByCode($PoHead->chck_by) }}" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Checked Date	</lable>
														<input type="text" id = "checked_dt" class="cinput_field disabled" name="chck_dt" value="@if($PoHead->chck_dt != ''){{$PoHead->chck_dt}}@endif" readOnly/>
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified By	</lable>
														<input type="text" id="TSO14_emp_master_hd_emp_number" class="cinput_field tbl_select disabled" name="appr_by" onclick="getTableSelectData('TSO14','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','590','349','TSO14_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'][0] }}'});" autocomplete="off" value="{{ $PoHead->appr_by }}" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO14_tbl_select_loader" style="display: none;">
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified By Name	</lable>
														<input type="text" class="cinput_field disabled" id="TSO14_emp_master_hd_emp_first_name" value="{{ getUserNameByCode($PoHead->appr_by) }}" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Verified Date		</lable>
														<input type="text" class="cinput_field disabled" name="appr_dt" id = "frez_dt" value="@if($PoHead->appr_dt != ''){{$PoHead->appr_dt}}@endif" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved By	</lable>
														<input type="hidden" id="check_approved" value="{{ $PoHead->frez_by }}" />
														<input type="text" id="approved_by" class="cinput_field tbl_select disabled" name="frez_by" onclick="getTableSelectData('TSO15','','emp_master_hd', 'emp_number,emp_first_name,emp_last_name', 'emp_number,emp_first_name,emp_last_name','630','349','TSO15_emp_master_hd_emp_number', {'emp_number':'{{ @$user['emp_id'][0] }}'});" autocomplete="off" value="{{ $PoHead->frez_by }}" readOnly/>
														<img src="/assets/icons/btn_loader.gif" class="input_loader TSO15_tbl_select_loader" style="display: none;">
													</div>

													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved By Name	</lable>
														<input type="text" class="cinput_field"  id="approved_name" value="{{ getUserNameByCode($PoHead->frez_by) }}" readOnly/>
													</div>
													<div class="col-sm-6 col-md-4 cfieldmain">
														<lable class="cinput_lable">Approved Date	</lable>
														<input type="text" class="cinput_field" id = "approved_dt" name="frez_dt" value="@if($PoHead->frez_dt != ''){{$PoHead->frez_dt}}@endif" readOnly/>
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
																	<a href="javascript:void(0);" title="Add New Row"><img src="{{ URL::asset('assets/icons/add.png') }}" /></a>
																	</li>
																</ul>
															</div>
															<div title="Minimize/Maximize" class="ptogtitle">
																<span></span>
															</div>
														</div>
														<table id="flex1">
															<thead>
																<tr class="hDiv">
																	<th>
																		<div class="text-left field-sorting" rel="account_title">
																			Document Sequence No					
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
																</tr>
															</thead>
															<tbody id="po_doc_details">
																@php $sin = 1; @endphp
																@foreach($PoHead->getDocDetails as $podocs)
																<tr class="po_doc_detailRow_{{ $rowcount }}">
																	<td style="text-align:center;">
																		<input type="text" name="doc[doc_sl_no][]" class="item_cinput_field NLW_3D" value="{{$sin}}" readOnly/>
																	</td>
																	<td>
																		<input type="text" class="item_cinput_field datepicker" value="{{ $podocs->unit_cd }}" name="doc[unit_cd][]" readOnly/>
																	</td>
																	<td>
																		<input type="text" class="item_cinput_field datepicker" value="{{ getUnitNameCode($podocs->unit_cd) }}" readOnly/>
																	</td>
																	<td style="text-align:center;">
																		<input type="text" class="item_cinput_field" value="{{ $podocs->ref_doc_no }}" id="" name="doc[ref_doc_no][]" readOnly/>
																	</td>
																	<td style="text-align:center;">
																	<input type="text" class="item_cinput_field NLW_10D" value="{{ $podocs->ref_doc_type }}" name="doc[ref_doc_type][]" readOnly />
																	</td>
																	<td style="text-align:center;">
																		<input type="text" class="item_cinput_field NLW_20D" name="doc[doc_file_name][]" value="{{ $podocs->doc_file_name }}" readOnly />
																	</td>
																	<td style="text-align:center;">
																		<input type="text" class="item_cinput_field NLW_10D" value="{{ date('d-m-Y', strtotime($podocs->ref_doc_date)) }}" name="doc[ref_doc_date][]" readOnly />
																	</td>
																	<td style="text-align:center;">
																		<input type="text" class="item_cinput_field" value="{{ $podocs->remarks }}" id="doc_remarks_1" name="doc[remarks][]" readOnly/>
																	</td>
																	<td style="text-align:center;">
																		<a href="{{ $podocs->path }}" target="_blank">Download</a>
																	</td>
																</tr>
																@php $sin++; @endphp
																@endforeach
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
	</form>
	</section>
</div><!-- /.content-wrapper -->
  

@endsection

@section('scripts')
	<script>


		jQuery('#verify').click(function(e){
	      e.preventDefault();
	      if (confirm("Are you sure?")) {
	      var po_number = jQuery('#po_no').val();

	      $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

	      $.ajax({
	             url: baseUrl+'/purchaseorder/verify_po',
	             method: 'POST',
	             data:{'_ts':_ts, 'po_no': po_number},
	             success:function(response){
	                 if(response.status == 1){
	                     jQuery('#TSO14_emp_master_hd_emp_number').val(response.code);
	                     jQuery('#TSO14_emp_master_hd_emp_first_name').val(response.name);
	                     jQuery('#frez_dt').val(response.date);
	                      @if(@$_REQUEST['_fap'] == 1){
	                     	openSuccess('PO verified successfully.',"/approvalscreen/{{@$user['approvalscreen']}}?_ts={{@$_REQUEST['_ts']}}")
	                     }@else{
	                     	openSuccess('PO verified successfully.');
	                     }
	                     @endif
	                 }else{
	                  openError(response.message)
	                 }
	             }        
	         });
	      }

   		})


   		jQuery('#approved').click(function(e){
	      e.preventDefault();
	      if (confirm("Are you sure?")) {
	      var po_number = jQuery('#po_no').val();

	      $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

	      $.ajax({
	             url: baseUrl+'/purchaseorder/approve_po',
	             method: 'POST',
	             data:{'_ts':_ts, 'po_no': po_number},
	             success:function(response){
	                 if(response.status == 1){
	                     jQuery('#approved_by').val(response.code);
	                     jQuery('#approved_dt').val(response.date);
	                     jQuery('#approved_name').val(response.name);
	                      @if(@$_REQUEST['_fap'] == 1){
	                     	openSuccess('PO approved successfully.',"/approvalscreen/{{@$user['approvalscreen']}}?_ts={{@$_REQUEST['_ts']}}")
	                     }@else{
	                     	openSuccess('PO approved successfully.');
	                     }
	                     @endif
	                 }else{
	                  openError(response.message)
	                 }
	             }        
	         });
	      }

	   })


   		jQuery('#check').click(function(e){
	      e.preventDefault();
	      if (confirm("Are you sure?")) {
	      var po_number = jQuery('#po_no').val();

	      $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

	      $.ajax({
	             url: baseUrl+'/purchaseorder/checked_po',
	             method: 'POST',
	             data:{'_ts':_ts, 'po_no': po_number},
	             success:function(response){
	                 if(response.status == 1){
	                     jQuery('#checked_by').val(response.code);
	                     jQuery('#checked_dt').val(response.date);
	                     jQuery('#checked_name').val(response.name);
	                      @if(@$_REQUEST['_fap'] == 1){
	                     	openSuccess('PO checked successfully.',"/approvalscreen/{{@$user['approvalscreen']}}?_ts={{@$_REQUEST['_ts']}}")
	                     }@else{
	                     	openSuccess('PO checked successfully.');
	                     }
	                     @endif
	                 }else{
	                  openError(response.message)
	                 }
	             }        
	         });
	      }

	   })
		
		// Set total amounts on all rows
		function calculateTotalAmounts(){
			var amt_total = 0;
			var sgst_amt_total = 0;
			var cgst_amt_total = 0;
			var gst_amt_total = 0;
			var po_indent_item_total = 0;
			var uc_amount = 0;

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

			// CGST Amount
			jQuery(".cgst_amt").each(function(){
				if(jQuery(this).val() != ''){
					cgst_amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#cgst_amt_total").text(parseFloat(cgst_amt_total).toFixed(2));

			// GST Amount
			jQuery(".gst_amt").each(function(){
				if(jQuery(this).val() != ''){
					gst_amt_total += parseFloat(jQuery(this).val());
				}
			});
			jQuery("#gst_amt_total").text(parseFloat(gst_amt_total).toFixed(2));

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
		}

		jQuery(document).ready(function(){
			calculateTotalAmounts();

			// Cancel PO 
			jQuery("#cancel_po_act").click(function(event){
				event.preventDefault();
				var po_id = jQuery("#po_id").val();
				var po_no = jQuery("#po_no").val();
				var amd_no = jQuery("#amd_no").val();
				var confirmation_s = confirm("Do You Want To Cancel the PO?");
				if (confirmation_s == true) {
					openProcess();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					jQuery.ajax({
						type: 'post',
						url: baseUrl + '/purchaseorder/cancelpo',
						data: {'_ts':_ts, 'po_id': po_id, 'amd_no':amd_no, 'po_no':po_no},
						success: function(res) {
							closeProcess();
							console.log(res);
							if(res.status == 1){
								openSuccess(res.message);
							}else{
								openError(res.message);
							}
						}
					});
				}
			});

			// Amenment PO 
			jQuery("#amenment_btn").click(function(event){
				event.preventDefault();
				var po_no = jQuery("#po_no").val();
				var amd_no = jQuery("#amd_no").val();
				var confirmation_s = confirm("Do You Want To Amendment the PO?");
				if (confirmation_s == true) {
					openProcess();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					jQuery.ajax({
						type: 'post',
						url: baseUrl + '/purchaseorder/poamenment',
						data: {'_ts':_ts,  'po_no': po_no, 'amd_no':amd_no},
						success: function(res) {
							closeProcess();
							console.log(res);
							if(res.status == 1){
								openSuccess(res.message);
							}else{
								openError(res.message);
							}
						}
					});
				}
			});

			

			// Check amenment btn active or not 
			var po_status = jQuery("#po_status_val").val();
			var check_approved = jQuery("#check_approved").val();
			if(po_status == 'Y' && check_approved != ''){
				jQuery("#amenment_btn").removeClass('disabled');
			}else if(po_status == 'N'){
				jQuery("#amenment_btn").addClass('disabled');
				jQuery("#cancel_po_act").addClass('disabled');
			}else{
				jQuery("#amenment_btn").addClass('disabled');
			}

			// Go to edit page vailidation
			jQuery("#go_to_edit_page").click(function(event){
				event.preventDefault();
				var po_no = jQuery("#po_no").val();
				var amd_no = jQuery("#amd_no").val();
				
				openProcess();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				jQuery.ajax({
					type: 'post',
					url: baseUrl + '/purchaseorder/checklatestamen',
					data: { '_ts':_ts,'po_no': po_no, 'amd_no':amd_no},
					success: function(res) {
						closeProcess(); 
						console.log(res);
						if(res.status == 1){
							var link = jQuery("#go_to_edit_page").attr("href");
							var check_approved = jQuery("#check_approved").val();
							var po_status = jQuery("#po_status_val").val();
							if(check_approved != '' && po_status != 'N'){
								openError("This PO Already Approved,Updation not Allowed, Only Cancellation/Amendment Can Be Done!");
								return false;
							}else if(po_status == 'N'){
								openError("This PO Already Canceled, Updation not Allowed, Only View Can Be Done!");
								return false;
							}
							location.href = link;
						}else{
							openError(res.message);
						}
					}
				});	
			});
		});
	</script>
	<script src="{{ URL::asset('assets/js/Material/PurchaseOrder/ctable_script.js') }}"></script>

@endsection