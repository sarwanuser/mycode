// Load the success message 
function openSuccess(message, move_url = '') {

    jQuery(".suc_text_area").html(message);
    jQuery("#move_url").val(move_url);
    jQuery('.terms_success_pop').bPopup({ modalClose: false });
}

//custom popup
function customSuccess(message) {
    jQuery(".suc_text_area").html(message);
    jQuery('.terms_success_pop').bPopup({ modalClose: false });
}

// Close the success message 
function closeSuccess() {
    jQuery('.terms_success_pop').bPopup().close();
}

// Load the Error message 
function openError(message) {
    jQuery(".error_text_area").html(message);
    jQuery('.terms_error_pop').bPopup({ modalClose: false });
}

// Close the Error message 
function closeError() {
    jQuery('.terms_error_pop').bPopup().close();
}

// Load the process message 
function openProcess() {
    jQuery('.terms_process_pop').bPopup({ modalClose: false });
}

// Close the process message 
function closeProcess() {
    jQuery('.terms_process_pop').bPopup().close();
}

// Reload page
function pageefresh() {
    var move_url = jQuery("#move_url").val();
    var base_url = jQuery("#base_url").val();
    if (move_url != '') {
        console.log(base_url + move_url);
        window.location.href = base_url + move_url;
    } else {
        var module_url = jQuery("#module_url").val();
        window.location.href = module_url;
    }
}

// Get table select options data
function getTableSelectData(TSO_id, heading, view_name, showfields, searchfields, top, left, focus_id, para = {}, paraIn = {}, paraCOND = {}, hideColumns = {}, getValFunName = '', disabledRows = '', distinct = '', orderby = '', orderby2 = '') {
    jQuery("." + TSO_id + "_tbl_select_loader").show();
    jQuery(".tbl_select_option").hide();
    console.log(para);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/gettableoptions',
        async: false,
        data: { '_ts': _ts, 'para': para, 'focus_id': focus_id, 'TSO_id': TSO_id, 'view_name': view_name, 'showfields': showfields, 'searchfields': searchfields, 'paraIn': paraIn, 'paraCOND': paraCOND, 'hideColumns': hideColumns, 'getValFunName': getValFunName, 'disabledRows': disabledRows, 'distinct': distinct, 'orderby': orderby, 'orderby2':orderby2 },
        success: function(res) {
            if (res.status == 1) {
                console.log(res);
                //jQuery(".tbl_select_option").animate({ "top": top, "left": left });
                //jQuery(".tbl_select_option").show();
                jQuery(".tbl_select_search").val('');
                jQuery(".tbl_select_search_head").text(heading);
                jQuery(".data_tbl_area").html(res.data);
                jQuery("#tso_pagination").html(res.pagination);
                setTimeout(function() {
                    setThePositionTSOInMobileTab();
                    jQuery(".tbl_select_option").css("opacity", "1");
                    jQuery(".tbl_select_option").show();
                    jQuery(".tbl_select_search").focus();
                    jQuery("." + TSO_id + "_tbl_select_loader").hide();
                }, 500);
            } else if (res.status == 0) {
                openError('Data Not Found!');
            }
        }
    });
}

// Get table select options data by search
function getTableSelectDataBySearch(TSO_id, view_name, showfields, searchfields, key, focus_id, para = {}, page = 1, paraIn = {}, paraCOND = {}, hideColumns = {}, getValFunName = '', disabledRows = '', distinct = '', orderby = '', orderby2 = '') {
    //jQuery(".data_tbl_area").html('Loading...');
    console.log(para);
    jQuery(".tbl_select_search_loader").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/gettableoptions',
        async: false,
        data: { '_ts': _ts, para: para, 'focus_id': focus_id, 'TSO_id': TSO_id, 'view_name': view_name, 'showfields': showfields, 'searchfields': searchfields, 'search': key, 'page': page, 'paraIn': paraIn, 'paraCOND': paraCOND, 'hideColumns': hideColumns, 'getValFunName': getValFunName, 'disabledRows': disabledRows, 'distinct': distinct, 'orderby': orderby, 'orderby2':orderby2 },
        success: function(res) {
            jQuery(".tbl_select_search_loader").hide();
            if (res.status == 1) {
                console.log(res);
                jQuery(".data_tbl_area").html(res.data);
                jQuery("#tso_pagination").html(res.pagination);
                setThePositionTSOInMobileTab();
            } else if (res.status == 0) {
                openError('Data Not Found!');
            }
        }
    });
}

