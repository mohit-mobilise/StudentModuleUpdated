var columnAlignment = 'center';
var columnAlignment = 'center';
var dateFormat = 'dd-MM-yyyy';
var jqGridDateFormat = 'd-m-Y';
var referenceDateFormat = 'dd/MM/yyyy';
var detailImageUrl = '/scripts/images/edit.jpeg';
var deleteImageUrl = '/scripts/images/edit.jpeg';
var hrAttendanceApplicationId = 12;
var dateTemplate = {formatter:'date', formatoptions:{newformat:jqGridDateFormat, defaultValue:'&nbsp;'}};
var numberTemplate = {formatter:'number', editrules:{number:true, required:true}};
var integerTemplate = {formatter:'integer', editrules:{integer:true, required:true}};

function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left, false);
}

function openWindow(url) {
    window.open(url, '_blank');
}

function openSameWindow(url) {
    window.open(url);
}

function openNewWindow(url) {
    window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
}


function viewopendeleteconfirm(str, title) {
    var r = confirm("Do you want to delete " + title + " ?")
    if (r == true) {
        window.location = str;
    }
}

function sendsmsinadmission(str) {
    document.forms["tablefrm"].action = str;
    document.forms["tablefrm"].submit();
}
function sendsmsinadmission_frmMain(str) {
    if (validateFormValue()) {
        document.forms["frmMain"].action = str;
        document.forms["frmMain"].submit();
    }
}

function selectvaluecheck(value) {
    if (value == undefined) {
        value = "-1";
    }
    return value;
}
function valuecheck(value) {
    if (value == undefined) {
        value = "";
    }
    return value;
}

function viewopenvalidateconfirm(str) {
    disablebuttonbyjq('savebtn');
    if (validateFormValue()) {
        var confirmation = confirm(str);
        if (!confirmation) {
            enablebuttonbyjq('savebtn');
        }
        return confirmation;
    }
    enablebuttonbyjq('savebtn');
    return false;
}

function viewopenconfirm(str, msg) {
    var r = confirm(msg)
    if (r == true) {
        window.location = str;
    }
}

function viewPopupEditor(context, resourceid, label) {
    var url = window.location.protocol + '//' + window.location.host + context + '/ResourceView?resource_id=' + resourceid;
    var left = window.width / 2 - 175;
    var top = 0;
    $('<div style="width: 350px; height: 250px"><iframe src="' + url + '" width="100%" frameborder="0" height="100%"></iframe></div>').dialog({
        width:500,
        height:420,
        top:0,
        title:label,
        closeOnEscape:false,
        position:[left, top],
        modal:true,
        show:{
            effect:"blind",
            duration:1000
        },
        hide:{
            effect:"blind",
            duration:1000
        }
    }).css({padding:0, overflow:'hidden'});
}


function showdiv(div) {
    document.getElementById(div).style.display = "block";
}
function hidediv(div) {
    document.getElementById(div).style.display = "none";
}

function payment_type_enable(target_value, div) {
    if (target_value == 1) {
        hide_jquery_div(div);
    } else {
        show_jquery_div(div);
    }

}
function show_jquery_div(div) {
    $("." + div).each(function () {
        $(this).show()
    });
}
function hide_jquery_div(div) {
    $("." + div).each(function () {
        $(this).hide()
    });
}

function saveValidateFormValue(frmname) {


    document.getElementById("savebtn").disabled = true;
    if (validateFormValue()) {
        document.forms[frmname].submit();
    } else {
        document.getElementById("savebtn").disabled = false;
    }
}

function getMultipleSelectBoxValue(sectionid) {
    var aSelBranchVal = '';
    var ai;

    for (ai = 0; ai < sectionid.options.length; ai++) {
        if (sectionid.options[ai].selected && sectionid.options[ai].value > 0) {
            if (aSelBranchVal == '') {
                aSelBranchVal = sectionid.options[ai].value;
            } else {
                aSelBranchVal = aSelBranchVal + "," + sectionid.options[ai].value;
            }

        }
    }
    return aSelBranchVal;
}
function getMultipleSelectBoxStringValue(sectionid) {
    var aSelBranchVal = '';
    var ai;

    for (ai = 0; ai < sectionid.options.length; ai++) {
        if (sectionid.options[ai].selected) {
            if (aSelBranchVal == '') {
                aSelBranchVal = sectionid.options[ai].value;
            } else {
                aSelBranchVal = aSelBranchVal + "," + sectionid.options[ai].value;
            }

        }
    }
    return aSelBranchVal;
}


$(function () {

    $('.date').datepicker({
        showOn:"button",
        buttonImage:"/scripts/calender.png",
        buttonImageOnly:true,
        changeMonth:true,
        changeYear:true,
        showButtonPanel:true,
        showOtherMonths:true,
        selectOtherMonths:true
    });

    $('.datetime').datetimepicker({
        timeFormat:'HH:mm',
        stepHour:1,
        stepMinute:1,
        showOn:"button",
        buttonImage:"/scripts/calender.png",
        buttonImageOnly:true,
        changeMonth:true,
        changeYear:true,
        showButtonPanel:true,
        showOtherMonths:true,
        selectOtherMonths:true
    });

    $('.time').timepicker({
        timeFormat:'HH:mm',
        stepHour:1,
        stepMinute:1,
        showOn:"button",
        buttonImage:"/scripts/calender.png",
        buttonImageOnly:true,
        showButtonPanel:true
    });

    $(".fromdate").datepicker({
        defaultDate:"+1w",
        showOn:"button",
        buttonImage:"/scripts/calender.png",
        buttonImageOnly:true,
        changeMonth:true,
        changeYear:true,
        showButtonPanel:true,
        showOtherMonths:true,
        selectOtherMonths:true,
        numberOfMonths:3,
        onClose:function (selectedDate) {
            $(".todate").datepicker("option", "minDate", selectedDate);
        }
    });
    $(".todate").datepicker({
        defaultDate:"+1w",
        showOn:"button",
        buttonImage:"/scripts/calender.png",
        buttonImageOnly:true,
        changeMonth:true,
        changeYear:true,
        showButtonPanel:true,
        showOtherMonths:true,
        selectOtherMonths:true,
        numberOfMonths:3,
        onClose:function (selectedDate) {
            $(".fromdate").datepicker("option", "maxDate", selectedDate);
        }
    });


    $('input.valid-number').bind('keypress', function (e) {
        return ( e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) ? false : true;
    });
    $('input.valid-decimal').keyup(function () {
        var val = $(this).val();
        if (isNaN(val)) {
            val = val.replace(/[^0-9\.]/g, '');
            if (val.split('.').length > 2)
                val = val.replace(/\.+$/, "");
        }
        $(this).val(val);
    });
});

