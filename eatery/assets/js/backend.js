/*refactored start*/

   jQuery('.deleteFromQueue').on('click', function() {

    var r = confirm("Are you sure to delete this product from today's queue?.");
    if (r == true) {

      var productID = $(this).closest('tr').attr('id');
      
        jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'removeFromTodayProductQueue', 
             'product_id':productID
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
        alert(result);
        $(this).closest('tr').remove();
        
      },
      type: 'POST'
      });
      
    }

});

jQuery('[data-view-changed]').on('click', function() {
   $(this).next().removeClass('hidden');
   $(this).hide();
});

jQuery('body').on('click','[data-hide-changed]', function() {
   $(this).closest('p').addClass('hidden');
   $(this).hide();
   $(this).closest('td').find('[data-view-changed]').show();
});

/*refactoted end*/

jQuery('#submitFund').on('click', function() {

    var r = confirm("Are you sure to add the fund?.");
    if (r == true) {

       var username = jQuery('#username').val();
       console.debug(username);
       var fund = jQuery('#fund').val();
       var purpose = jQuery('#purpose').val();
       var given_by = jQuery('#given_by').val();
       var given_by_id = jQuery('#given_by_id').val();
        console.debug(username);
        console.debug(fund);
        console.debug(purpose);
        console.debug(given_by);
        console.debug(given_by_id);
        jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'addPoints', 
             'username':username, 
             'fund':fund, 
             'purpose':purpose, 
             'given_by':given_by, 
             'given_by_id':given_by_id, 
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
        alert(result)
      },
      type: 'POST'
      });
      
    }
  console.debug('wow');  
});

jQuery('.date_on_sale').on('click', function() {

    console.debug('hi');
   console.debug(jQuery(this).attr('id')); 
   console.debug($(this).prev('input'));
   $(this).prev('input').prop('readonly', false);
});

jQuery("#loadnext").on('click', function(){
      var lastUserID = $('#userlog').find('tr').last().attr('id');
      console.debug(lastUserID);
      
      jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'fetchUsers',
             'userID':lastUserID
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
           
            if (result.length <= 0) {
                alert('no more data');
                return false;
            }
            var count = 0;
            
            count++;
            for(var i =0; i< result.length; i++) {

                $('<tr id="'+result[i].ID+'"><td>'+count+'</td><td>'+result[i].ID+'</td><td>'+result[i].user_login+'</td><td>'+result[i].user_email+'</td><td>'+result[i].phone+'</td><td>RM '+result[i].available_fund+'</td><td>'+result[i].registered_date+'</td><td>'+result[i].topup+'</td><td colspan="3"><a href="javascript:void(0);" data-topup="'+result[i].ID+'">topup</a> <a href="javascript:void(0);" data-purchase="'+result[i].ID+'">purchase</a></td><td>'+result[i].device_type+'</td></tr>').insertAfter('#userlog tr:last');
            }
        },
         type: 'POST'
        
   });
   
});
 
/*end*/

jQuery('#maintenance_confirm').on('click', function() {
    
   var ios = jQuery('#maintenance_mode_ios').val();
   var android = jQuery('#maintenance_mode_android').val();
   var mobile_web = jQuery('#maintenance_mode_mobile_web').val();
   var dekstop = jQuery('#maintenance_mode_dekstop').val();
    
      jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'maintenance',
             'data': {
                 'ios': ios,
                 'android': android,
                 'mobile_web': mobile_web,
                 'dekstop': dekstop
             }
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
         console.debug(result);
      },
      type: 'POST'
      });  
});

jQuery('#version_confirm').on('click', function() {

   var ios = jQuery('#version_update_ios').val();
   var android = jQuery('#version_update_android').val();
   
   var ios_update = jQuery('#minor_major_ios').val();
   var android_update = jQuery('#minor_major_android').val();
   
   var ios_code = jQuery('#version_code_ios').val();
   var android_code = jQuery('#version_code_android').val();
   
   
   var ios_msg = jQuery('#version_custom_msg_ios').val();
   var android_msg = jQuery('#version_custom_msg_android').val();
    
      jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'version',
             'data': {
                 'ios': ios,
                 'android': android,
                 'ios_update': ios_update,
                 'android_update': android_update,
                 'ios_version_code':ios_code,
                 'android_version_code':android_code,
                 'ios_msg': ios_msg,
                 'android_msg': android_msg,
             }
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
         console.debug(result);
      },
      type: 'POST'
      });  
});