// Get table select options data by pagination
function getTableSelectDataByPage(page) {
    var view_name = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('view_name');
    var showfields = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('showfields');
    var searchfields = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('searchfields');
    var TSO_id = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('TSO_id');
    var focus_id = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('focus_id');
    var para = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('para');
    var paraIn = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('paraIn');
    var paraCOND = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('paraCOND');
    var hideColumns = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('hideColumns');
    var getValFunName = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('getValFunName');
    var disabledRows = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('disabledRows');
    var distinct = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('distinct');
    var orderby = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('orderby');
    var orderby2 = jQuery('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('orderby2');
    console.log(paraIn);
    var key = jQuery('.tbl_select_search').val();
    getTableSelectDataBySearch(TSO_id, view_name, showfields, searchfields, key, focus_id, para, page, paraIn, paraCOND, hideColumns, getValFunName, disabledRows, distinct, orderby, orderby2);
}



// Close this tbl select options
function closeTableSelectOptopn() {
    jQuery(".tbl_select_option").hide();
    jQuery(".tbl_select_option_p").hide();
    //jQuery(".tbl_select_option").bPopup().close();
    jQuery("body").removeClass('modal-open');
}

// Get the scroll top possition and set the TSO popup
function setThePositionTSOInMobileTab() {
    var scrolled_val = jQuery(document).scrollTop().valueOf();
    var popup_width = jQuery(".tbl_select_option").width();
    var possition_left = screen_size - popup_width;
    possition_left = possition_left / 2;
    jQuery(".tbl_select_option").css("top", scrolled_val + 100);
    jQuery(".tbl_select_option").css("left", possition_left);

    var popup_width_p = jQuery(".tbl_select_option_p").width();
    var possition_left_p = screen_size - popup_width_p;
    possition_left_p = possition_left_p / 2;
    jQuery(".tbl_select_option_p").css("top", scrolled_val + 100);
    jQuery(".tbl_select_option_p").css("left", possition_left_p);

    var popup_width_tsop = jQuery(".tso_possition").width();
    var possition_left_tsop = screen_size - popup_width_tsop;
    possition_left_tsop = possition_left_tsop / 2;
    jQuery(".tso_possition").css("top", scrolled_val + 100);
    jQuery(".tso_possition").css("left", possition_left_tsop);
}

// Vaue Not equal to zero.
function notZeroVallue(element) {
    if (element.value == 0 && element.value != '') {
        jQuery(element).val('');
        openError('Not accept Zero ( 0 ) Value!');
    }
}

// Change menu current status
function setSideManuOCStatus(thisele) {
    var current = jQuery(thisele).attr("current");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        type: 'POST',
        url: baseUrl + '/setSideManuOCStatus',
        async: false,
        data: { '_ts': _ts, 'current': current },
        success: function(res) {
            console.log(res);
            jQuery(".side_menu_hide_show").attr('current', res.menu_now);
        }
    });
}

// Check date formate
function isDate(txtDate) {
    var date = txtDate.split("/");
    var day = date[0];
    var month = date[1];
    var dateString = txtDate;
    var regex = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
    if (regex.test(dateString) || dateString.length == 0) {
        //alert("Please enter correct date");
        return false;
    }
    if (day > 31) {
        //alert("Please enter correct date");
        return false;
    }
    else if (month > 12) {
        //alert("Please enter correct date");
        return false;
    }
    return true;
}

