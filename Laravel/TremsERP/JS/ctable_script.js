// Search hide show 
function filterShowHide(table) {
    jQuery("." + table + " #quickSearchBox").fadeToggle("slow");
}

// Check search field in session then get data
function getDataBySearchInSession(){
    var search_status = false;
    jQuery(".search_field").each(function(){
        var search_data = jQuery(this).val();
        if(search_data != ''){
            search_status = true;
        }
        console.log(search_data);
    });
    console.log(search_status);
    if(search_status == true){
        getTableData();
    }else{
        getTableColumns();
    }
}

// Get table data
function getTableData() {
    jQuery(".po_search_loader").show();
    var search_field = jQuery("#search_field").val();
    var sea_field_val = jQuery("#sea_field_val").val();
    var per_page = jQuery("#per_page").val();
    var po_no = jQuery("#TSO1_po_query_view_po_no").val();
    var po_type = jQuery("#po_type").val();
    var vendor_code = jQuery("#TSO2_vendor_detail_view_vendor_code").val();
    var po_date = jQuery("#po_date").val();
    var po_type2 = jQuery("#po_type2").val();
    var type = jQuery("#type").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/purchaseorder/data',
        data: { '_ts':_ts,'po_no': po_no, 'po_type': po_type, 'vendor_code': vendor_code, 'po_date': po_date, 'po_type2': po_type2, 'type': type, 'search_field': search_field, 'sea_field_val': sea_field_val, 'per_page': per_page, 'sorting': 'po_id', 'order': 'DESC' },
        success: function(res) {
            jQuery(".po_search_loader").hide();
            if (res.status == 1) {
                console.log(res);
                jQuery(".testtable table").html(res.data);
                jQuery("#data_pagination").html(res.pagination);
                jQuery("#total_items").text(res.total_items);
                jQuery("#displayto").text(res.displayto);
                jQuery("#displayfrom").text(res.displayfrom);
            } else if (res.status == 0) {
                var message = '<tr><td colspan="15"><center>Data Not Found!</center></td></tr>';
                jQuery(".testtable table").html(message);
            }
        }
    });
}

// Get item rate history
function getItemRateHistory(rowid) {
    var item_code = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").val();
    if(item_code == ''){
        jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").focus();
        return false;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'get',
        url: baseUrl + '/purchaseorder/ratehistory',
        data: { '_ts':_ts,'item_code': item_code},
        success: function(res) {
            if (res.status == 1) {
                console.log(res);
                jQuery("#item_rate_history_"+rowid).html(res.data);
                jQuery("#item_RH_modal_"+rowid).modal('show');
            } else if (res.status == 0) {
                var message = '<tr><td colspan="9"><center>'+res.message+'</center></td></tr>';
                jQuery(".testtable table").html(message);
            }
        }
    });
}

// Get table columns
function getTableColumns(sessionrest=0) {
    jQuery(".po_search_loader").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/purchaseorder/get_columns',
        data: { '_ts':_ts,'columns': 1, 'sessionrest':sessionrest},
        success: function(res) {
            jQuery(".po_search_loader").hide();
            if (res.status == 1) {
                console.log(res);
                jQuery(".testtable table").html(res.data);
                jQuery("#data_pagination").html(res.pagination);
                jQuery("#total_items").text('0');
                jQuery("#displayto").text('0');
                jQuery("#displayfrom").text('0');
            } else if (res.status == 0) {
                var message = '<tr><td colspan="15"><center>Data Not Found!</center></td></tr>';
                jQuery(".testtable table").html(message);
            }
        }
    });
}

// Get table data with pagination
function getTableDataByPage(PAGENO) {
    var per_page = jQuery("#per_page").val();
    var search_field = jQuery("#search_field").val();
    var sea_field_val = jQuery("#sea_field_val").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/purchaseorder/data',
        data: { '_ts':_ts,'per_page': per_page, 'page': PAGENO, 'search_field': search_field, 'sea_field_val': sea_field_val, 'sorting': primaryKey, 'order': 'DESC' },
        success: function(res) {
            if (res.status == 1) {
                console.log(res);
                jQuery(".testtable table").html(res.data);
                jQuery("#data_pagination").html(res.pagination);
                jQuery("#displayto").text(res.displayto);
                jQuery("#displayfrom").text(res.displayfrom);
            } else if (res.status == 0) {
                var message = '<tr><td colspan="15"><center>Data Not Found!</center></td></tr>';
                jQuery(".testtable table").html(message);
            }
        }
    });
}