function showReport(url) {
    if (url != "-1") {
        openSameWindow(url);
    }
}

function calculateValue(sum_class, value_div) {

    var totalpaidamount = 0;
    $("input." + sum_class).each(function () {
        var value = $(this).val(); //do something with

        if (value != '') {
            totalpaidamount = parseFloat(totalpaidamount) + parseFloat(value);
        }
    });

    document.getElementById(value_div).innerHTML = parseFloat(totalpaidamount);
}

$(function () {
    var icons = {
        header:"ui-icon-circle-arrow-e",
        activeHeader:"ui-icon-circle-arrow-s"
    };
    $("#accordion").accordion({
        collapsible:true,
        icons:icons,
        heightStyle:"content",
        active:501
    });
    $("#accordion2").accordion({
        collapsible:true,
        icons:icons,
        heightStyle:"content",
        active:501
    });
});

function table_view_filter() {
    var filter = '';
    $(".table_filter").each(function () {
        var value = $(this).val(); //do something with
        var name = $(this).prop('id');
        if (filter.length == 0) {
            filter = name + "=" + value;
        } else {
            filter = filter + "&" + name + "=" + value;
        }
    });
    var formpath = document.getElementById("formpath").value;
    window.location = formpath + '?' + filter;
}

function table_view_export(formpath) {
    var filter = '';
    $(".table_filter").each(function () {
        var value = $(this).val(); //do something with
        var name = $(this).prop('id');
        if (filter.length == 0) {
            filter = name + "=" + value;
        } else {
            filter = filter + "&" + name + "=" + value;
        }
    });
    window.location = formpath + '?' + filter;
}
function table_view_export_new_page(formpath) {
    var filter = '';
    $(".table_filter").each(function () {
        var value = $(this).val(); //do something with
        var name = $(this).prop('id');
        if (filter.length == 0) {
            filter = name + "=" + value;
        } else {
            filter = filter + "&" + name + "=" + value;
        }
    });
    openWindow(formpath + '?' + filter);
}

