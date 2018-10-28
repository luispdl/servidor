<?php
	date_default_timezone_set ('America/Argentina/Buenos_Aires');
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	$hoy = date("Y/m/d");
	echo $hoy;
?>