jQuery('button[id^=delete_]').on('click', function() {

    var r = confirm("Are you sure to delete the ad?.");
    if (r == true) {

       var add = jQuery(this).attr('id');
       var addID = add.split('_')[1];

        jQuery.ajax({
         url: backend.ajax_url,
         'data': {'action': 'deleteAdd', 'add_id':addID },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
        jQuery('#ads').find('tr#'+addID).css('background-color', '#ff0').remove();
      },
      type: 'POST'
      });
      
    }
});

jQuery('#download_user_log').on('click', function() {
   
   console.debug('download user log');
    jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'downloadUserLog'
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
         console.debug(result);
      },
      type: 'POST'
      });   
});


 $("body").on('click', '#userlog tr [data-topup]', function(){
    
   $('#preview #first').slideDown();
   $('#preview #second').slideUp();
   $('#userlog').find('tr').css('background-color', '#fff');
   $(this).closest('tr').css('background-color', '#d3d3d3');
     
     var id = $(this)[0].dataset.topup;
     
        // $('#preview').toggle();
        $('#preview #first').html('<h3>Loading...</h3>');
        
        jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'topupLog',
             'user_id':id
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
         $('#preview #first').html('<table width=100% class="doropu-table"><tr><th>Product</th><th>Amount</th><th>Order ID</th><th>Date/Time</th></tr>');
         
         if (result.length <= 0) {
             $('<tr><td colspan="4">No Top up Found</td></tr>').insertAfter('#preview #first table tr:first');
         }
         for(var i = 0; i< result.length; i++) {

            $('<tr><td>'+result[i].purchased_item+'</td><td>RM '+Math.round(result[i].purchase_price)+'</td><td> '+result[i].purchase_reference_no+'</td><td> '+result[i].date_bought+'</td></tr>').insertAfter('#preview #first table tr:first');
         }

       
      },
      type: 'POST'
      });   
      
      
    });
    
     $('body').on('click', '#userlog tr [data-purchase]', function(){
    
     $('#preview #first').slideDown();
     $('#preview #second').slideUp();
      
     $('#userlog').find('tr').css('background-color', '#fff');
     $(this).closest('tr').css('background-color', '#d3d3d3');
     
     var id = $(this)[0].dataset.purchase;
     
        // $('#preview').toggle();
       $('#preview #first').html('<h3>Loading...</h3>');
        
        jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'purchaseLog',
             'user_id':id
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
         $('#preview #first').html('<table width=100% class="doropu-table" id="purchase_table"><tr><th>Product ID</th><th>Product</th><th>Purchased Price</th><th>Order ID</th><th>Date/Time</th><th>Payment Status</th><th>Total Rebate Collected</th></tr>');
         
         if (result.length <= 0) {
             $('<tr><td colspan="6">No Purchase Found</td></tr>').insertAfter('#preview #first table tr:first');
         }
         for(var i = 0; i< result.length; i++) {

            $('<tr>><td>'+result[i].product_id+'</td><td>'+result[i].purchased_item+'</td><td>RM '+result[i].purchase_price+'</td><td> '+result[i].purchase_reference_no+'</td><td> '+result[i].date_bought+'</td><td> '+result[i].payment_status+'</td><td>RM '+Math.round(result[i].totalRebate)+'<br/><a data-rebate="'+result[i].product_id+'" data-user-rebate="'+result[i].user_id+'" href="javascript:void(0);">view rebate history</a></td></tr>').insertAfter('#preview #first table tr:first');
         }
        
       
      },
      type: 'POST'
      });   
      
      
    });
    
    
    $('#preview #first').on('click', '[data-rebate]', function() {
      
      $('#preview #first').slideUp();
      $('#preview #second').slideDown();
      
       $('#preview #second  #second_content').html('<h3>Loading...</h3>');
       
      var productID = $(this).data('rebate');
      var userID = $(this).data('user-rebate');

       jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'rebateLog',
             'product_id':productID,
             'user_id':userID
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
         $('#preview #second #second_content').html('<table width=100% class="doropu-table"><tr><th colspan="2">PRODUCT ID:'+result.product_id+' <br/>PRODUCT NAME: '+result.productName+'</th></tr><tr><th>Rebate</th><th>Date/ Time</th></tr>');
         
         if (!result.status) {
             $('<tr><td colspan="2">No Rebate Found</td></tr>').insertAfter('#preview #second #second_content table tr:nth-child(2)');
         }

         for(var i = 0; i< result.data.length; i++) {

            $('<tr><td>RM '+Math.round(result.data[i].rebate)+'</td><td> '+result.data[i].rebateTime+'</td></tr>').insertAfter('#preview #second #second_content table tr:nth-child(2)');
         }
       
      },
      type: 'POST'
      });   
    });