function validateFormValue() {
    disablebuttonbyjq('savebtn');
    var validate = true;
    $("select.mandatoryvalue").each(function () {
        var value = $(this).val(); //do something with

        if (value == '' || value == -1) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly insert the " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $("input.mandatoryvalue").each(function () {
        var value = $(this).val().trim(); //do something with

        if (value == '') {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly insert the " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });


    $("textarea.mandatoryvalue").each(function () {
        var value = $(this).val().trim(); //do something with
        if (value == '') {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly insert the " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });


    $(".filesize").each(function () {
        var limitsize = $(this).prop('name');
        var file = $(this).prop('files')[0];
        if (file == NaN || file == undefined) {

        } else {
            var iFileSize = file.size; //do something with
            var iConvert = (iFileSize / 1024).toFixed(2);

            if (parseFloat(iConvert) > parseFloat(limitsize)) {
                var message = "Attachment size is " + iConvert + " KB . Attachment size should be less than " + limitsize + " KB"
                showErrorMessageDiv(message);
                validate = false;
                return false;
            }
        }

    });


    $("input.edu_range").each(function () {
        var value = $(this).val().trim(); //do something with

        if (value != '') {
            var min = $(this).prop('min');
            var max = $(this).prop('max');
            var title = $(this).prop('title');

            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            if(parseInt(min)>=value || parseInt(max)<=value)
            {
                var message = "Kindly insert the value of '" + title + "' between "+min+" to "+max+". ";
                showErrorMessageDiv(message);
                validate = false;
                return false;
            }



        }
    });

    $(".fileext").each(function () {

        var file = $(this).prop('files')[0];

        if (file == NaN || file == undefined) {

        } else {
            var extenstion = $(this).attr('ext');
            var res = extenstion.split(",");
            var ext = $(this).val().split('.').pop().toLowerCase();
            if ($.inArray(ext, res) == -1) {
                var message = "The Attachment should be in " + extenstion + " format.";
                showErrorMessageDiv(message);
                validate = false;
                return false;
            }
        }
    });


    $(".date").each(function () {
        var value = $(this).val(); //do something with
        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}$/;
        if (value.trim() != '' && !value.match(re)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly select the correct " + title + ". (dd-MM-yyyy)";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });
    $(".datetime").each(function () {
        var value = $(this).val(); //do something with
        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}\s*?\d{2}[- :.]\d{2}$/;
        if (value.trim() != '' && !value.match(re)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly select the correct " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });


    $(".numbervalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseInt(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".floatvalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseFloat(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Decimal or Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".mobileno").each(function () {
        var value = $(this).val().trim(); //do something with
        if (value.length > 0 && value.length != 10) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " should be 10 digits";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    if (!validate) {
        enablebuttonbyjq("savebtn")
        return false;

    } else {
        return true;
    }

}

function validateFormForRequest() {
    var validate = true;

    $(".date").each(function () {
        var value = $(this).val(); //do something with
        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}$/;
        if (value.trim() != '' && !value.match(re)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            validate = false;
            return false;
        }
    });
    $(".datetime").each(function () {
        var value = $(this).val(); //do something with
        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}\s*?\d{2}[- :.]\d{2}$/;
        if (value.trim() != '' && !value.match(re)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            validate = false;
            return false;
        }
    });


    $(".numbervalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseInt(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            validate = false;
            return false;
        }
    });

    $(".floatvalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseFloat(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            validate = false;
            return false;
        }
    });


    if (!validate) {
        return false;

    } else {
        return true;
    }

}
function messagecount(id1, id2) {
    var message = document.getElementById(id1).value;
    var count = 1;
    var length = 0;
    if (message != '') {
        length = message.length;
        var devide = parseFloat(length / 165);
        devide = parseFloat(devide) + parseFloat(.9999);
        count = parseInt(devide);
    }
    var countstr = length + '/' + count;
    document.getElementById(id2).innerHTML = countstr;
}

function getPaymentType(paymenttypeid_field) {

    var studenttypeid = document.getElementById("studenttypeid").value;
    var xmlHttp = getxmlhttpobject();
    var url = "StudentTypeDetail";
    url += "?id=" + studenttypeid;

    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            var json = eval('(' + xmlHttp.responseText + ')');
            var paymenttypeid = valuecheck(json.paymenttypeid);
            document.getElementById(paymenttypeid_field).value = paymenttypeid;
        }
    }
    xmlHttp.send(null);

}


function validateFormData() {
    var validate = true;

    $(".numbervalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseInt(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".floatvalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseFloat(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Decimal or Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    if (!validate) {
        return false;
    }
    return true;
}
function disablebuttonbyjq(name) {

    $("." + name).each(function () {
        $(this).prop("disabled", true);
    });

}
function hidebuttonbyjq(name) {

    $("." + name).each(function () {
        $(this).hide();
    });

}
function disablehyperlinkbyjq(name) {

    $("." + name).each(function () {
        $(this).prop("disabled", true);
        $(this).unbind('click');
        $(this).prop("onclick", null);
        $(this).addClass("hide-column");
    });

}
function enablebuttonbyjq(name) {
    $("." + name).each(function () {
        $(this).removeProp("disabled")
    });
}

function checkboxselect_unselect(classname, target) {
    var select_row = 0;
    $("." + classname).each(function () {

        if (target.checked) {
            $(this).prop('checked', true);
            $(this).value = 1;
            $(this).val(1);
            select_row = parseInt(select_row) + parseInt(1);
        } else {
            $(this).prop('checked', false);
            $(this).val(0);
            $(this).value = 0;
        }

    });
    $("#selectdata").val(select_row)

}

function checkboxselect_count(target) {
    var select_row = $("#selectdata").val();

    if (target.checked) {
        $(target).prop('checked', true);
        $(target).value = 1;
        $(target).val(1);
        select_row = parseInt(select_row) + parseInt(1);
    } else {
        $(target).prop('checked', false);
        $(target).val(0);
        $(target).value = 0;
        select_row = parseInt(select_row) - parseInt(1);
    }

    $("#selectdata").val(select_row);

}
function validateFormValueWithButton() {
    var validate = true;
    $("select.mandatoryvalue").each(function () {
        var value = $(this).val(); //do something with

        if (value == '' || value == -1) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly insert the " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $("input.mandatoryvalue").each(function () {
        var value = $(this).val(); //do something with
        if (value == '') {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly insert the " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".date").each(function () {
        var value = $(this).val(); //do something with
        var re = /^\d{1,2}(\-|\/)\d{1,2}(\-|\/)\d{4}$/;
        if (value != '' && !value.match(re)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly select the correct " + title + ".";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });
    $(".numbervalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseInt(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".floatvalue").each(function () {
        var value = $(this).val();
        if (value != '' && parseFloat(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Kindly fill the Decimal or Number value in '" + title + "'.";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    $(".mobileno").each(function () {
        var value = $(this).val().trim(); //do something with
        if (value.length > 0 && value.length != 10) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " should be 10 digits";
            showErrorMessageDiv(message);
            validate = false;
            return false;
        }
    });

    if (!validate) {
        return false;
    }
    hideErrorMessage();
    return true;
}

function selectedInvokeActionRefreshAndLoad(str, targeturl) {
    var url = str + "?" + tableformValues();

    selectpostRequest(url, targeturl);
}

function invokeActionRefreshAndLoad(str, targeturl) {
    selectpostRequest(str, targeturl);
}
function invokeActionConfirmationDeleteRefreshAndLoad(str, targeturl, title) {

    var r = confirm("Do you want to delete " + title + " ?")
    if (r == true) {
        selectpostRequest(str, targeturl);
    }
}

function rowActionConfirmationDeleteRefreshAndLoad(str, targeturl) {

    var r = confirm(str)
    if (r == true) {
        viewopen(targeturl);
    }
}

function selectpostRequest(strURL, targeturl) {
    var xmlHttp;
    if (window.XMLHttpRequest) { // For Mozilla, Safari, ...
        var xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) { // For Internet Explorer
        var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var result = xmlHttp.responseText;

            if (result == 'Login Id Created Successfully' || result == 'Successfully') {
                window.location = targeturl;
            } else {
                showErrorMessage(result)

            }
        }
    }
    xmlHttp.send(strURL);
}


function parsingInteger(value, iszero, fieldname) {
    if (parseInt(value) != value) {
        return "Kindly fill the Number value in '" + fieldname + "'.";
    } else if (iszero) {
        if (parseInt(value) < parseInt(0)) {
            return "Value of " + fieldname + " should be equal or greater than zero.";
        }
    }
    return "";
}

function changefile() {
    document.getElementById('changefile').style.display = "block";
    document.getElementById('existfile').style.display = "none";
    document.getElementById("filechange").value = 1;
}

function parsingFloat(value, iszero, fieldname) {
    if (value == '') {
    }
    else if (parseFloat(value) != value) {
        return "Kindly fill the Number value in '" + fieldname + "'.";
    } else if (iszero) {
        if (parseFloat(value) < parseFloat(0)) {
            return "Value of '" + fieldname + "' should be equal or greater than zero.";
        }
    }
    return "";
}

function showErrorMessage(message) {
    showErrorMessageDiv(message);
}


function showErrorMessageDiv(message) {
    document.getElementById('errormessagediv').style.display = "block";
    document.getElementById("errormessages").innerHTML = message;
}

function hideErrorMessageDiv() {
    document.getElementById('errormessagediv').style.display = "none";
    document.getElementById("errormessages").innerHTML = "";
}

function hideErrorMessage() {
    hideErrorMessageDiv();
}
function showSuccessMessage(message) {
    document.getElementById('successlabel').style.display = "block";
    document.getElementById("successmessage").innerHTML = message;
}
function hideSuccessMessage() {
    document.getElementById('successlabel').style.display = "none";
    document.getElementById("successmessage").innerHTML = "";
}
function hideErrorMessageAndshowSuccessMessage(message) {
    if (document.getElementById('errorlabel') != '') {
        hideErrorMessage();
    }
    showSuccessMessage(message);
}

function hideSuccessMessageAndshowErrorMessage(message) {
    if (document.getElementById('successlabel') != '') {
        hideSuccessMessage();
    }
    showErrorMessage(message);
}

function selectAppllication(str) {
    var applicationid = document.getElementById("applicationid").value;

    if (applicationid == -1 || applicationid == 'null') {
        return;
    }
    window.location = str + "?applicationid=" + applicationid;
}

function selectFamily(str) {
    var familyuserid = document.getElementById("familyuserid").value;

    if (familyuserid == -1 || familyuserid == 'null') {
        return;
    }
    window.location = str + "?familyuserid=" + familyuserid;
}

function checkboxvalue(target) {
    var checked = target.checked;
    if (target.checked) {
        target.value = 1;
    } else {
        target.value = 0;
    }
}
function tableformcheckboxvalue(target) {
    var checked = target.checked;
    if (target.checked) {
        var elementname = target.name;

        if (elementname.indexOf("select_") != -1) {
            var value = elementname.substring(7, elementname.length);
            target.value = value;
        } else {
            target.value = 1;
        }

    } else {
        target.value = 0;
    }
}

function allvalueselect(target) {

    var checked = target.checked;
    var value = 0;
    if (target.checked) {
        target.value = 1;
        value = 1;
    } else {
        target.value = 0;
        value = 0;
    }
    var elem = document.getElementById('tablefrm').elements;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.indexOf("select_") != -1) {
            elem[i].checked = checked;
            tableformcheckboxvalue(elem[i])
        }


    }
}

function allvalueselectforpost(target) {

    var checked = target.checked;
    var value = 0;
    if (target.checked) {
        target.value = 1;
        value = 1;
    } else {
        target.value = 0;
        value = 0;
    }
    var elem = document.getElementById('tablefrm').elements;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.indexOf("selectbox_") != -1) {
            elem[i].checked = checked;
            tableformcheckboxvaluepost(elem[i])
        }


    }
}

function dob_word() {
    var xmlHttp = getxmlhttpobject();
    var dateofbirth = document.getElementById("dateofbirth").value;

    var url = "__dobwords__.jsp";
    url += "?dob=" + dateofbirth;
    xmlHttp.open("GET", url, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/text');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            document.getElementById("dobwords").innerHTML = xmlHttp.responseText;

        }
    }
    xmlHttp.send(url);
}

function dob_word_dynamic(input_column, output_div) {
    var xmlHttp = getxmlhttpobject();
    var dateofbirth = document.getElementById(input_column).value;

    var url = "__dobwords__.jsp";
    url += "?dob=" + dateofbirth;
    xmlHttp.open("GET", url, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/text');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            document.getElementById(output_div).innerHTML = xmlHttp.responseText;

        }
    }
    xmlHttp.send(url);
}

function dob_word_inadmission() {
    var xmlHttp = getxmlhttpobject();
    var dateofbirth = document.getElementById("dateofbirth").value;
    var admissionid = document.getElementById("admissionid").value;

    var url = "__dobwords__.jsp";
    url += "?dob=" + dateofbirth + "&admissionid=" + admissionid;
    xmlHttp.open("GET", url, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/text');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            document.getElementById("dobwords").innerHTML = xmlHttp.responseText;

        }
    }
    xmlHttp.send(url);
}

function tableformcheckboxvaluepost(target) {
    var checked = target.checked;
    if (target.checked) {
        target.value = 1;
    } else {
        target.value = 0;
    }
}


function viewopen(str) {
    window.location = str;
}

function moveNext() {
    var counter = 1
    var limit = document.getElementById("limit").value;
    var maxlimit = document.getElementById("maxlimit").value;
    var resultsize = document.getElementById("resultsize").value;
    var formpath = document.getElementById("formpath").value;
    if (limit != 'null' && limit != '') {
        if (parseInt(maxlimit) == parseInt(resultsize)) {
            counter = parseInt(limit) + 1
        } else {
            counter = parseInt(limit)
        }
    }
    document.getElementById("limit").value = counter;
    var url = formpath + "?" + formValues();
    viewopen(url);
}

function movePrevious() {
    var counter = 1;
    var limit = document.getElementById("limit").value;
    var formpath = document.getElementById("formpath").value;
    if (limit != 'null' && limit != '' && parseInt(limit) > 1) {
        counter = parseInt(limit) - 1
    }

    document.getElementById("limit").value = counter;
    var url = formpath + "?" + formValues();
    viewopen(url);
}
function viewopen(str) {
    window.location = str;
}

function viewopenformvalue(str) {
    var url = str + "?" + formValues();
    window.location = url;
}

function viewopenformvalueAndValidate(str) {
    if (validateFormValueWithButton()) {
        var url = str + "?" + formValues();
        window.location = url;
    }
}

function viewopentableformvalueAndValidate(str) {
    if (validateFormValueWithButton()) {
        var url = str + "?" + tableformValues();
        window.location = url;
    }
}

function viewopenformvalueAndCondirmAndValidate(str, msg) {
    var r = confirm(msg)
    if (r == true) {
        viewopenformvalueAndValidate(str)
    }
}


function formValues() {
    var parameters = '';

    var elem = document.getElementById('frmMain').elements;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        var elementtype = elem[i].type;
        var multiple = elem[i].multiple;


        if (elementtype == 'checkbox' && elementname != '') {
            if (elem[i].checked) {
                elem[i].value = 1;
            } else {
                elem[i].value = 0;
            }
        }

        if (multiple != undefined && multiple != '') {
            parameters += elementname + "=" + getMultipleSelectBoxValue(elem[i]) + "&";
        } else if (elementname != '') {
            parameters += elementname + "=" + elem[i].value + "&";
        }
    }
    if (parameters.length > 1) {
        parameters = parameters.substring(0, parameters.length - 1);
    }
    return parameters;
}

function tableformValues() {
    var parameters = '';

    var elem = document.getElementById('tablefrm').elements;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname != '' && elementname.length > 0) {
            parameters += elementname + "=" + elem[i].value + "&";
        }
    }
    if (parameters.length > 1) {
        parameters = parameters.substring(0, parameters.length - 1);
    }
    return parameters;
}

