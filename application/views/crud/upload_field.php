<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<!-- <script src="<?php echo $assets_url; ?>/js/ui/jquery-ui-1.9.0.custom.min.js"></script> -->
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo $assets_url; ?>/js/jquery_plugins/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo $assets_url; ?>/js/jquery_plugins/jquery.fileupload.js"></script>
<div id="fileupload-<?php echo $unique; ?>" style="overflow:hidden; <?php echo $upload_button_display; ?>">
    <span >PDF hochladen</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload-input-<?php echo $unique; ?>" type="file" name="userfile" single style="position: absolute; height: 100%; width: 100%; opacity: 0; -moz-opacity: 0; margin: 0; top: 0; left: 0;">
</div>
<div id="filebuttons-<?php echo $unique; ?>" style="<?php echo $file_buttons_display ?>">
<a id="download-pdf-<?php echo $unique; ?>" href="<?php echo $download_url; ?>" target="_blank">PDF herunterladen</a>
    <input id="delete-pdf<?php echo $unique; ?>" type="button" value="PDF löschen" />
</div>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    
    'use strict';
    $('#download-pdf-<?php echo $unique; ?>').button();
    $('#fileupload-<?php echo $unique; ?>').button();

    // Init the fileupload plugin:
    $('#fileupload-input-<?php echo $unique; ?>').fileupload({
        url: '<?php echo $upload_url; ?>',
        dataType: 'json',
        maxNumberOfFiles: 1,
        maxFileSize: 104857600, // = 100 MB
        dropZone: null,
        //headers: {'Content-Type': 'application/pdf'},
        acceptFileTypes: /(\.|\/).pdf$/i,
        done: function (e, data) {
            $('#fileupload-<?php echo $unique; ?>').hide();
            $('#filebuttons-<?php echo $unique; ?>').show();
            var successEl = $('#report-success');
            successEl.html($('<p/>').text('<?php echo $upload_success_msg; ?>'));
            $('#report-error').hide();            
            successEl.show().focus();
        },
        fail: function (e, data) {
            var errorEl = $('#report-error');
            errorEl.html($('<p/>').text('<?php echo $upload_error_msg; ?>'));
            $('#report-success').hide();
            errorEl.show();
        }
    });

    $('#delete-pdf<?php echo $unique; ?>').button().click(function(){
            if( confirm('<?php echo $confirm_delete_msg; ?>') )
			{
				$.ajax({
                    // type: 'DELETE',
                    url: '<?php echo $delete_url; ?>',
					cache: false,
					success:function() {
                        $('#filebuttons-<?php echo $unique; ?>').hide();
    	                $('#fileupload-<?php echo $unique; ?>').show();
                        var successEl = $('#report-success');
                        successEl.html($('<p/>').text('<?php echo $delete_success_msg; ?>'));
                        $('#report-error').hide();
                        successEl.show().focus();
					},
                    error: function() {
                        var errorEl = $('#report-error');
                        errorEl.html($('<p/>').text('<?php echo $delete_error_msg; ?>'));
                        $('#report-success').hide();
                        errorEl.show().focus();
                    }
					// beforeSend: function(){
						// $('#upload-state-message-'+unique_id).html(string_delete_file);
						// $('#success_'+unique_id).hide();
						// $("#loading-"+unique_id).show();
						// $("#upload-button-"+unique_id).slideUp("fast");
					// }
				});
			}
			
			return false;
	});		    
});
</script>
<?php
/* End of file upload_field.php */
/* Location: ./application/views/crud/upload_field.php */
?>