// Reset Search
function resetSearch() {
    jQuery("#TSO1_po_query_view_po_no").val('');
    jQuery("#po_type").val('');
    jQuery("#TSO2_vendor_detail_view_vendor_code").val('');
    jQuery('#po_date').val('');
    jQuery('#po_type2').val('');
    jQuery('#type').val('');
    //jQuery('#sub_grp_cd').val('').trigger('change');
	jQuery(".ind_print_btn").prop("disabled", true);
    getTableColumns(1);
}

// Vailidate form data
function vailidateData() {
    var flag = 0;
    jQuery(".validate_error").html('');

    // Group Code
    var group_code = jQuery("select[name=group_code]").val();
    if (group_code == '') {
        jQuery("select[name=group_code]").parent().find(".group_code").text('Group code is required!');
        flag = 1;
    }
    return flag;
}

//open schedule popup
function openSchedulepopup(rowid){
    var item_code = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").val();
    var item_desc = jQuery("#TSOI2-"+rowid+"_item_stock_hsn_view_item_desc").val();
    var item_qty = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_bal_qty").val();
    if(item_qty < 0 || item_qty == ''){
        return false;
    }
    jQuery('.item_name').text(item_code)
    jQuery('.item_desc').text(item_desc)
    jQuery('.item_qty').text(item_qty)
    var error_status = 0;
    if(item_code == ''){
        //openError('Please select item first!');
        jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").focus();
        jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").css({"border":"1px solid red"});
        error_status = 1;
    }

    if(error_status == 0){
        jQuery("#TSOI2"+rowid+"_po_indent_item_view_item_cd").css({"border":"1px solid #555"});
        jQuery("#schedule-"+rowid).modal('show');
    }
    $("#schedule-"+rowid).modal({backdrop: 'static', keyboard: false}, 'show');
}

// Open popup item remarks
function openItemRemarksPopup(rowid){
    var indent_no = jQuery("#TSOI1-"+rowid+"_po_indent_view_ind_no").val();
    var item_code = jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").val();
    var error_status = 0;
    if(indent_no == ''){
        //openError('Please select indent first!');
        jQuery("#TSOI1-"+rowid+"_po_indent_view_ind_no").focus();
        jQuery("#TSOI1-"+rowid+"_po_indent_view_ind_no").css({"border":"1px solid red"});
        //error_status = 1;
    }else if(item_code == ''){
        //openError('Please select item first!');
        jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").focus();
        jQuery("#TSOI2-"+rowid+"_po_indent_item_view_item_cd").css({"border":"1px solid red"});
        //error_status = 1;
    }
    if(error_status == 0){
        jQuery("#TSOI2"+rowid+"_po_indent_item_view_item_cd").css({"border":"1px solid #555"});
        jQuery("#TSOI1-"+rowid+"_po_indent_view_ind_no").css({"border":"1px solid #555"});
        jQuery("#item_remarks_modal_"+rowid).modal('show');
    }
}

// Close popup item remarks
function closeItemRemarksPopup(rowid){
    jQuery("#item_remarks_modal_"+rowid).modal('hide');
}

// Open popup item Specf
function openItemSpecfPopup(rowid){
    jQuery("#item_specf_modal_"+rowid).modal('show');
}

// Close popup item Specf
function closeItemSpecfPopup(rowid){
    jQuery("#item_specf_modal_"+rowid).modal('hide');
}

// Open popup item RH
function openItemRHfPopup(rowid){
    jQuery("#item_RH_modal_"+rowid).modal('show');
}

// Close popup item RH
function closeItemRHPopup(rowid){
    jQuery("#item_RH_modal_"+rowid).modal('hide');
}

// Open popup payment term
function openPaymentTerms(){
    //jQuery("#payment_terms_modal").modal('show');
    jQuery("#payment_terms_modal").modal({
        backdrop: 'static',
        keyboard: true, 
        show: true
});
}

// Close popup payment term
function closePaymentTerms(){
    var noofrowadded = jQuery(".pay_term_percentage").length;
    if(noofrowadded == 0){
        jQuery("#payment_terms_modal").modal('hide');
        return false;
    }
    var percentage = 0;
    jQuery(".pay_term_percentage").css({"border":"1px solid #555"});
    jQuery(".pay_terms_per_error").hide();
    jQuery(".pay_term_percentage").each(function(){
        percentage += parseFloat(jQuery(this).val());
    });
    if(percentage == 100){
        jQuery("#payment_terms_modal").modal('hide');
    }else{
        jQuery(".pay_term_percentage").css({"border":"1px solid red"});
        jQuery(".pay_terms_per_error").show();
    }    
}

