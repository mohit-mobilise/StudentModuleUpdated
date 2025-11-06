$(document).ready(function(){

  var calculation_show = $('#calculation_show').val();
  
  var bounce_fee = $('#bounce_fee').val();
  
  if (calculation_show == '1' || calculation_show == 1) 
  {


    var field = '';
    var balance = '';
    var latefee = '';
    var bounce = '';

    var amount = '';
    var totalamt = 0;
    
    $(".getamt").each(function(){
        
        if($(this).is(":checked")){
            
            field = $(this).val();

            balance = $("#balance_"+field).val();              
            latefee = $("#latefee_"+field).val();              
            bounce  = $("#bounce_"+field).val();  

            amount = parseInt(balance) + parseInt(latefee) + parseInt(bounce);   

            totalamt += parseInt(amount); 
            

        }
    
    });

    totalamt += parseInt(bounce_fee);

    $('#total_amount').val(totalamt);

  }
  else
  {

      setTimeout(function(){

        $(".getamt").each(function(){
            
            this.checked=false; 
        
        });
       
       }, 1000);
      

  }
    
  
  var hstl_calculation_show = $('#hstl_calculation_show').val();
  
  var hstl_bounce_fee = $('#hstl_bounce_fee').val();
  
  if (hstl_calculation_show == '1' || hstl_calculation_show == 1) 
  {


    var hstlfield = '';
    var hstlbalance = '';
    var hstllatefee = '';
    var hstlbounce = '';

    var hstlamount = '';
    var hstltotalamt = 0;
    
    $(".hstlgetamt").each(function(){
        
        if($(this).is(":checked")){
            
            hstlfield = $(this).val();

            hstlbalance = $("#hstlbalance_"+hstlfield).val();              
            hstllatefee = $("#hstllatefee_"+hstlfield).val();              
            hstlbounce  = $("#hstlbounce_"+hstlfield).val();  

            hstlamount = parseInt(hstlbalance) + parseInt(hstllatefee) + parseInt(hstlbounce);   

            hstltotalamt += parseInt(hstlamount); 
            

        }
    
    });

    hstltotalamt += parseInt(hstl_bounce_fee);

    $('#hstl_total_amount').val(hstltotalamt);

  }
  else
  {

      setTimeout(function(){

        $(".hstlgetamt").each(function(){
            
            this.checked=false; 
        
        });
       
       }, 1000); 
      

  }

  
$('.getamt').click(function(){
    
    var chkbox = $(this).val();
    var Fee_Submission_Type = $('#Fee_Submission_Type').val();
    
    if(Fee_Submission_Type == '3') {
        
        if($(this).is(":checked")) {

          if (chkbox == '1' || chkbox == '2' || chkbox == '3' || chkbox == '4' || chkbox == '5' || chkbox == '6') {
              $('#chkcheckbox_1').prop('checked', true);
              $('#chkcheckbox_2').prop('checked', true);
              $('#chkcheckbox_3').prop('checked', true);
              $('#chkcheckbox_4').prop('checked', true);
              $('#chkcheckbox_5').prop('checked', true);
              $('#chkcheckbox_6').prop('checked', true);
          }else if (chkbox == '7' || chkbox == '8' || chkbox == '9' || chkbox == '10' || chkbox == '11' || chkbox == '12') {
              $('#chkcheckbox_7').prop('checked', true);
              $('#chkcheckbox_8').prop('checked', true);
              $('#chkcheckbox_9').prop('checked', true);
              $('#chkcheckbox_10').prop('checked', true);
              $('#chkcheckbox_11').prop('checked', true);
              $('#chkcheckbox_12').prop('checked', true);
          }
        
        } else {
    
          if (chkbox == '1' || chkbox == '2' || chkbox == '3' || chkbox == '4' || chkbox == '5' || chkbox == '6') {
              $('#chkcheckbox_1').prop('checked', false);
              $('#chkcheckbox_2').prop('checked', false);
              $('#chkcheckbox_3').prop('checked', false);
              $('#chkcheckbox_4').prop('checked', false);
              $('#chkcheckbox_5').prop('checked', false);
              $('#chkcheckbox_6').prop('checked', false);
          } else if (chkbox == '7' || chkbox == '8' || chkbox == '9' || chkbox == '10' || chkbox == '11' || chkbox == '12') {
              $('#chkcheckbox_7').prop('checked', false);
              $('#chkcheckbox_8').prop('checked', false);
              $('#chkcheckbox_9').prop('checked', false);
              $('#chkcheckbox_10').prop('checked', false);
              $('#chkcheckbox_11').prop('checked', false);
              $('#chkcheckbox_12').prop('checked', false);
          }
    
        }
        
    } 
    
    if(Fee_Submission_Type == '2') {
        
        if($(this).is(":checked")) {

          if (chkbox == '1' || chkbox == '2' || chkbox == '3') {
              $('#chkcheckbox_1').prop('checked', true);
              $('#chkcheckbox_2').prop('checked', true);
              $('#chkcheckbox_3').prop('checked', true);
          } else if (chkbox == '4' || chkbox == '5' || chkbox == '6') {
              $('#chkcheckbox_4').prop('checked', true);
              $('#chkcheckbox_5').prop('checked', true);
              $('#chkcheckbox_6').prop('checked', true);
          } else if (chkbox == '7' || chkbox == '8' || chkbox == '9') {
              $('#chkcheckbox_7').prop('checked', true);
              $('#chkcheckbox_8').prop('checked', true);
              $('#chkcheckbox_9').prop('checked', true);
          }  else if (chkbox == '10' || chkbox == '11' || chkbox == '12') {
              $('#chkcheckbox_10').prop('checked', true);
              $('#chkcheckbox_11').prop('checked', true);
              $('#chkcheckbox_12').prop('checked', true);
          }
        
        } else {
    
          if (chkbox == '1' || chkbox == '2' || chkbox == '3') {
              $('#chkcheckbox_1').prop('checked', false);
              $('#chkcheckbox_2').prop('checked', false);
              $('#chkcheckbox_3').prop('checked', false);
          } else if (chkbox == '4' || chkbox == '5' || chkbox == '6') {
              $('#chkcheckbox_4').prop('checked', false);
              $('#chkcheckbox_5').prop('checked', false);
              $('#chkcheckbox_6').prop('checked', false);
          } else if (chkbox == '7' || chkbox == '8' || chkbox == '9') {
              $('#chkcheckbox_7').prop('checked', false);
              $('#chkcheckbox_8').prop('checked', false);
              $('#chkcheckbox_9').prop('checked', false);
          }  else if (chkbox == '10' || chkbox == '11' || chkbox == '12') {
              $('#chkcheckbox_10').prop('checked', false);
              $('#chkcheckbox_11').prop('checked', false);
              $('#chkcheckbox_12').prop('checked', false);
          }
    
        }
        
    }

    var field = '';
    var balance = '';
    var latefee = '';
    var bounce = '';

    var amount = '';
    var totalamt = 0;
    
    var bounce_fee = $('#bounce_fee').val();
    
    $(".getamt").each(function(){
        
        if($(this).is(":checked")){
            
            field = $(this).val();

            balance = $("#balance_"+field).val();              
            latefee = $("#latefee_"+field).val();              
            bounce  = $("#bounce_"+field).val();  

            amount = parseInt(balance) + parseInt(latefee) + parseInt(bounce);   

            totalamt += parseInt(amount); 
            

        }
    
    });
    
    
    totalamt += parseInt(bounce_fee);
    

    $('#total_amount').val(totalamt);

});



$('.hstlgetamt').click(function(){
    
  var hstlchkbox = $(this).val();
  

//   if($(this).is(":checked")) {

//     if (hstlchkbox == '1' || hstlchkbox == '2' || hstlchkbox == '3') {
//         $('#hstlchkcheckbox_1').prop('checked', true);
//         $('#hstlchkcheckbox_2').prop('checked', true);
//         $('#hstlchkcheckbox_3').prop('checked', true);
//     } else if (hstlchkbox == '4' || hstlchkbox == '5' || hstlchkbox == '6') {
//         $('#hstlchkcheckbox_4').prop('checked', true);
//         $('#hstlchkcheckbox_5').prop('checked', true);
//         $('#hstlchkcheckbox_6').prop('checked', true);
//     } else if (hstlchkbox == '7' || hstlchkbox == '8' || hstlchkbox == '9') {
//         $('#hstlchkcheckbox_7').prop('checked', true);
//         $('#hstlchkcheckbox_8').prop('checked', true);
//         $('#hstlchkcheckbox_9').prop('checked', true);
//     }  else if (hstlchkbox == '10' || hstlchkbox == '11' || hstlchkbox == '12') {
//         $('#hstlchkcheckbox_10').prop('checked', true);
//         $('#hstlchkcheckbox_11').prop('checked', true);
//         $('#hstlchkcheckbox_12').prop('checked', true);
//     }
  
//   } else {

//     if (hstlchkbox == '1' || hstlchkbox == '2' || hstlchkbox == '3') {
//         $('#hstlchkcheckbox_1').prop('checked', false);
//         $('#hstlchkcheckbox_2').prop('checked', false);
//         $('#hstlchkcheckbox_3').prop('checked', false);
//     } else if (hstlchkbox == '4' || hstlchkbox == '5' || hstlchkbox == '6') {
//         $('#hstlchkcheckbox_4').prop('checked', false);
//         $('#hstlchkcheckbox_5').prop('checked', false);
//         $('#hstlchkcheckbox_6').prop('checked', false);
//     } else if (hstlchkbox == '7' || hstlchkbox == '8' || hstlchkbox == '9') {
//         $('#hstlchkcheckbox_7').prop('checked', false);
//         $('#hstlchkcheckbox_8').prop('checked', false);
//         $('#hstlchkcheckbox_9').prop('checked', false);
//     }  else if (hstlchkbox == '10' || hstlchkbox == '11' || hstlchkbox == '12') {
//         $('#hstlchkcheckbox_10').prop('checked', false);
//         $('#hstlchkcheckbox_11').prop('checked', false);
//         $('#hstlchkcheckbox_12').prop('checked', false);
//     }

//   }

  var hstlfield = '';
  var hstlbalance = '';
  var hstllatefee = '';
  var hstlbounce = '';

  var hstlamount = '';
  var hstltotalamt = 0;
  
  var hstl_bounce_fee = $('#hstl_bounce_fee').val();
  
  $(".hstlgetamt").each(function(){
      
      if($(this).is(":checked")){
          
          hstlfield = $(this).val();

          hstlbalance = $("#hstlbalance_"+hstlfield).val();              
          hstllatefee = $("#hstllatefee_"+hstlfield).val();              
          hstlbounce  = $("#hstlbounce_"+hstlfield).val();  

          hstlamount = parseInt(hstlbalance) + parseInt(hstllatefee) + parseInt(hstlbounce);   

          hstltotalamt += parseInt(hstlamount); 
          
      }
  
  });
  
  
  hstltotalamt += parseInt(hstl_bounce_fee);

  $('#hstl_total_amount').val(hstltotalamt);

});


  
 $('#btngeneratechallan').click(function(){

     var adm_no = $('#challan_adm_no').val();  
     var masterclass = $('#challan_class').val();  
     var year = $('#challan_year').val();  
     var challan_idtnfy = $('#challan_idtnfy').val();  

      var monthid = '';
      var field = '';

      $('.selectmonthchallan').each(function(){

        if($(this).is(":checked")){
            
            field = $(this).val();
            
            monthid += "'" + field + "'" + ",";

        }


      });
     
     var month_id =  monthid.replace(/(^,)|(,$)/g, "");

     if (month_id == '') 
     {
       toastr.warning("Please select atleast one month", "Validation Error");
       return false;
     }
     else if (year == '') 
     {
       toastr.warning("Please select year", "Validation Error");
       return false;
     }
     else if (masterclass == '') 
     {
        toastr.warning("Class is mandatory", "Validation Error");
        return false;
     } 
     else if (challan_idtnfy == '')
     {
        toastr.error("Please try again", "Error");
        return false;
     }
     else
     {
        $('#challan_modal').modal('hide');

        $('#challan_year').val('');

        $('.selectmonthchallan').each(function(){
            
            $(this).prop('checked',false);
        });

        $('#master_class').val(masterclass);
        $('#sadm_no').val(adm_no);
        $('#year').val(year);
        $('#month').val(month_id);

        if(challan_idtnfy == 'student') {
          $('#frmdisplaychallan').attr('action', '../Admin/fee_ledger/DisplayClassWiseChallan_monthly.php');

        }

        if(challan_idtnfy == 'hostel') {
          $('#frmdisplaychallan').attr('action', '../Admin/hostel_fee/DisplayClassWiseChallan_monthly.php');
        }

        $('#frmdisplaychallan').submit();


     }


    });



});