function viewselectrecord(str) {
    window.location = str + "?" + tableformValues();
}
function viewselectrecordandconfirm(str, msg) {
    var r = confirm(msg)
    if (r == true) {
        window.location = str + "?" + tableformValues();
    }

}

function selectedInvokeAction(str) {
    var url = str + "?" + tableformValues();
    postRequest(url);
}
function selectedInvokeActionRefresh(str) {
    var url = str + "?" + tableformValues();
    postRequest(url);
    location.reload(true);
}

function postRequest(strURL) {
    var xmlHttp;
    if (window.XMLHttpRequest) { // For Mozilla, Safari, ...
        var xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) { // For Internet Explorer
        var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            document.getElementById('errorlabel').style.display = "block";
            document.getElementById("errormessage").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(strURL);
}


function tableformValues() {
    var parameters = '';

    var elem = document.getElementById('tablefrm').elements;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.indexOf("select_") != 1) {
            parameters += elementname + "=" + elem[i].value + "&";
        }
    }
    if (parameters.length > 1) {
        parameters = parameters.substring(0, parameters.length - 1);
    }
    return parameters;
}

function updateTableformValues() {
    var parameters = '';

    var elem = document.getElementById('tablefrm').elements;
    var isvalue = "";
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.indexOf("select_") != 1) {
            parameters += elementname + "=" + elem[i].value + "&";
            if (elem[i].value > 0) {
                isvalue = "1";
            }
        }
    }
    if (parameters.length > 1) {
        parameters = parameters.substring(0, parameters.length - 1);
    }
    if (isvalue == '') {
        alert("Kindly select any record");
        parameters = "";
    }
    return parameters;
}


