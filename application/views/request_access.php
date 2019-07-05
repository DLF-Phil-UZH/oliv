<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<link rel="stylesheet" href="assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css" />
	<link rel="stylesheet" href="assets/grocery_crud/themes/datatables/css/datatables.css" />
    <script src="<?php echo site_url('assets/grocery_crud/js/jquery-1.10.2.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/grocery_crud/js/common/lazyload-min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js'); ?>"></script>
	<div class="oliv_content">
		<h2>Zugriff verweigert</h2>
		<p>Herzlich willkommen auf Oliv.</p>
		<p>Oliv ist ein Dienst der E-Learning Koordination der PhF zum Verwalten von Literaturlisten.</p>
		<p>Tut uns leid, aber Sie sind nicht berechtigt, Literaturlisten
		zu verwalten. Beantragen Sie 
		jetzt mit nur einem Klick einen Zugang:</p>
		<p><a id="request-access-button" href="<?php echo site_url('auth/request_access') ?>" target="_self">Zugang beantragen</a></p>
		<p>Zugriff auf die Literaturlisten haben Sie direkt über die
		entsprechenden OLAT-Kurse.</p>
	</div>
	<script>
        $(function(){
            $( '#request-access-button' ).button();
        });
	</script>