function open_fee_receipt(adm,receipt)
{
  $('#adm_no').val(adm);
  $('#receipt_no').val(receipt);

  $('#frmfeereceipt').attr('action', '../Admin/fee_ledger/fee_receipt.php');

  $('#frmfeereceipt').submit();

}

function hostel_open_fee_receipt(adm,receipt)
{
  $('#adm_no').val(adm);
  $('#receipt_no').val(receipt);

  $('#frmfeereceipt').attr('action', '../Admin/hostel_fee/fee_receipt.php');

  $('#frmfeereceipt').submit();

}


function create_challan(adm,masterclass)
{

  $('#challan_class').val(masterclass);
  $('#challan_adm_no').val(adm);
  $('#challan_idtnfy').val('student');
  
  $('.selectmonthchallan').each(function(){
    $(this).prop('checked',true);
    $(this).prop('disabled',true);
  });

  $('#challan_modal').modal('show'); 

}

function create_hostel_challan(adm,masterclass)
{

  $('#challan_class').val(masterclass);
  $('#challan_adm_no').val(adm);
  $('#challan_idtnfy').val('hostel');

  $('.selectmonthchallan').each(function(){
    $(this).prop('checked',true);
    $(this).prop('disabled',true);
  });

  $('#challan_modal').modal('show'); 

}