$('#preview').on('click', '#second #back', function() {
    
    $('#preview #second').slideUp();
    $('#preview #first').slideDown();
});


$('#search_user').on('click', function() {
 
    var key = $('#search_key').val();
    var value = $('#search_value').val();
    
    jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'searchUser',
             'key':key,
             'value':value
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            console.debug(result.length);
            if (result.length <= 0) {
                 console.debug('no data');
                    $('#userlog').html('<tr>'
        		+'<th></th>'
        		+'<th>USER ID</th>'
        		+'<th>CUSTOMER NAME</th>'
        		+'<th>CUSTOMER EMAIL</th>'
        		+'<th>CUSTOMER PHONE</th>'
        		+'<th>REGISTERED DATE</th>'
        		+'<th>AVAILABLE FUND</th>'
        		+'<th>TOTAL TOPUP</th>'
        		+'<th>TOTAL PURCHASE</th>'
        		+'<th colspan="3">VIEW HISTORY</th>'
        		+'<th>REGISTERED THROUGH</th>'
        	+'</tr><tr><td colspan="8">No User Found</td></tr>');
            return false;
                }
                
            if (result.length > 0) {
                $('#userlog').html('');
            }
            var count = 0;
            $('#userlog').append('<tr>'
        		+'<th></th>'
        		+'<th>USER ID</th>'
        		+'<th>CUSTOMER NAME</th>'
        		+'<th>CUSTOMER EMAIL</th>'
        		+'<th>CUSTOMER PHONE</th>'
        		+'<th>REGISTERED DATE</th>'
        		+'<th>AVAILABLE FUND</th>'
        		+'<th>TOTAL TOPUP</th>'
        		+'<th>TOTAL PURCHASE</th>'
        		+'<th colspan="3">VIEW HISTORY</th>'
        		+'<th>REGISTERED THROUGH</th></tr>'
        	+'</tr>');
            for(var i =0; i< result.length; i++) {

                count++;
                $('<tr><td>'+count+'</td><td>'+result[i].ID+'</td><td>'+result[i].user_login+'</td><td>'+result[i].user_email+'</td><td>'+result[i].phone+'</td><td>'+result[i].registered_date+'</td><td>RM '+Math.round(result[i].available_fund)+'</td><td></td><td></td><td colspan="3"><a href="javascript:void(0);" data-topup="'+result[i].ID+'">topup</a> <a href="javascript:void(0);" data-purchase="'+result[i].ID+'">purchase</a></td><td>'+result[i].device_type+'</td></tr>').insertAfter('#userlog tr:first');
            }
        
        
       
      },
      type: 'POST'
      });
});

$('#reload').on('click', function() {
    
    $('#search_key').val('')
    $('#search_value').val('')
   location.reload();
});

$('#export_csv').on('click', function() {

    var args = [$('#userlog'), 'export.csv'];
  console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});