function indicatormarkvalidation(formname) {

    disablebuttonbyjq('savebtn');
    var islock = document.getElementById("islock").value;

    if (islock != "") {
        showErrorMessage("You can't change grade/marks of this subject because it has been locked.");
        enablebuttonbyjq('savebtn');
        return false;
    }
    var elem = document.getElementById(formname).elements;
    var notvalid = 0;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.search("_indicator_marks") != -1) {
            var value = elem[i].value;
            var alertvalue = parsingFloat(value, true, "Marks");
            if (alertvalue != '') {
                showErrorMessage(alertvalue);
                return false;
            }
            if (value > 5) {

                elem[i].style.borderColor = 'red';
                if (notvalid == 0) {
                    elem[i].focus();
                    showErrorMessage("Marks should be less than to 5");
                }
                notvalid = 1;
            } else {
                elem[i].style.borderColor = 'white';
            }
        }
    }
    if (notvalid == 0) {
        return true;
    } else {
        enablebuttonbyjq('savebtn');
        return false;

    }
}

function indicatoravgmarkvalidation(formname) {

    document.getElementById("savebtn").disabled = true;
    var islock = document.getElementById("islock").value;

    if (islock != "") {
        showErrorMessage("You can't change grade/marks of this subject because it has been locked.");
        document.getElementById("savebtn").disabled = false;
        return false;
    }
    var elem = document.getElementById(formname).elements;
    var notvalid = 0;
    for (var i = 0; i < elem.length; i++) {
        var elementname = elem[i].name;
        if (elementname.search("studentavgmarks_") != -1) {
            var value = elem[i].value;
            var alertvalue = parsingFloat(value, true, "Marks");
            if (alertvalue != '') {
                showErrorMessage(alertvalue);
                document.getElementById("savebtn").disabled = false;
                return false;
            }
            if (value > 5) {

                elem[i].style.borderColor = 'red';
                if (notvalid == 0) {
                    elem[i].focus();
                    showErrorMessage("Marks should be less than to 5");

                }
                notvalid = 1;
            } else {
                elem[i].style.borderColor = 'white';
            }
        }
    }
    if (notvalid == 0) {
        return true;
    } else {
        document.getElementById("savebtn").disabled = false;
        return false;
    }
}

