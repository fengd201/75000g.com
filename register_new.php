<?php
	require_once('coreUser_fns.php');
	
	//Create short variable names
	$email=$_POST['email'];
	$username=$_POST['username'];
	$passwd=$_POST['passwd'];
	$passwd2=$_POST['passwd2'];
	//Start session which may be needed later
	//Start it now because it must go before header
	session_start();
	try{
		//Check forms filled in_array
		if (!filled_out($_POST)){
			throw new Exception('You have not filled the form out correctly -
				please go back and try again.');
		}
		
		//Email address not valid
		if (!valid_email($email)){
			throw new Exception('That is not a valid email address'.
				'Please go back and try again.');
		}
		
		//Password not the same
		if ($passwd != $passwd2){
			throw new Exception('The passwords you entered do not match -
				please go back and try again.');
		}
		
		//Check password length is ok
		//ok if username truncates, but password will get
		//munged if they are too long.
		if (strlen($passwd) < 6 || strlen($passwd) > 16) {
			throw new Exception('Your password must be between 6 and 16 characters.
				Please go back and try again.');
		}
		
		//Attempt to register
		//this function can also throw an exception
		register($username, $email, $passwd);
		//register session variable
		$_SESSION['valid_user'] = $username;
		
		//provide link to member page
		do_html_header('Registration successful');
		echo 'Your registration was successful. Go to the member page:';
		do_html_url('member.php', 'Go to member page');
		
		//end page
		do_html_footer();
	}
	catch (Exception $e){
		do_html_header('Problem:');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>