$('#export_pdf').on('click', function() {
    //   jQuery.ajax({
    //      url: backend.ajax_url,
    //      'data': {
    //          'action': 'exporttocsv'
             
    //      },
    //      error: function(msg, b, c) {
    //          console.debug('error');
    //     },
    //     dataType: 'json',
    //     success: function(result) {
            
    //      console.debug(result);
    //   },
    //   type: 'POST'
    //   });
    window.print();
    fnExcelReport();
});

$('#export_excel').on('click', function() {
     
    fnExcelReport();
});

$('body').on('click', '#pricedrop_log',function() {

    var args = [$('body').find('#pricedrop_log_table'), 'export.csv'];
    console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});

$('body').on('click', '#purchase_log',function() {
console.debug('puchase table');
    var args = [$('body').find('#purchase_log_table'), 'export.csv'];
    console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});


$('body').on('click', '#purchase_price',function() {
console.debug('puchase table');
    var args = [$('body').find('#purchase_table'), 'export.csv'];
    console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});

function fnExcelReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('userlog'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

function fnExcelReport2()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('topup_history'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}


function fnExcelReport3()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('buy_history'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

$('#registered_today').on('click', function() {
    jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'registered_today'
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            
         if (result.length <= 0) {
                 console.debug('no data');
                    $('#userlog').html('<tr>'
        		+'<th></th>'
        		+'<th>USER ID</th>'
        		+'<th>CUSTOMER NAME</th>'
        		+'<th>CUSTOMER EMAIL</th>'
        		+'<th>CUSTOMER PHONE</th>'
        		+'<th>REGISTERED DATE</th>'
        		+'<th>AVAILABLE FUND</th>'
        		+'<th>TOTAL TOPUP</th>'
        		+'<th>TOTAL PURCHASE</th>'
        		+'<th colspan="3">VIEW HISTORY</th>'
        		+'<th>REGISTERED THROUGH</th>'
        	+'</tr><tr><td colspan="8">No User Found</td></tr>');
            return false;
                }
                
            if (result.length > 0) {
                $('#userlog').html('');
            }
            var count = 0;
            $('#userlog').append('<tr>'
        		+'<th></th>'
        		+'<th>USER ID</th>'
        		+'<th>CUSTOMER NAME</th>'
        		+'<th>CUSTOMER EMAIL</th>'
        		+'<th>CUSTOMER PHONE</th>'
        		+'<th>REGISTERED DATE</th>'
        		+'<th>AVAILABLE FUND</th>'
        		+'<th>TOTAL TOPUP</th>'
        		+'<th>TOTAL PURCHASE</th>'
        		+'<th colspan="3">VIEW HISTORY</th>'
        		+'<th>REGISTERED THROUGH</th></tr>'
        	+'</tr>');
            for(var i =0; i< result.length; i++) {

                count++;
                $('<tr><td>'+count+'</td><td>'+result[i].ID+'</td><td>'+result[i].user_login+'</td><td>'+result[i].user_email+'</td><td>'+result[i].phone+'</td><td>'+result[i].registered_date+'</td><td>RM '+Math.round(result[i].available_fund)+'</td><td></td><td></td><td colspan="3"><a href="javascript:void(0);" data-topup="'+result[i].ID+'">topup</a> <a href="javascript:void(0);" data-purchase="'+result[i].ID+'">purchase</a></td><td>'+result[i].device_type+'</td></tr>').insertAfter('#userlog tr:first');
            }
       
      },
      type: 'POST'
      });   
});

$('#add_more').on('click', function(e) {
    
    e.preventDefault();
    $("#firstRow:first").clone().insertAfter("#postcode_table #firstRow:first"); 
});

