<?php

	require_once('coreUser_fns.php');
	session_start();
	check_valid_user();
	do_html_header('Upload Portrait');
	include ('/upload_portrait.html');
?>