Date.prototype.format = function (format) //author: meizz
{
    var o = {
        "M+":this.getMonth() + 1, //month
        "d+":this.getDate(), //day
        "h+":this.getHours(), //hour
        "m+":this.getMinutes(), //minute
        "s+":this.getSeconds(), //second
        "q+":Math.floor((this.getMonth() + 3) / 3), //quarter
        "S":this.getMilliseconds() //millisecond
    }

    if (/(y+)/.test(format)) format = format.replace(RegExp.$1,
        (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)if (new RegExp("(" + k + ")").test(format))
        format = format.replace(RegExp.$1,
            RegExp.$1.length == 1 ? o[k] :
                ("00" + o[k]).substr(("" + o[k]).length));
    return format;
}

function addToList(array, item) {
    var exist = false;
    for (var i = 0; i < array.length; i++) {
        if (item.id == array[i].id) {
            exist = true;
        }
    }
    if (exist == false) {
        array.push(item);
    }
}

function removeFromList(array, item) {
    for (var i = 0; i < array.length; i++) {
        if (item.id == array[i].id) {
            array.splice(i, 1);
            break;
        }
    }
}

function removeFromListById(array, id) {
    for (var i = 0; i < array.length; i++) {
        if (id == array[i].id) {
            array.splice(i, 1);
            break;
        }
    }
}

function validatedataandsubmitformanddisablebutton(str) {
    document.getElementById("savebtn").disabled = true;
    if (validateForm()) {
        var url = str + "?" + formValues();
        buttondisableenableuniquepostRequest(url);
    } else {
        document.getElementById("savebtn").disabled = false;
    }

}
function validateformdataandsubmitformanddisablebutton(str) {
    document.getElementById("savebtn").disabled = true;
    disablebuttonbyjq("savebtn");
    if (validateFormValue()) {
        var url = str + "?" + formValues();
        buttondisableenableuniquepostRequest(url);
    } else {
        document.getElementById("savebtn").disabled = false;
    }
}
function validatedataandsubmitform(str) {
    disablebuttonbyjq("savebtn");
    if (validateForm()) {
        disablebuttonbyjq("savebtn");
        var url = str + "?" + formValues();
        uniquepostRequest(url);
    } else {
        enablebuttonbyjq("savebtn");
    }

}
function validatedateandsubmitformdata(str) {
    disablebuttonbyjq("savebtn");
    if (validateFormValueWithButton()) {
        disablebuttonbyjq("savebtn");
        actualpostRequest(str, formValues());
    } else {
        enablebuttonbyjq("savebtn");
    }

}
function validatedateandsubmitformdataandprint(str) {
    disablebuttonbyjq("savebtn");
    if (validateFormValueWithButton()) {
        document.getElementById("print").value = 1;
        document.getElementById("frmMain").setAttribute("target", "_blank");
        actualpostRequest(str, formValues());
    } else {
        enablebuttonbyjq("savebtn");
    }

}

function validatedateviewform(str) {

    if (validateFormValue()) {

        if (str != '#') {
            var url = str + "?" + formValues();
            viewopen(url);
        }
    }
}

function buttondisableenableuniquepostRequest(strURL) {
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send(strURL);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            buttondisableenableuniqueoutput(xmlHttp.responseText);
        }
    }

}

function buttondisableenableuniqueoutput(str) {
    disablebuttonbyjq("savebtn");
    if ((str == 'successful' || str == 'Successfully')) {
        document.forms["frmMain"].submit();
    } else {
        showErrorMessage(str);
        document.getElementById("savebtn").disabled = false;
        enablebuttonbyjq("savebtn");
    }
}

function actualpostRequest(strURL, params) {
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            uniqueoutput(xmlHttp.responseText);
        }
    }
    xmlHttp.send(params);
}

function classCall(strURL) {
    disablebuttonbyjq('savebtn');
    console.log(strURL);
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send(strURL);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            console.log(xmlHttp.responseText);
        }
    }
}

function uniquepostRequest(strURL) {
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            uniqueoutput(xmlHttp.responseText);
        }
    }
    xmlHttp.send(strURL);
}

function uniqueoutput(str) {
    if (str == 'successful' || str == 'Successfully') {
        disablebuttonbyjq("savebtn");
        document.forms["frmMain"].submit();
    } else {
        showErrorMessage(str);
        enablebuttonbyjq("savebtn");
    }
}

function getxmlhttpobject() {
    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return
    }
    return  xmlHttp;
}

function printpagediv(printpage) {
    var headstr = "<html><head><title></title></head><body>";
    var footstr = "</body>";
    var newstr = document.all.item(printpage).innerHTML;
    var oldstr = document.body.innerHTML;
    document.body.innerHTML = headstr + newstr + footstr;
    window.print();
    document.body.innerHTML = oldstr;
    return false;
}