$('#products_on_sale tr td a').on('click', function() {
  
   var productID = $(this).attr('id');

  jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'buyHistory',
             'product_id': productID
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            $('#pid').html(productID);
    
             $('#preview #first').html('<table width=100% class="doropu-table" id="purchase_log_table"><tr><th>Poduct ID</th><th>Order ID</th><th>Purchased By</th><th>Puchased Price</th><th>Purchased Time</th><th> Rebate Calculation</th></tr>');
         
             if (result == 'null') {
                 $('<tr><td colspan="4">No Purchase found</td></tr>').insertAfter('#preview #first table tr:first');
             } else{
                
                for(var i = 0; i< result.length; i++) {
    
                    $('<tr><td>'+result[i].product_id+'</td><td>'+result[i].order_id+'</td><td> '+result[i].user_name+' ('+result[i].user_id+')</td><td> '+result[i].redeem_price+'</td><td> '+result[i].redeem_at+'</td><td><a href="#" class="rebate_view" id="'+result[i].product_id+'_'+result[i].user_id+'_'+result[i].redeem_price+'">View</a></td></tr>').insertAfter('#preview #first table tr:first');
                } 
             }
             
        },
         type: 'POST'
  });
   
   //price drop
    jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'priceDropHistory',
             'product_id': productID
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            $('#pid').html(productID);
  
             $('#preview_pricedrop #first_pricedrop').html('<table width=100% class="doropu-table" id="pricedrop_log_table"><tr><th>Poduct ID</th><th>Dropped Time</th><th>Dropped Price</th></tr>');
         
             if (result == 'null') {
                 $('<tr><td colspan="4">No Purchase found</td></tr>').insertAfter('#preview_pricedrop #first_pricedrop table tr:first');
             } else{
                
                for(var i = 0; i< result.length; i++) {
    
                    $('<tr><td>'+result[i].product_id+'</td><td> '+result[i].datetime+'</td><td> '+result[i].price_drop+'</td></tr>').insertAfter('#preview_pricedrop #first_pricedrop table tr:first');
                } 
             }
             
        },
         type: 'POST'
   });
});

/******topup**/
$('#export_csv_topup').on('click', function() {

    var args = [$('#topup_history'), 'export.csv'];
  console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});

$('#export_pdf_topup').on('click', function() {
    //   jQuery.ajax({
    //      url: backend.ajax_url,
    //      'data': {
    //          'action': 'exporttocsv'
             
    //      },
    //      error: function(msg, b, c) {
    //          console.debug('error');
    //     },
    //     dataType: 'json',
    //     success: function(result) {
            
    //      console.debug(result);
    //   },
    //   type: 'POST'
    //   });
    window.print();
    fnExcelReport2();
});

$('#export_excel_topup').on('click', function() {
     
    fnExcelReport2();
});



/******buy**/
$('#export_csv_buy').on('click', function() {

    var args = [$('#buy_history'), 'export.csv'];
  console.debug(this);
    console.debug(args);
    exportTableToCSV.apply(this, args);

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

      // Temporary delimiter characters unlikely to be typed by keyboard
      // This is to avoid accidentally splitting the actual contents
      tmpColDelim = String.fromCharCode(11), // vertical tab character
      tmpRowDelim = String.fromCharCode(0), // null character

      // actual delimiter characters for CSV format
      colDelim = '","',
      rowDelim = '"\r\n"',

      // Grab text from table into CSV formatted string
      csv = '"' + $rows.map(function(i, row) {
        var $row = $(row),
          $cols = $row.find('td');

        return $cols.map(function(j, col) {
          var $col = $(col),
            text = $col.text();

          return text.replace(/"/g, '""'); // escape double quotes

        }).get().join(tmpColDelim);

      }).get().join(tmpRowDelim)
      .split(tmpRowDelim).join(rowDelim)
      .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

      var blob = new Blob([decodeURIComponent(csv)], {
        type: 'text/csv;charset=utf8'
      });

      // Crashes in IE 10, IE 11 and Microsoft Edge
      // See MS Edge Issue #10396033
      // Hence, the deliberate 'false'
      // This is here just for completeness
      // Remove the 'false' at your own risk
      window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
      // HTML5 Blob        
      var blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8'
      });
      var csvUrl = URL.createObjectURL(blob);

      $(this)
        .attr({
          'download': filename,
          'href': csvUrl
        });
    } else {
      // Data URI
      var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

      $(this)
        .attr({
          'download': filename,
          'href': csvData,
          'target': '_blank'
        });
    }
  }
      
});