// Close popup item details
function closeItemDetails(){
    var jo_po = jQuery("#jo_po").val();
    var po_type = jQuery("#po_type").val();
    check_status = 1;
    if(jo_po == 'J' && po_type == 'JW'){
        jQuery(".check_proc_seq").each(function(){
            var seq = jQuery(this).val();
            if(seq == 0){
                check_status = 0;
                jQuery(this).css({"border":"1px solid red"});
                openError('Process sequence must be greater than 0 in case of po category is jobwork and po type is jobwork order!');
                return false;
            }else{
                check_status = 1;
                jQuery(this).css({"border":"1px solid #555"});
            }
        });
    }   
    if(check_status == 1){
        jQuery("#item_details_modal").modal('hide');
    }
}

// indentNoRequired
function indentNoRequired(rowid){
    jQuery("#TSOI1-"+rowid+"_po_indent_view_ind_no").css({"border":"2px solid red"});
}

// po_type Vailidation 
function POTypeVailidation(){
    jQuery("#po_type").css({"border":"1px solid #555"});
    var po_type = jQuery("#po_type").val();
    var jo_po = jQuery("#jo_po").val();

    if(jQuery.trim(jo_po) == 'P'){
        jQuery("#po_type").val('IN');
    }else if(jQuery.trim(jo_po) == 'J'){
        jQuery("#po_type").val('JW');
    }
}

//Po Category Vailidation 
function POCatVailidation(){
    //jQuery("#po_type").css({"border":"1px solid #555"});
    var po_type = jQuery("#po_type").val();
    var jo_po = jQuery("#jo_po").val();

    if(jQuery.trim(jo_po) == 'P'){
        if(jQuery.trim(po_type) == 'JW' || jQuery.trim(po_type) == 'RP' || jQuery.trim(po_type) == 'JT'){
            //jQuery("#po_type").css({"border":"1px solid red"});
            jQuery("#po_type").val('IN');
            openError('This type is not allowed for Purchase Order');
        }
    }else if(jQuery.trim(jo_po) == 'J'){
        if(jQuery.trim(po_type) == 'IN' || jQuery.trim(po_type) == 'RM' || jQuery.trim(po_type) == 'CA' || jQuery.trim(po_type) == 'IM' || jQuery.trim(po_type) == 'CN'){
            //jQuery("#po_type").css({"border":"1px solid red"});
            openError('Only JobWork / Repair & Maintenance Type is Allowed for JobWork Order');
            jQuery("#po_type").val('JW');
        }
    }
    clearItemDetails();
}

// Enable popup buts
function enablePopupButton(){
    var loc = jQuery("#TSO6_location_locat_code").val();
    var ven_code = jQuery("#TSO1_vendor_master_view_vendor_code").val();
    if(loc != "" && ven_code != ""){
        jQuery(".ven_code_loc_en").removeClass('disabled');
    }
}

// Vailidate from date and to date
function VailidateFromToDate(){
    jQuery("#valid_fr").css({"border":"1px solid #555"});
    jQuery("#valid_to").css({"border":"1px solid #555"});
    var fdate = jQuery("#valid_fr").val();
    var tdate = jQuery("#valid_to").val();
    if(fdate != '' && tdate != ''){
        if(new Date(fdate) >= new Date(tdate)){
            jQuery("#valid_fr").css({"border":"1px solid red"});
            jQuery("#valid_fr").val('');
            openError('Valid from can not be greater than valid to!');
        }else if(new Date(tdate) < new Date(fdate)){
            jQuery("#valid_to").css({"border":"1px solid red"});
            jQuery("#valid_to").val('');
            openError('Valid to can not be less than valid from!');
        }
    }
}

// Vailidate Amend With Effect Of should be Valid From and Valid To in between only
function VailidateAmdEffDateValid(element, header=0){
    var fdate = jQuery("#valid_fr").val();
    var tdate = jQuery("#valid_to").val();
    var amd_wef = element.value;//jQuery("#amd_wef").val();
    jQuery(element).css({"border":"1px solid #555"});
    if(fdate != '' && tdate != ''){
        if(new Date(fdate) > new Date(amd_wef)){
            jQuery(element).css({"border":"1px solid red"});
            jQuery(element).val('');
            if(header != 0){
                openError(header+' can not be less than valid from!');
            }else{
                openError('Amend With Effect date can not be less than valid from!');
            }
            
        }else if(new Date(tdate) < new Date(amd_wef)){
            jQuery(element).css({"border":"1px solid red"});
            jQuery(element).val('');
            if(header != 0){
                openError(header+' can not be greater than valid to!');
            }else{
                openError('Amend With Effect date can not be greater than valid to!');
            }
        }
    }
}

// Vailidate Valid From Of should be greater than Valid From at the time of amnt
function VailidateValidFrom(element){
    return false;
    var fdate = jQuery("#valid_fr").val();
    var tdate = jQuery("#valid_fr_val").val();
    jQuery(element).css({"border":"1px solid #555"});
    if(fdate != '' && tdate != ''){
        if(new Date(fdate) < new Date(tdate)){
            jQuery(element).css({"border":"1px solid red"});
            jQuery(element).val('');
            openError('Valid From date should be greater than or equal only from previus valid from date.!');
        }
    }
}