function showSingleSubject() {

    var classid = document.getElementById("classid").value;


    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return
    }

    var url = "subjectbyclassid.jsp";
    url += "?classid=" + classid + "&mandatory=yes";
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("subject").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showMultipleSection() {
    var xmlHttp = getxmlhttpobject();

    var classid = document.getElementById("classid").value;
    var i;
    var url = "sectionbyclassid.jsp";
    url += "?classid=" + classid + "&extra=multiple";
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("section").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showSingleSection() {
    var xmlHttp = getxmlhttpobject();

    var classid = document.getElementById("classid").value;
    var methodonchange = "onchange='showStudents();'";
    var url = "sectionbyclassid.jsp";
    url += "?classid=" + classid + "&function=" + methodonchange;
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("section").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}


function showMultipleSubject() {

    var classid = document.getElementById("classid");
    var i;
    var count = 0;
    var SelBranchVal = '';
    for (i = 0; i < classid.options.length; i++) {
        if (classid.options[i].selected) {
            if (SelBranchVal == '') {
                SelBranchVal = classid.options[i].value;
            } else {
                SelBranchVal = SelBranchVal + "," + classid.options[i].value;
            }

        }
    }

    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return;
    }
    var methodonchange = "onchange='showactivities();'";
    var url = "subjectbyclassid.jsp";
    url += "?classid=" + SelBranchVal + "&multiple=multiple&function=" + methodonchange + "&scorefilter=1";
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("subject").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showMultipleSubjectWithoutExtraFilter() {

    var classid = document.getElementById("classid");
    var i;
    var count = 0;
    var SelBranchVal = '';
    for (i = 0; i < classid.options.length; i++) {
        if (classid.options[i].selected) {
            if (SelBranchVal == '') {
                SelBranchVal = classid.options[i].value;
            } else {
                SelBranchVal = SelBranchVal + "," + classid.options[i].value;
            }

        }
    }

    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return;
    }
    var methodonchange = "onchange='showactivities();'";
    var url = "subjectbyclassid.jsp";
    url += "?classid=" + SelBranchVal + "&multiple=multiple&function=" + methodonchange;
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("subject").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showSisngleSubject() {

    var classid = document.getElementById("classid").value;


    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return
    }

    var url = "subjectbyclassid.jsp";
    url += "?classid=" + classid;
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("subject").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}


function showMultipleterm() {
    var xmlHttp;
    if (typeof XMLHttpRequest != "undefined") {
        xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlHttp == null) {
        alert("Browser does not support XMLHTTP Request")
        return
    }

    var classid = document.getElementById("classid").value;
    var i;
    var count = 0;


    var url = "termbyclassid_dynamic.jsp";
    url += "?classid=" + classid + "&elementid=" + "termid&extra=multiple"
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("term").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}


function showSectionByClassId() {
    var xmlHttp = getxmlhttpobject();
    var classid = document.getElementById("classid").value;
    var url = "sectionbyclassid.jsp";
    var functionname = document.getElementById("sectionfunction").value;
    var functionfilter = "";

    if (functionname != '') {
        functionfilter = "&function=onchange='" + functionname + "'";
    }
    url += "?classid=" + classid + "&mandatory=yes" + functionfilter;

    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("section").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showMessageofSMSTemplate(value, otherelement) {
    var xmlHttp = getxmlhttpobject();

    var url = "GetSMSTemplate?id=" + value;
    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById(otherelement).value = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showStudentByAcademicYearAndSectionID() {

    var xmlHttp = getxmlhttpobject();
    var sectionid = document.getElementById("sectionid").value;
    var academicyearid = document.getElementById("academicyearid").value;
    var multiple = document.getElementById("multiple").value;
    var mulfilter = "";
    if (multiple != '') {
        mulfilter = "&multiple=multiple"
    }
    var url = "sis_studentprofilebysecdtionid.jsp";
    url += "?sectionid=" + sectionid + "&mandatory=yes&academicyearid=" + academicyearid + mulfilter;

    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("student").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showStudentByAcademicYearAndSectionID_Table() {

    var xmlHttp = getxmlhttpobject();
    var sectionid = document.getElementById("sectionid").value;
    var academicyearid = document.getElementById("academicyearid").value;

    var url = "sis_table_studentbysectionid.jsp";
    url += "?sectionid=" + sectionid + "&academicyearid=" + academicyearid;

    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("student").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function showStudentByAcademicYearAndSectionIDStudentType_Table() {

    var xmlHttp = getxmlhttpobject();
    var sectionid = document.getElementById("sectionid").value;
    var academicyearid = document.getElementById("academicyearid").value;
    var classid = document.getElementById("classid").value;
    var studenttypeid = document.getElementById("studenttypeid").value;
    var url = "sis_table_studentbysectionid.jsp";
    url += "?sectionid=" + sectionid + "&academicyearid=" + academicyearid + "&studenttypeid=" + studenttypeid + "&classid=" + classid;

    xmlHttp.open("GET", url, true);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
            document.getElementById("student").innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.send(null);
}

function distance(origin, columnname) {
    var url = "mapdistance?origin=" + origin;
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', url, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/text');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            distancecolumnupdate(xmlHttp.responseText, columnname);
        }
    }
    xmlHttp.send(url);
}

function distancecolumnupdate(str, columnname) {
    document.getElementById(columnname).value = str;
}

function getdistance() {
    var address = document.getElementById("address").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var country = document.getElementById("country").value;
    var origin = "";
    if (address.length > 0) {
        origin = origin.length > 0 ? origin + " , " : origin;
        origin = origin + address;
    }

    if (city.length > 0) {
        origin = origin.length > 0 ? origin + " , " : origin;
        origin = origin + city;
    }

    if (state.length > 0) {
        origin = origin.length > 0 ? origin + " , " : origin;
        origin = origin + state;
    }

    if (country.length > 0) {
        origin = origin.length > 0 ? origin + " , " : origin;
        origin = origin + country;
    }

    distance(origin, 'distacnefromschool');
}


function posturl(strURL, currentpath) {
    var xmlHttp;
    if (window.XMLHttpRequest) { // For Mozilla, Safari, ...
        var xmlHttp = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) { // For Internet Explorer
        var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            submitpage(xmlHttp.responseText, currentpath);
        }
    }
    xmlHttp.send(strURL);
}

