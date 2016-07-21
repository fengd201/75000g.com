<?php

require_once('coreUser_fns.php');
session_start();

$username = $_POST['username'];
$passwd = $_POST['passwd'];

if ($username && $passwd){
//they have just tried logging in
	try {
		login($username, $passwd);
		//if they are in the databse register the user id
		$_SESSION['valid_user'] = $username;
	} catch (Exception $e){
		//unsuccessful login
		do_html_header('Problem:');
		echo 'Sorry. . . Your username and password do not match. Please try again.';
		do_html_url('login.php', 'Login');
		do_html_footer();
		exit;
	}
}

//do_html_header('Home');
//check_valid_user();
//showing memeber home page content
//if ($url_array = get_user_urls($_SESSION['valid_user'])) {
//	display_user_urls($url_array);
//}

//give menu options
//display_user_menu();
redirect_to_home();
do_html_footer();

?>