// This function use for remove enter item details
function clearItemDetails(){
    jQuery("#po_item_detail_main").html('');
}


// Save Data
$("form#create_po").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    openProcess();
    $.ajax({
        url: baseUrl + '/purchaseorder/save',
        type: 'POST',
        data: formData,
        success: function(res) {
            console.log(res)
            setTimeout(function() {
                closeProcess();
                if (res.status == 1) {
                    console.log(res);
                    openSuccess(res.message);
                } else {
                    openError(res.message);
                }
            }, 1500);
        },
        cache: false,
        contentType: false,
        processData: false
    });
});


function saveData() {
    var validate = vailidateData();
    if (validate == 0) {
        openProcess();
        var data = $("#create_part_mas").serialize();
        console.log(data);
        jQuery.ajax({
            type: 'POST',
            url: baseUrl + '/purchaseorder',
            data: data,
            success: function(res) {
                setTimeout(function() {
                    closeProcess();
                    if (res.status == 1) {
                        console.log(res);
                        openSuccess(res.message);
                    } else {
                        openError(res.message);
                    }
                }, 1500);
            }
        });
    }
}

// Update Data
function updateData(DATAID) {
    // alert(DATAID);
    var validate = vailidateData();
    if (validate == 0) {
        openProcess();
        var data = $("#update_part_mas").serialize();
        // alert(data);
        //console.log(data);
        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: baseUrl + '/purchaseorder/set_updateData',
            data: data,
            //  data: data,
            success: function(res) {
                console.log(res);
                setTimeout(function() {
                    closeProcess();
                    if (res.status == 1) {
                        console.log(res);
                        openSuccess(res.message);
                    } else {
                        openError(res.message);
                    }
                }, 1500);
            }
        });
    }
}

// get pay term by vendor code
function getPayTermByVenCode(vendor_code,name,state,city,address) {
    jQuery("#TSO10_vendor_master_view_vendor_code").val(vendor_code);
    jQuery("#TSO10_vendor_master_view_name").val(name);
    jQuery("#TSO10_vendor_master_view_city").val(city);
    jQuery("#TSO10_vendor_master_view_state").val(state);
    jQuery("#TSO10_vendor_master_view_address").val(address);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/purchaseorder/getpaymenttermbyven',
        data: { '_ts':_ts, 'vendor_code': vendor_code},
        success: function(res) {
            if(res.status == 1) {
                console.log(res);
                jQuery("#payment_terms_detail").html(res.data);
            }
        }
    });
}

jQuery(document).ready(function() {
    baseUrl = jQuery("#base_url").val();
    primaryKey = 'po_no';
	
	// Single check  By Atul
    jQuery("table").delegate('.item_check_box','change', function(){
        jQuery(".ind_print_btn").prop("disabled", false);
        var rowid = jQuery(this).attr('rowid');
        var checkstatus = jQuery(".item_check_box").prop('checked');
        jQuery(".item_check_box").prop('checked', false);
        jQuery(this).prop('checked', true);
        jQuery("#ind_selected_ids").val(rowid);
    });

    // Enable popup buts
    jQuery("#TSO6_location_locat_code").focus(function(){
        enablePopupButton();
    });

    jQuery("#TSO1_vendor_master_view_vendor_code").focus(function(){
        enablePopupButton();
    });

    // Sorting data in table by column
    jQuery("table").delegate('.field-sorting', 'click', function() {
        var crud_page = jQuery("#crud_page").val();
        var per_page = jQuery("#per_page").val();
        var search_field = jQuery("#search_field").val();
        var sea_field_val = jQuery("#sea_field_val").val();
        var FIELD = jQuery(this).attr('field');
        var order = jQuery(this).attr('order');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            type: 'POST',
            url: baseUrl + '/purchaseorder/data',
            data: { '_ts':_ts,'per_page': per_page, 'page': crud_page, 'search_field': search_field, 'sea_field_val': sea_field_val, 'sorting': FIELD, 'order': order },
            success: function(res) {
                if (res.status == 1) {
                    console.log(res);
                    jQuery(".testtable table").html(res.data);
                    jQuery("#data_pagination").html(res.pagination);
                    jQuery("#displayto").text(res.displayto);
                    jQuery("#displayfrom").text(res.displayfrom);
                } else if (res.status == 0) {
                    var message = '<tr><td colspan="15"><center>Data Not Found!</center></td></tr>';
                    jQuery(".testtable table").html(message);
                }
            }
        });
    });

});