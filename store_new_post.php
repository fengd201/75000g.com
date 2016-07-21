<?php
	require_once(dirname(__FILE__).'/../src/core/coreUser_fns.php');
	session_start();
	
	if(isset($_POST['username'])) {
		$username = $_POST['username'];
	} else {
		$username = null;
	}
	if(isset($_POST['passwd'])) {
		$passwd = $_POST['passwd'];
	} else {
		$passwd =null;
	}

	if ($username && $passwd){
	//they have just tried logging in
		try {
			login($username, $passwd);
			//if they are in the databse register the user id
			$_SESSION['valid_user'] = $username;
		} catch (Exception $e){
			//unsuccessful login
			do_html_header('Problem:');
			echo 'You could not be logged in.
					You must be logged in to view this page.';
			do_html_url('login.php', 'Login');
			do_html_footer();
			exit;
		}
	}

	check_valid_user();


	include ('coreMsgBoard_fns.php');
	if($id = store_new_post($_POST, $_SESSION['valid_user'])) {
		include ('index.php');
	} else {
		$error = true;
		include ('new_post.php');
	}
?>	