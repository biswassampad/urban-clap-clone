var datatable =  $('#example1').DataTable();
    function deleteRecord(url,id){
        var request = $.ajax({
                  url: url,
                  method: "POST",
                  dataType: "json"
                });
                 
                request.done(function( msg ) {
                    
                                if(msg.responce == 1)
    							{
                                    
                                    //$("#row_"+id+" .title").html() 
                                    $(".bs-example-modal-lg").modal('hide');
                                    $("#container_message").html(msg.message);
                                    datatable
                                    .row( "#row_"+id )
                                    .remove()
                                    .draw(false);
                                }
    							else
    							{
                                    $(".bs-example-modal-lg").modal('hide');
                                    $("#container_message").html(msg.error);
    							}
                });
                 
                request.fail(function( jqXHR, textStatus ) {
                    	    if (jqXHR.status === 0) {
    							alert ('Not connected.\nPlease verify your network connection.');
    						} else if (jqXHR.status == 404) {
    							alert ('The requested page not found. [404]');
    						} else if (jqXHR.status == 500) {
    							alert ('Internal Server Error [500].');
    						} else if (jqXHR.status === 'parsererror') {
    							alert ('Requested JSON parse failed.');
    						} else if (jqXHR.status === 'timeout') {
    							alert ('Time out error.');
    						} else if (jqXHR.status === 'abort') {
    							alert ('Ajax request aborted.');
    						} else {
    							alert ('Uncaught Error.\n' + jqXHR.responseText);
    						}
                });
        
    }