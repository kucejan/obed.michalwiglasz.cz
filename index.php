<?php

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if ($_SERVER['REMOTE_ADDR'] == '78.102.201.145') {
	header("Location: /zborovska");
	exit(0);
}

header("Location: /fit");
