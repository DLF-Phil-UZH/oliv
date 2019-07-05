<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo doctype('html5'); ?>
<html>
    <head>
        <title><?php echo $title; ?></title>
		<?php
		echo meta('Content-type', 'text/html; charset=utf-8', 'equiv');
		echo meta('description', 'Oliv');
		echo link_tag(base_url('/assets/css/uzh.css')); // UZH standard, lower priority
		echo link_tag(base_url('/assets/css/oliv-common.css')); // Higher priority, customized
		?>
   	</head>
    <body>
		<?php
			if(isset($width) && strcmp($width, 'small') === 0){
				echo '<div class="bodywidthsmall">'; // small body
			}
			else{
				echo '<div class="bodywidth">'; // allover body
			}
		?>
			<div id="topline">
                <?php
                if (isset($logged_in) && $logged_in) { 
                    echo '<p id="logout"><a href="' . site_url('/auth/logout') . '" >Abmelden</a></p>';
                } ?>
				<p>Online Literaturlisten-Verwaltung</p>
            </div>
			<div class="floatclear">
			</div>
			<div id="headerarea">
				<div id="uzhlogo">
					<a href="http://www.uzh.ch">
						<img alt="uzh logo" height="80" src="<?php echo base_url('/assets/images/uzh_logo_d_pos_web_main.jpg'); ?>" width="231" />
					</a>
                </div>
				<div id="olivlogo">
					<a href="http://www.phil.uzh.ch/fakultaet/dlf.html">
						<img alt="oliv logo" height="80" src="<?php echo base_url('/assets/images/oliv.png'); ?>" width="133" />
					</a>
                </div>
               	<h1 style="clear:both">
					<a href="<?php echo site_url('welcome'); ?>">
						<heading>Oliv</heading>
					</a>
				</h1>
			</div>
			<div class="floatclear">
			</div>
			<div class="endheaderline">
			</div>
			<?php
				if(isset($access) && $access === true){ ?>
					<div id="primarnav">
						<a class="namedanchor" name="primarnav"><!----></a>
						<?php
							if(strcmp($page, 'documents') === 0){ ?>
								<a class="active" href="<?php echo site_url('manager/documents'); ?>">Dokumente</a>
							<?php }
							else{ ?>
								<a href="<?php echo site_url('manager/documents'); ?>">Dokumente</a>
							<?php } ?>
						<div class="linkseparator">&#8226;</div>
						<?php
							if(strcmp($page, 'lists') === 0){ ?>
								<a class="active" href="<?php echo site_url('manager/lists'); ?>">Listen</a>
							<?php }
							else{ ?>
								<a href="<?php echo site_url('manager/lists'); ?>">Listen</a>
							<?php }
						if(isset($admin) && $admin === true){ ?>
							<div class="linkseparator">&#8226;</div>
							<?php
								if(strcmp($page, 'users') === 0){ ?>
									<a class="active" href="<?php echo site_url('admin/users'); ?>">Benutzer</a>
								<?php }
								else{ ?>
									<a href="<?php echo site_url('admin/users'); ?>">Benutzer</a>
								<?php } ?>
							<div class="linkseparator">&#8226;</div>
							<?php
								if(strcmp($page, 'user_requests') === 0){ ?>
									<a class="active" href="<?php echo site_url('admin/user_requests'); ?>">Benutzeranfragen</a>
								<?php }
								else{ ?>
									<a href="<?php echo site_url('admin/user_requests'); ?>">Benutzeranfragen</a>
								<?php }
						} ?>
							<div class="linkseparator">&#8226;</div>
                            <a href="<?php echo site_url('assets/help/anleitung_eingabe_zitationsstile.html') ?>" target="_blank">Anleitung zur Eingabe</a>
							<div class="linkseparator">&#8226;</div>
                            <a href="<?php echo site_url('assets/help/anleitung_liste_einbinden.html') ?>" target="_blank">Anleitung zum Einbinden</a>
					</div>
				<?php } ?>
			<div class="floatclear">
			</div>

<!-- End of file header.php -->
<!-- Location: ./application/views/header.php -->