function formposturl(strURL, currentpath, param) {
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            submitpage(xmlHttp.responseText, currentpath);
        }
    }
    xmlHttp.send(param);
}

function submitpage(str, currentpath) {
    if (str == 'successful' || str == 'Data Saved Successfully') {

        swal({   title:"Successfully", text:"Data has been saved!",timer: 500, type:"success", showCancelButton:false, closeOnConfirm:false, showConfirmButton: false }, function () {
            setTimeout(function () {
                window.location = currentpath;
            });
        });


    } else {
        showErrorMessageDiv(str);
        enablebuttonbyjq('savebtn');
    }
}

function formposturl_json(strURL, currentpath, param) {
    var xmlHttp = getxmlhttpobject();
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            submitpage_json(xmlHttp.responseText, currentpath);
        }
    }
    xmlHttp.send(param);
}

function submitpage_json(str, currentpath) {
    var json = eval('(' + str + ')');
    var message = valuecheck(json.message);
    if (message != '') {
        window.location = currentpath;
    } else {
        var error = valuecheck(json.error);
        showErrorMessage(error);
        enablebuttonbyjq('savebtn');
    }
}

function formsubmit(path, currentpath) {
    if (validateFormValue()) {
        disablebuttonbyjq('savebtn')
        formposturl(path, currentpath, formValues());

    } else {
        enablebuttonbyjq('savebtn');
    }
}

function formsubmit_json(path, currentpath) {
    if (validateFormValue()) {
        disablebuttonbyjq('savebtn')
        formposturl_json(path, currentpath, formValues());

    } else {
        enablebuttonbyjq('savebtn');
    }
}

function formsubmitLogin(path, currentpath) {
    var url = path + "?" + formValues();
    posturl(url, currentpath);
}

function formsubmitvalidateform(path, currentpath) {
    if (validateForm()) {
        disablebuttonbyjq('savebtn')
        var url = path + "?" + formValues();
        posturl(url, currentpath);

    } else {
        enablebuttonbyjq('savebtn');
    }
}

$(function () {
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create:function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu:function (ul, items) {
            var that = this,
                currentCategory = "";
            $.each(items, function (index, item) {
                var li;

                if (item.category != currentCategory) {

                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });


    $(document).ready(function () {
        $(function () {
            var cache = {};
            $("#admissionsearch").catcomplete({
                source:function (request, response) {
                    var term = request.term;
                    if (term in cache) {
                        response(cache[ term ]);
                        return;
                    }
                    $.ajax({
                        url:"AdmissionSearch",
                        type:"GET",
                        data:{
                            term:request.term,
                            academicyearid:$("#academicyearid").val(),
                            studenttypeid:$("#studenttypeid").val()
                        },
                        dataType:"json",
                        success:function (data) {
                            cache[ term ] = data;
                            response(data);
                        }
                    });
                },
                select:function (event, ui) {
                    $("#admissionsearch").val(ui.item.value);
                    onselect();
                    return false;
                }
            })
        });
    });


    $(document).ready(function () {
        $(function () {
            var cache = {};
            $("#admissionsearch").catcomplete({
                source:function (request, response) {
                    var term = request.term;
                    if (term in cache) {
                        response(cache[ term ]);
                        return;
                    }
                    $.ajax({
                        url:"AdmissionSearch",
                        type:"GET",
                        data:{
                            term:request.term,
                            academicyearid:$("#academicyearid").val(),
                            studenttypeid:$("#studenttypeid").val()
                        },
                        dataType:"json",
                        success:function (data) {
                            cache[ term ] = data;
                            response(data);
                        }
                    });
                },
                select:function (event, ui) {
                    $("#admissionsearch").val(ui.item.value);
                    $("#admissionsearch").prop('title', ui.item.desc);
                    onselect();
                    return false;
                }
            })
        });
    });

    $(document).ready(function () {
        $(function () {
            var cache = {};
            $("#studentsearch").catcomplete({
                source:function (request, response) {
                    var term = request.term;
                    if (term in cache) {
                        response(cache[ term ]);
                        return;
                    }
                    $.ajax({
                        url:"AdmissionSearch",
                        type:"GET",
                        data:{
                            term:request.term,
                            academicyearid:$("#academicyearid").val(),
                            sectionid:$("#sectionid").val()
                        },
                        dataType:"json",
                        success:function (data) {
                            cache[ term ] = data;
                            response(data);
                        }
                    });
                },
                select:function (event, ui) {
                    $("#studentsearch").val(ui.item.label);
                    $("#studentsearch").prop('title', ui.item.desc);
                    $("#studentsearch").prop('sectionid', ui.item.sectionid);
                    $("#studentsearch").prop('classid', ui.item.classid);
                    $("#studentsearch").prop('studentid', ui.item.studentid);
                    $("#studentsearch").prop('studentprofileid', ui.item.studentprofileid);
                    onselect();
                    return false;
                }
            })
        });
    });

    $(document).ready(function () {
        $(function () {
            var cache = {};
            $("#search").autocomplete({
                source:function (request, response) {
                    var term = request.term;
                    if (term in cache) {
                        response(cache[ term ]);
                        return;
                    }
                    $.ajax({
                        url:"AutoSearch",
                        type:"GET",
                        data:{
                            term:request.term,
                            uri:$("#uri").val()
                        },
                        dataType:"json",
                        success:function (data) {
                            cache[ term ] = data;
                            response(data);
                        }
                    });
                },
                select:function (event, ui) {
                    $("#search").val(ui.item.value);
                    $("#search").prop('title', ui.item.desc);
                    onvalueselect();
                    return false;
                }
            })
        });
    });
});


$(function () {
    $(".tooltipdata").tooltip({
        hide:{
            effect:"explode",
            delay:250
        }
    });
});;