$('#export_pdf_buy').on('click', function() {
    //   jQuery.ajax({
    //      url: backend.ajax_url,
    //      'data': {
    //          'action': 'exporttocsv'
             
    //      },
    //      error: function(msg, b, c) {
    //          console.debug('error');
    //     },
    //     dataType: 'json',
    //     success: function(result) {
            
    //      console.debug(result);
    //   },
    //   type: 'POST'
    //   });
    window.print();
    fnExcelReport3();
});

$('#export_excel_buy').on('click', function() {
     
    fnExcelReport3();
});

var dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
      close: function() {
        
      }
    });
 
   
$('body').on('click', '.rebate_view', function(e) {
    e.preventDefault();
    var ids = $(this).attr('id');
    var id = ids.split('_');
    var productID = id[0];
    var userID = id[1];
    var purchasedPrice = id[2];
     
   dialog.dialog( "open" );
   $( "#dialog-form #product_id" ).html(productID);
   $( "#dialog-form #user_id" ).html(userID);
   
         jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'simulateRebateHistory',
             'product_id':productID,
             'user_id':userID,
             'purchase_price': purchasedPrice
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
 
             $('#dialog-form #simulate_rebate').html('<table width=100% class="doropu-table"><tr><th>Drop</th><th class="rebate">Rebate</th><th>date/Time</th></tr>');
              $('#dialog-form #actual_rebate').html('<table width=100% class="doropu-table"><tr><th class="rebate">Rebate</th><th>Available Fund</th><th>date/Time</th></tr>');
         
             if (result.simulate == 'null' && result.simulate != 'undefined') {
                 $('<tr><td colspan="4">No Purchase found</td></tr>').insertAfter('#dialog-form #simulate_rebate');
             } else{
                
                $('#final_price').html(result.simulate[result.simulate.length-1].final_price);
                $('#bought_at').html(result.simulate[result.simulate.length-1].purchase_price);
                $('#total_rebate').html(result.simulate[result.simulate.length-1].total_rebate);
                
                for(var i = 0; i< result.simulate.length; i++) {
                    if (typeof(result.simulate[i].price_drop) != 'undefined') {
                       
                         $('<tr><td>'+result.simulate[i].price_drop+'</td><td class="rebate"> '+result.simulate[i].rebate+'</td><td> '+result.simulate[i].date_time+'</td></tr>').insertAfter('#dialog-form #simulate_rebate tr:first');
                    }
                   
                }
             }
             
             if (result.actual == 'null' && result.actual != 'undefined') {
                 $('<tr><td colspan="4">No Purchase found</td></tr>').insertAfter('#dialog-form #actual_rebate');
             } else{
                
                for(var i = 0; i< result.actual.length; i++) {
    
                    $('<tr><td class="rebate">'+result.actual[i].rebate+'</td><td> '+result.actual[i].fund+'</td><td> '+result.actual[i].date_time+'</td></tr>').insertAfter('#dialog-form #actual_rebate tr:first');
                }
             }
             
             
      },
      type: 'POST'
      });
});


$('[data-release]').on('click', function() {
 
   var data_to_clear = $(this).attr('class');
   
   jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'clearCart',
             'data_to_clear':data_to_clear
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            alert(result);
        },
         type: 'POST'
        
   });
   
});

$('[data-tmrw]').on('click', function() {
 
   var data_to_clear = $(this).attr('class');
   
   jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'deleteTmrwProduct',
             'data_to_clear':data_to_clear
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            alert(result);
        },
         type: 'POST'
        
   });
   
});
   
   $('[data-change-quantity]').on('click', function() {
 
   var quantity = $(this).prev('input').val();
   
   var product_id = $(this).attr('id');
   console.debug(this);
   console.debug(quantity);
   console.debug(product_id);
   
   jQuery.ajax({
         url: backend.ajax_url,
         'data': {
             'action': 'changeQuantity',
             'quantity':quantity,
             'product_id':product_id
             
         },
         error: function(msg, b, c) {
             console.debug('error');
        },
        dataType: 'json',
        success: function(result) {
            alert(result);
        },
         type: 'POST'
        
   });
   
 
});