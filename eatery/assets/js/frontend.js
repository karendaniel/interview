(function($) {

var conn = null;
var client = {

  fireAjax: function(data, callback, dType) {
    
    if (!dType) {
        var dType = 'json';
    }

  $.ajax({
     url: frontend.ajax_url,
     'data': data,
     error: function(msg, b, c) {

      console.debug('error');
      console.debug(msg);
      console.debug(b);
      console.debug(c);
    },
    dataType: dType,
    success: function(result) {
     callback(result);
     return false;
  },
  type: 'POST'
  });

  },
  
}

//add triggered
$("#add").on('click', function(e) {

});


//delete triggered
$(".delete").on('click', function(e) {
var element = $(this);
var id = $(this).attr('id');

      client.fireAjax({
       'url': frontend.ajax_url,
      'action': 'deleteListing',
      'id': id
     }, function(response) {
		$(element).closest('tr').remove();
     });  


  });
  })(jQuery);