jQuery(document).ready(function() {

    // Check input type date for valid date or not on blur event.
    jQuery("body").delegate('input[type="date"]', 'blur', function(index) {
        var thisvalue = jQuery(this).val();
        if (isDate(thisvalue) == false) {
            jQuery(this).val('');
        }
    });

    // Left menu hide show for deshtop
    jQuery(".side_menu_hide_show").click(function() {
        jQuery("#left").toggleClass('close_left_menu_d');
        jQuery("#content").toggleClass('expend_body_area_d');
        var btn_class = jQuery(this).find("i").attr('class');
        // if(btn_class == 'fa fa-bars'){
        //     jQuery(this).find("i").attr('class', 'fa fa-times');
        // }else{
        //     jQuery(this).find("i").attr('class', 'fa fa-bars');
        // }

    });

    jQuery(document).bind('keydown', function(e) {
        if (e.which == '27') {
            e.preventDefault();
            jQuery(".tbl_select_option").hide();
            jQuery(".ui-draggable").hide();
            jQuery(".tbl_select_option_p").hide();
        }
    });

    jQuery(window).scroll(function() {
        setThePositionTSOInMobileTab();
    });


    screen_size = jQuery(window).width();
    // Mobile menu show hide
    jQuery("#menu-toggle").click(function() {
        jQuery("body").toggleClass('sidebar-left-opened');
    });

    // Search input show and hide
    jQuery(".small_device_search").click(function() {
        jQuery("#t_code_search_field").toggle();
        jQuery(".ticode_list").show();
        jQuery(".top_search_box").toggleClass('open_search');
        jQuery(".top_search_box.open_search input").blur();
        setTimeout(function() {
            jQuery(".top_search_box.open_search input").focus();
        }, 100);
    });

    // Check internet connection
    setInterval(function() {
        var internet_status = navigator.onLine;
        //console.log(internet_status);
        if (internet_status === false) {
            jQuery(".net_not_available").show();
            jQuery("body").addClass('modal-open');
        } else {
            jQuery(".net_not_available").hide();
            jQuery("body").removeClass('modal-open');
        }
    }, 1000);

    // T-Code
    jQuery(document).on('click', function(e) {
        if (jQuery(e.target).closest(".top_search_box").length === 0 && jQuery(e.target).closest("#menu-toggle").length != 1) {
            jQuery(".ticode_list").hide();
        }

        // This is for hide left menu click on outside the left menu.
        if (jQuery(e.target).closest("#left").length === 0 && jQuery(e.target).closest("#menu-toggle").length != 1) {
            jQuery("body").removeClass('sidebar-left-opened');
        }

        // this is for hide search T-Code input field when click on outside this
        if (screen_size < 400) {
            if (jQuery(e.target).closest(".top_search_box").length === 0 && jQuery(e.target).closest(".small_device_search").length != 1) {
                jQuery(".top_search_box").removeClass('open_search');
                jQuery("#t_code_search_field").hide();
            }
        }
    });

    jQuery("#t_code_search_field").focus(function() {
        jQuery(".ticode_list").show();
    });

    // Get t-code by type key
    jQuery("#t_code_search_field").keyup(function(e) {
        var totalli = jQuery(".search_tcode_data li").length;
        current_index = 0;
        if (e.which == 13) {
            if (totalli == 1) {
                var tcode_link = jQuery(".search_tcode_data .tcode_link").attr('href');
                tcode_link = baseUrl + tcode_link;
                window.location.href = tcode_link;
                return false;
            }else{
                var tcode_link = jQuery(".search_tcode_data .tcode_link").attr('href');
                tcode_link = baseUrl + tcode_link;
                window.location.href = tcode_link;
                return false;
            }
        } else if (e.which == 40) {
            jQuery(".search_tcode_data .tcode_link:eq(" + current_index + ")").focus();
            return false;
        }
        jQuery(".tcode_search_loader").show();
        var char = jQuery(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            type: 'POST',
            async: false,
            url: baseUrl + '/gettcode',
            data: { '_ts': _ts, 'char': char },
            success: function(res) {
                jQuery(".tcode_search_loader").hide();
                if (res.status == 1) {
                    console.log(res);
                    var tcode_html = '';
                    var tcodes = res.data;
                    if (tcodes.length > 0) {
                        tcodes.forEach(function(element) {
                            tcode_html += `<li><a class="tcode_link" href="${element.flow_path}?_ts=${_ts}">${element.t_code}-${element.file_sname}</a></li>`;
                        });
                        jQuery(".ticode_list").show();
                        jQuery(".search_tcode_data").html(tcode_html);
                    } else {
                        jQuery(".search_tcode_data").html('');
                        jQuery(".ticode_list").hide();
                    }
                } else if (res.status == 0) {
                    openError('T-Code Not Found!');
                }
            }
        });
    });


    jQuery("html").keyup(function(e) {
        var totalli = jQuery(".search_tcode_data li").length;
        if (e.which == 40) {
            if (current_index == 0) {
                current_index++;
            }
            jQuery(".search_tcode_data .tcode_link:eq(" + current_index + ")").focus();

            if (totalli >= current_index) {
                current_index++;
            }
            return false;
        } else if (e.which == 38) {
            if (current_index >= 0) {
                current_index--;
            }
            jQuery(".search_tcode_data .tcode_link:eq(" + current_index + ")").focus();
        }
    });

    $('.numbers').keyup(function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $(".numbertoFixed").change(function() {
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

    // if check blank then set 0
    jQuery(".amount_field").each(function() {
        var amt = jQuery(".amount_field").val();
        if (amt == '') {
            jQuery(".amount_field").val(0);
        }
    });
    jQuery("body").delegate('.amount_field', 'keyup', function() {
        var amt = jQuery(this).val();
        if (amt == '') {
            jQuery(this).val(0);
        }
    });

    //prevnent type input field
    jQuery(".tbl_select").prop('readonly', 'true');


    // This code for active mene open
    jQuery(".menu_active_c").parent().parent().addClass('active');
    jQuery(".menu_active_c").parent().addClass('show');

    jQuery(".menu_active_c").parent().parent().parent().parent().addClass('active');
    jQuery(".menu_active_c").parent().parent().parent().addClass('show');

    jQuery(".menu_active_c").parent().parent().parent().parent().parent().parent().addClass('active');
    jQuery(".menu_active_c").parent().parent().parent().parent().parent().addClass('show');

    jQuery("#left .parent_menu").first().addClass('active');
    jQuery("#left .collapse").first().addClass('show');

    // Select keyword wise in table select
    jQuery("body").delegate('.tbl_select_search', 'keyup', function() {
        var view_name = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('view_name');
        var showfields = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('showfields');
        var searchfields = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('searchfields');
        var TSO_id = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('TSO_id');
        var focus_id = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('focus_id');
        var para = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('para');
        var paraIn = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('paraIn');
        var paraCOND = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('paraCOND');
        var hideColumns = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('hideColumns');
        var getValFunName = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('getValFunName');
        var disabledRows = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('disabledRows');
        var distinct = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('distinct');
        var orderby = jQuery(this).parents('.tbl_select_option').find('.tbl_select_option_item_tbl').attr('orderby');
        var key = jQuery(this).val();
        getTableSelectDataBySearch(TSO_id, view_name, showfields, searchfields, key, focus_id, para, '1', paraIn, paraCOND, hideColumns, getValFunName, disabledRows, distinct, orderby);
    });

    // Select tbl_select item and fill this value
    jQuery("body").delegate('.option_item', 'click', function() {
        var x = 1;
        jQuery(this).find("td").each(function() {
            var class_n = jQuery(this).attr("class");
            var class_val = jQuery(this).text();
            jQuery("#" + class_n).val(class_val);
            if (x == 1) {
                focus_id = jQuery(this).attr("focus_id");
            }
            console.log(class_n);
            x++;
        });
        jQuery("#" + focus_id).focus();
        closeTableSelectOptopn();
    });

    // Draggable Option Popup
    jQuery(".tbl_select_option").draggable();

    jQuery('.select2').select2();



    // Not put the -value in input type number
    jQuery("body").delegate('input[type="number"]', 'keyup', function(index) {
        if (index.which == 189 || index.which == 69 || index.which == 109) {
            jQuery(this).val('');
        }
    });

    // Not put the -value in input type number with change event
    jQuery("body").delegate('input[type="number"]', 'change', function(index) {
        var thisvalue = jQuery(this).val();
        if (thisvalue < 0) {
            jQuery(this).val('');
        }
    });

    // Max char check with keypress event
    jQuery("body").delegate('input[t-maxc]', 'keypress', function() {
        var max = jQuery(this).attr('t-maxc');
        var clenght = jQuery(this).val().length + 1;
        if (max < clenght) {
            //jQuery(this).val('');
            return false;
        }
    });

    // Max char check with keypress event for textarea tag
    jQuery("body").delegate('textarea[t-maxc]', 'keypress', function() {
        var max = jQuery(this).attr('t-maxc');
        var clenght = jQuery(this).val().length;
        if (max < clenght) {
            //jQuery(this).val('');
            return false;
        }
    });


    // Max char check with change event
    jQuery("body").delegate('input[t-maxc]', 'change', function(index) {
        var max = jQuery(this).attr('t-maxc');
        var clenght = jQuery(this).val().length;
        if (max < clenght) {
            //jQuery(this).val('');
            return false;
        }
    });

    /* for cursor should not move on readonly field*/
     $('input[readonly]').each(function(){
        if(!($(this).hasClass('tbl_select'))){
            $(this).attr('tabindex', '-1');
        }
        
    });


});