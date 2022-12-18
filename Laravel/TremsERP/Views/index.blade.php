@extends('template.base')

@section('title', 'MIL - Purchase Order')

@section('styles')
<style>
	.ind_print_btn {
	background: url(assets/grocery_crud/themes/flexigrid/css/images/print_old.png) no-repeat;
	background-position: 0px 5px;
	padding-left: 20px;
	height: 22px;
	border: 0;
	width: 26px;
	margin-left: 6px;
	}
</style>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<!-- Content Header (Page header) -->
		<div class="row">
			<div class="col-md-6 col-sm-12 m-t-10">
				<h4 style="margin: 0;">Purchase Order</h4>
			</div>
			<div class="col-md-6 col-sm-12 m-t-10">

			</div>
		</div>
		<!-- /Content Header (Page header) -->
		
		<!-- Filter Form -->
		<div class="row m-t-10">
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">PO No</lable>
				<input type="text" id="TSO1_po_query_view_po_no" class="cinput_field tbl_select search_field" onclick="getTableSelectData('TSO1','','po_query_view', 'po_no,po_dt,ven_cd,name', 'po_no,ven_cd,name','130','300','TSO1_po_query_view_po_no',{'unit_cd':'{{ @$user['unitcode']}}'},{},{},{},'','','po_no','po_no##DESC');" value="{{!empty(@$search_session['search']['po_no']) ? $search_session['search']['po_no'] : ''}}" autocomplete="off" />
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSO1_tbl_select_loader" style="display: none;">
			</div>
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">PO Type	</lable>
				<select class="cinput_field search_field search_field" id="po_type">
					<option value=""></option>
					@foreach($po_types as $po_type)
						<option value="{{ $po_type->control_code }}" @if(@$search_session['search']['po_type'] == $po_type->control_code) {{ 'selected' }} @endif>{{ $po_type->description }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">Vendor Code</lable>
				<input type="text" id="TSO2_vendor_detail_view_vendor_code" class="cinput_field tbl_select search_field" onclick="getTableSelectData('TSO2','','vendor_detail_view', 'name,vendor_code,address', 'name,vendor_code','130','800','TSO1_vendor_detail_view_vendor_code',{},{},{},{},'','','vendor_code');" value="{{!empty(@$search_session['search']['ven_cd']) ? $search_session['search']['ven_cd'] : ''}}" autocomplete="off"/>
				<img src="/assets/icons/btn_loader.gif" class="input_loader TSO2_tbl_select_loader" style="display: none;">
			</div>
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">PO Date	</lable>
				<input type="date" id="po_date" name="po_date" value="{{!empty(@$search_session['search']['po_dt']) ? $search_session['search']['po_dt'] : ''}}" class="cinput_field search_field"/>
			</div>
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">PO Type2	</lable>
				<select class="cinput_field search_field search_field" id="po_type2">
					<option value=""></option>
					<option value="O" @if(@$search_session['search']['po_type2'] == 'O') {{ 'selected' }} @endif>Open</option>
					<option value="C" @if(@$search_session['search']['po_type2'] == 'C') {{ 'selected' }} @endif>Close</option>
				</select>
			</div>
			<div class="col-sm-6 col-md-3 cfieldmain">
				<lable class="cinput_lable">Type</lable>
				<select class="cinput_field search_field search_field" id="type">
					<option value=""></option>
					@foreach($types as $type)
						<option value="{{ $type->control_code }}" @if(@$search_session['search']['po_unit_type'] == $type->control_code) {{ 'selected' }} @endif>{{ $type->description }}</option>
					@endforeach
				</select>
			</div>
			
			<div class="terms_search_btn_area">
				<img src="/assets/icons/btn_loader.gif" class="input_loader po_search_loader" style="display: none; position: unset;">
				<button type="button" class="flx_btn" onclick="return getTableData();"> Search </button>
				<button type="button" class="flx_btn" onclick="return resetSearch();"> Reset </button>
			</div>
		</div>
		<!-- /Filter Form -->
		
		<!-- Filter Datas In Table -->
		<div class="row">
			<div class="span12 columns flexigrid_table_res col-md-12">
				<div class="well">
					<div id='list-report-error' class='report-div error'></div>
					<div id='list-report-success' class='report-div success report-list' ></div>
					<div class="flexigrid testtable" style='width: 100%;'>
						<div class="mDiv">
							<div class="ftitle">
								&nbsp;
							</div>
							<div title="Minimize/Maximize" class="ptogtitle">
								<span></span>
							</div>
						</div>
						<div id='main-table-box' class="main-table-box">
							<div class="tDiv">
								<div class="tDiv2">
									<a href="{{ url('purchaseorder/create?_ts='.@$_REQUEST['_ts']) }}" title='Add Accounts' class='add-anchor add_button'>
										<div class="fbutton">
											<div>
												<span class="add"></span>
											</div>
										</div>
									</a>
									<div class="btnseparator">
									</div>
								</div>
								<!--<div class="tDiv3">
									<a class="export-anchor" data-url="https://sbidevelopers.000webhostapp.com/accounts_managment/accounts/index/export" target="_blank">
										<div class="fbutton">
										<div>
											<span class="export"></span>
										</div>
										</div>
									</a>
									<a class="print-anchor" data-url="accounts/index/print.html">
										<div class="fbutton">
										<div>
											<span class="print"></span>
										</div>
										</div>
									</a>
								</div>-->
								<!-- By Atul-->
									<form target="_blank" method="post" action="{{ url('/purchaseorder/print-reports') }}?_ts={{@$_REQUEST['_ts']}}" id="ind_print" style="display: inline-block; vertical-align: top;">
										{{ csrf_field() }}
										<input type="hidden" id="ind_selected_ids" name="ind_selected_ids">
										<button class="ind_print_btn" type="submit" disabled></button>
									</form>
									<!-- End Here-->
								<div class='clear'></div>
							</div>
							<div id='ajax_list' class="ajax_list">
								<div class="bDiv" >
									<table id="flex1">
										
									</table>
								</div>
							</div>
							<div class="sDiv quickSearchBox" id='quickSearchBox' style="display:none;">
								<input type="hidden" id="checked_ids" />
								<div class="sDiv2">
									Search: <input type="text" class="qsbsearch_fieldox search_text" name="search_text" size="30" id='sea_field_val'>
									<select name="search_field" id="search_field">
										<option value="">Search all</option>
										<option value="unit_cd">Unit Code</option>
										<option value="po_status">PO Status</option>
										<option value="po_dt">PO Date</option>
										<option value="valid_fr">Valid From</option>
										<option value="valid_to">Valid To</option>
										<option value="amd_dt">Amendment Date</option>With Effect Of
										<option value="amd_wef">With Effect Of</option>
										<option value="del_term">Delivery Term</option>
										<option value="pf_type">P/F Type</option>
										<option value="ins_per">AMT% PF Charge</option>
										<option value="ins_cd">Ins Code</option>
										<option value="ins_amt">Ins AMT</option>
										<option value="po_head_others">Others</option>
										<option value="ship_mode">Mode Of Dispatch</option>
										<option value="freight">Freight By</option>
										<option value="curr_rate">Currency</option>
										<option value="our_bank">Our Bankers</option>
										<option value="our_bank_add">Our Bankers Address</option>
										<option value="our_bank_ac">Our Bank A/C.</option>
										<option value="desti">Destination</option>
										<option value="transporter">Transporter</option>
										<option value="disp_inst">Dispatch Instruction</option>
										<option value="jo_po">Project/ Job No.</option>
										<option value="tot_per">Tolerance+(%) (For Receiving)</option>
										<option value="tolerance_remark">Remarks</option>
										<option value="po_value">PO Value</option>
										<option value="price_basis">Price Basis</option>
										<option value="freight_rem">Freight Remarks</option>
									</select>
									<input type="button" value="Search" class="crud_search" id='crud_search' onclick="return getTableData(); ">
								</div>
								<div class='search-div-clear-button'>
									<input type="button" value="Clear filtering" id='search_clear' class="search_clear" onclick="return resetSearch();">
								</div>
							</div>
							<div class="pDiv">
								<div class="pDiv2">
									<div class="pGroup">
										<div class="pSearch pButton" onclick=" return filterShowHide('testtable');"><span></span></div>
									</div>
									<div class="btnseparator"></div>
									<div class="pGroup">
										<span class="pcontrol">
										Show 					
										<select name="per_page" id='per_page' class="per_page" onchange="return getTableData();">
											<option value="10" selected="selected">10&nbsp;&nbsp;</option>
											<option value="25" >25&nbsp;&nbsp;</option>
											<option value="50" >50&nbsp;&nbsp;</option>
											<option value="100" >100&nbsp;&nbsp;</option>
										</select>
										entries					<input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting' value='' />
										<input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'  value=''/>
										</span>
									</div>
									<div class="btnseparator"></div>
									<div id="data_pagination">
										<div class="pGroup">
											<div class="pFirst pButton first-button">
											<span></span>
											</div>
											<div class="pPrev pButton prev-button">
											<span></span>
											</div>
										</div>
										<div class="btnseparator"></div>
										<div class="pGroup">
											<span class="pcontrol">Page <input name='page' type="text" value="1" size="4" id='crud_page' class="crud_page">
											of				<span id='last-page-number' class="last-page-number">1</span></span>
										</div>
										<div class="btnseparator"></div>
										<div class="pGroup">
											<div class="pNext pButton next-button" >
											<span></span>
											</div>
											<div class="pLast pButton last-button">
											<span></span>
											</div>
										</div>
										<div class="btnseparator"></div>
									</div>

									<div class="pGroup">
										<div class="pReload pButton ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
										<span></span>
										</div>
									</div>
									<div class="btnseparator">
									</div>
									<div class="pGroup">
										<span class="pPageStat">
										Displaying <span id='displayfrom' class='page-starts-from'>1</span> to <span id='displayto' class='page-ends-to'>3</span> of <span id='total_items' class='total_items'>3</span> items				</span>
									</div>
								</div>
								<div style="clear: both;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Filter Datas In Table -->
		
	</section>
</div><!-- /.content-wrapper -->
           
@endsection

@section('scripts')
	<script src="{{ URL::asset('assets/js/Material/PurchaseOrder/ctable_script.js') }}"></script>
	<script>
	jQuery(document).ready(function(){
		// If check search data in session then get searchable data
    	getDataBySearchInSession();
	});
	</script>
@endsection