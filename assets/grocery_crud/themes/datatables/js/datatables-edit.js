$(function(){

	var save_and_close = false;
    var cancel_button_clicked = false;

	$('#save-and-go-back-button').click(function(){
		save_and_close = true;

		$('#crudForm').trigger('submit');
	});

	$('#crudForm').submit(function(){
		$(this).ajaxSubmit({
			url: validation_url,
			dataType: 'json',
			beforeSend: function(){
				$("#FormLoading").show();
			},
			cache: false,
			success: function(data){
				$("#FormLoading").hide();
				if(data.success)
				{
					$('#crudForm').ajaxSubmit({
						dataType: 'text',
						cache: false,
						beforeSend: function(){
							$("#FormLoading").show();
						},
						success: function(result){
							$("#FormLoading").fadeOut("slow");
							data = $.parseJSON( result );
							if(data.success)
							{
								if(save_and_close)
								{
									if ($('#save-and-go-back-button').closest('.ui-dialog').length === 0) {
										window.location = data.success_list_url;
									} else {
										$(".ui-dialog-content").dialog("close");
										success_message(data.success_message);
									}

									return true;
								}

								$('.field_error').removeClass('field_error');

								form_success_message(data.success_message);

							}
							else
							{
                                if (data.error_message) {
                                    form_error_message(data.error_message);
                                } else {
								    form_error_message(message_update_error);
                                }

							}
						},
						error: function(){
							form_error_message( message_update_error );
						}
					});
				}
				else
				{
					$('.field_error').removeClass('field_error');
					form_error_message(data.error_message);
					$.each(data.error_fields, function(index,value){
						$('#crudForm input[name='+index+']').addClass('field_error');
					});
				}
			}
		});
		return false;
	});

	$('.ui-input-button').button();
	$('.gotoListButton').button({
        icons: {
        	primary: "ui-icon-triangle-1-w"
    	}
	});

	if( $('#cancel-button').closest('.ui-dialog').length === 0 ) {

		$('#cancel-button').click(function(){
			if( $(this).hasClass('back-to-list') || confirm( message_alert_edit_form ) )
			{
                if (typeof abort_edit_url === 'string') {
                    // FIXME: better solution to decide if unlock should be sent
                     $.ajax({
                        url: abort_edit_url,
                        dataType: "json",
                        success: function(data) {
                            if (data.success) {
                                // load the list!
                                window.location = list_url;   
                            } else {
                                // TODO: define error message in php code
                                error_message('Beim Schliessen ist leider ein Fehler aufgetreten.');
                            }
                        },
                        error: function() {
                            // TODO: define error message in php code
                            error_message('Beim Schliessen ist leider ein Fehler aufgetreten.');   
                        }
                    });
                } else {
                    window.location = list_url;
                }
            }
			return false;
		});

	}

});
