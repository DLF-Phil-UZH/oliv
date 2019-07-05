<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="field-preview" class="readonly_label">
<?php echo $value; ?>
</div>
<script type="text/javascript">
$(function() {
    $('#crudForm').change(function(event) {
            // get the values:
            var title = $('#field-title').val(),
                authors = $('#field-authors').val(),
                editors = $('#field-editors').val(),
                publication = $('#field-publication').val(),
                volume = $('#field-volume').val(),
                year = $('#field-year').val(), 
                places = $('#field-places').val(), 
                publishingHouse = $('#field-publishingHouse').val(), 
                pages = $('#field-pages').val(), 
				webURL = $('#field-webURL').val(), 
				webAccess = $('#field-webAccess').val(), 
				config = $('#field-config_citation_style').val(), 
                preview = '';

            // This algorithm is a copy of the corresponding php function
            // toFormattedString() in Document_model, keep in mind, when
            // changing the php code.
            // FIXME: preview should be generated on the server by 
            // the same function.
			
			switch (config) {
			
			case 'MLA':
				// prepare year, places, publishingHouse, pages (always same formatting):
				var yearPart = year != '' ? ' (' + year + ')' : '';
				var placesPart = (places != '' || publishingHouse != '') ? '. ' + places : '';            
				var publishingHousePart = (places.length != '' && publishingHouse != '') ? ': ' + publishingHouse : publishingHouse;
				var pagesPart = pages.length != '' ? '. ' + pages : '';
				var endPart = '.';
				
				// If document is a monography without page indication
				if((editors.length + pages.length + publication.length) == 0 && authors.length > 0){
					preview += authors;
					preview += ". <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += ", ";
					preview += year;
					preview += endPart;
				}
				// If document is a monography with page indication
				else if((editors.length + publication.length) == 0 && pages.length > 0 && authors.length > 0){
					preview += authors;
					preview += ". <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += ", ";
					preview += year;
					preview += pagesPart;
					preview += endPart;
				}
				// If document is a chapter of a book
				else if(editors.length == 0 && places.length > 0 && publishingHouse.length > 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += ". \"";
					preview += title;
					preview += ".\"";
					preview += " <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += ". ";
					preview += authors;
					preview += placesPart;
					preview += publishingHousePart;
					preview += ", ";
					preview += year;
					preview += pagesPart;
					preview += endPart;
				}
				// If document is a magazine article
				else if((editors.length + places.length + publishingHouse.length) == 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += ". \"";
					preview += title;
					preview += ".\"";
					preview += " <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += yearPart;
					preview += ": ";
					preview += pages;
					preview += endPart;
				}
				// If document is an article in a book
				else if(title.length != 0 &&
						authors.length != 0 &&
						publication.length != 0 &&
						editors.length != 0 &&
						places.length != 0 &&
						publishingHouse.length != 0 &&
						pages.length != 0) {
					preview += authors;
					preview += ". \"";
					preview += title;
					preview += ".\"";
					preview += " <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += ". Hrsg. ";
					preview += editors;
					preview += placesPart;
					preview += publishingHousePart;
					preview += ", ";
					preview += year;
					preview += pagesPart;
					preview += endPart;
				}				
				else if (title.length != 0) {
					// fallback: if nothing matched 
					preview += authors != '' ? authors + ': ' : '';
					preview += '<i>' + title + '</i>';
					preview += endPart;
				}
				else {
					preview = 'Vorschau konnte nicht generiert werden.';
				}
			break;
			
            case 'APA':
				// prepare year, places, publishingHouse, pages (always same formatting):
				var yearPart = year != '' ? ' (' + year + ')' : '';
				var placesPart = (places != '' || publishingHouse != '') ? '. ' + places : '';            
				var publishingHousePart = (places.length != '' && publishingHouse != '') ? ': ' + publishingHouse : publishingHouse;
				var pagesPart = pages.length != '' ? ', ' + pages : '';
				var endPart = '.';
				
				// If document is a website
				if (webURL.length != 0) {
					preview += authors;
					preview += yearPart;
					preview += ". ";
					preview += title;
					preview += ". <i> ";
					preview += publication;
					preview += "</i>. ";
					preview += "Abgerufen am ";
					preview += webAccess;
					preview +=", von ";
					preview += webURL;
					preview += endPart;
				}
				// If document is a monography without page indication
				else if((editors.length + pages.length + publication.length) == 0 && authors.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ". <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " (Bd. ";
						preview += volume;
						preview += ")";
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += endPart;
				}
				// If document is a monography with page indication
				else if((editors.length + publication.length) == 0 && pages.length > 0 && authors.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ". <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " (Bd. ";
						preview += volume;
						preview += ", S. ";
						preview += pages;
						preview += ")";
					}
					else{
						preview += " (S. ";
						preview += pages;
						preview += ")";
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += endPart;
				}
				// If document is a chapter of a book
				else if(editors.length == 0 && places.length > 0 && publishingHouse.length > 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ". ";
					preview += title;
					preview += ". In ";
					preview += authors;
					preview += ", <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " (Bd. ";
						preview += volume;
						preview += ", S. ";
						preview += pages;
						preview += ")";
					}
					else{
						preview += " (S. ";
						preview += pages;
						preview += ")";
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += endPart;
				}
				// If document is a magazine article
				else if((editors.length + places.length + publishingHouse.length) == 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ". ";
					preview += title;
					preview += ". <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += "(";
						preview += volume;
						preview += ")";
					}
					preview += pagesPart;
					preview += endPart;
				}
				// If document is an article in a book
				else if(title.length != 0 &&
						authors.length != 0 &&
						publication.length != 0 &&
						editors.length != 0 &&
						places.length != 0 &&
						publishingHouse.length != 0 &&
						pages.length != 0) {
					preview += authors;
					preview += yearPart;
					preview += ". ";
					preview += title;
					preview += ". In ";
					preview += editors;
					preview += " (Hrsg.), <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " (Bd. ";
						preview += volume;
						preview += ", S. ";
						preview += pages;
						preview += ")";
					}
					else{
						preview += " (S. ";
						preview += pages;
						preview += ")";
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += endPart;
				}
				else if (title.length != 0) {
					// fallback: if nothing matched 
					preview += authors != '' ? authors + '. ' : '';
					preview += '<i>' + title + '</i>';
					preview += endPart;
				}
				else {
					preview = 'Vorschau konnte nicht generiert werden.';
				}
			break;

            case 'ROSE':		
				// prepare year, places, publishingHouse, pages (always same formatting):
				var yearPart = year != '' ? ' (' + year + ')' : '';
				var placesPart = (places != '' || publishingHouse != '') ? ', ' + places : '';            
				var publishingHousePart = (places.length != '' && publishingHouse != '') ? ': ' + publishingHouse : publishingHouse;
				var pagesPart = pages.length != '' ? ', ' + pages : '';
				var endPart = '.';
				
				// If document is a monography without page indication
				if((editors.length + pages.length + publication.length) == 0 && authors.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ": <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += endPart;
				}
				// If document is a monography with page indication
				else if((editors.length + publication.length) == 0 && pages.length > 0 && authors.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ": <i>";
					preview += title;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += pagesPart;
					preview += endPart;
				}
				// If document is a chapter of a book
				else if(editors.length == 0 && places.length > 0 && publishingHouse.length > 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ": \"";
					preview += title;
					preview += "\", in: ";
					preview += authors;
					preview += ": <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += pagesPart;
					preview += endPart;
				}
				// If document is a magazine article
				else if((editors.length + places.length + publishingHouse.length) == 0 && authors.length > 0 && publication.length > 0){
					preview += authors;
					preview += yearPart;
					preview += ": \"";
					preview += title;
					preview += "\", in: <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += pagesPart;
					preview += endPart;
				}
				// If document is an article in a book
				else if(title.length != 0 &&
						authors.length != 0 &&
						publication.length != 0 &&
						editors.length != 0 &&
						places.length != 0 &&
						publishingHouse.length != 0 &&
						pages.length != 0) {
					preview += authors;
					preview += yearPart;
					preview += ": \"";
					preview += title;
					preview += "\", in: ";
					preview += editors;
					preview += ": <i>";
					preview += publication;
					preview += "</i>";
					if(volume.length > 0){
						preview += " ";
						preview += volume;
					}
					preview += placesPart;
					preview += publishingHousePart;
					preview += pagesPart;
					preview += endPart;
				}
				else if (title.length != 0) {
					// fallback: if nothing matched 
					preview += authors != '' ? authors + ': ' : '';
					preview += '<i>' + title + '</i>';
					preview += endPart;
				}
				else {
					preview = 'Vorschau konnte nicht generiert werden.';
				}
			break;
			}
            $('#field-preview').html(preview);
        })
    });
</script>
<?php
/* End of file preview_field.php */
/* Location: ./application/views/crud/preview_field.php */
?>