function monthwisechallan(adm_no,masterclass,year,month)
{
    $('#master_class').val(masterclass);
    $('#sadm_no').val(adm_no);
    $('#year').val(year);
    $('#month').val("'"+month+"'");

    $('#frmdisplaychallan').attr('action', '../Admin/fee_ledger/DisplayClassWiseChallan_monthly.php');

    $('#frmdisplaychallan').submit();

}

function hostelmonthwisechallan(adm_no,masterclass,year,month)
{
    $('#master_class').val(masterclass);
    $('#sadm_no').val(adm_no);
    $('#year').val(year);
    $('#month').val("'"+month+"'");

    $('#frmdisplaychallan').attr('action', '../Admin/hostel_fee/DisplayClassWiseChallan_monthly.php');

    $('#frmdisplaychallan').submit();

}

function validatefees()
{
    var field = '';
    var feestatus = '';
    var chkcheckbox = '';

    $(".getamt").each(function(){
        
        if($(this).is(":checked")){

          field = $(this).val();
        
        };
    });

    console.log(field);
  
    for (var i=1;i < field; i++) 
    {
        feestatus = $('#chkfeestatus_'+i).val();

        chkcheckbox = $('#chkcheckbox_'+i).prop("checked")

        if (feestatus == 'notpaid' && chkcheckbox == false) 
        {
            toastr.warning('Please select previous month first', 'Validation Error');
            return false;
        }

    }

    $('#frmFeesMonthly').submit();

}


function hostelvalidatefees()
{
    var field = '';
    var feestatus = '';
    var chkcheckbox = '';

    $(".hstlgetamt").each(function(){
        
        if($(this).is(":checked")){

          field = $(this).val();
        
        };
    });

    console.log(field);
  
    for (var i=1;i < field; i++) 
    {
        feestatus = $('#hstlchkfeestatus_'+i).val();

        chkcheckbox = $('#hstlchkcheckbox_'+i).prop("checked")

        if (feestatus == 'notpaid' && chkcheckbox == false) 
        {
            toastr.warning('Please select previous month first', 'Validation Error');
            return false;
        }

    }

    $('#hstlfrmFeesMonthly').submit();

}