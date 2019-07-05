<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<div class="oliv_content">
		<h2>Willkommen auf Oliv</h2>
		<?php
		if(isset($access) && $access === true){ ?>
			<p>Klicken Sie oben in der Navigation auf "Dokumente", um bestehende Dokumente zu bearbeiten oder neue hochzuladen, oder auf "Listen", um eine Liste zu erfassen oder zu bearbeiten.</p>
		<?php }
		else{ ?>
			<p>Sie müssen sich anmelden, um auf Oliv zugreifen zu können: <a href="<?php echo site_url('auth') ?>">Anmelden</a>.
		<?php } ?>
	</div>

<!-- End of file welcome_message.php -->
<!-- Location: ./application/views/welcome_message.php -->