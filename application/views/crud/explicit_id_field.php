<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<input type="text" maxlength="100" value="<?php echo $value; ?>" name="explicitId" id="field-explicitId">
<input id="generate-explicit-id-button" type="button" title="<?php echo $button_title; ?>" value="Generieren">
<script type="text/javascript">
$(function() {
    $('#generate-explicit-id-button').button()
        .click(function() {
            // get the values:
            var title = $('#field-title').val(),
                authors = $('#field-authors').val(),
                year = $('#field-year').val(), 
                explicitId = '';

            // Add lastname of author(s)
            var authorsArray = authors.split("/");
            for (var i = 0; i < authorsArray.length; i++) {
                // remove the first name
                var authorName = authorsArray[i];
                var endOfLastname = authorName.indexOf(",");
                if (endOfLastname == -1) {
                    authorsArray[i] = $.trim(authorName);    
                } else {
                    authorsArray[i] = $.trim(authorName.substring(0, endOfLastname));
                }
            }
            // combine lastnames of the authors:
            explicitId += authorsArray.join("_");
            
            // Add year
            // Extract first publication, if given
            year = year.replace(/\[\d+\]/, '');
            // Delete edition number, if given
            year = year.replace(/<sup>\d+<\/sup>/, '');
            
            explicitId += year.length > 0 ? "_" + $.trim(year) : '';
            explicitId = explicitId.replace(' ', '_');
            if (explicitId == '') {
                form_error_message('Es konnte kein Vorschlag für die explizite ID generiert werden.');
            } else {
               $('#field-explicitId').val(explicitId);
            }
        })
    });
</script>
<?php
/* End of file explicit_id_field.php */
/* Location: ./application/views/crud/explicit_id_field.php */
?>
