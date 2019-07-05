<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $documentList->getTitle(); ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-type">
  </head>
  <body>
    <h2><?php echo $documentList->getTitle(); ?></h2>
    <p>Zuletzt ge&auml;ndert am: <?php
		$lDatum = date_format($documentList->getLastUpdated(), "d.m.Y, H:i:s");
		$lDatum = $lDatum." Uhr";
		echo $lDatum;
	?></p>

