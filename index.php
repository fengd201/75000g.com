<?php
	include ('coreMsgBoard_fns.php');
	require_once('coreUser_fns.php');
	session_start();
	
	//Check if we have created our session variable
	if (!isset($_SESSION['expanded'])) {
		$_SESSION['expanded'] = array();
	}
	
	//Check if an expanded button was pressed
	//Expand might equal 'all' or a postId ot not be set
	if(isset($_GET['expand'])) {
		if ($_GET['expand'] == 'all') {
			expand_all($_SESSION['expand']);
		} else {
			$_SESSION['expand'][$_GET['expand']] = true;
		}
	}
	
	//Check if a collapse button was pressed
	//Collapse might equal all or a postId or not be set
	if(isset($_GET['collapse'])) {
		if($_GET['collapse'] == 'all') {
			$_SESSION['expand'] = array();
		} else {
			unset ($_SESSION['expand'][$_GET['collapse']]);
		}
	}
	
	//Check if a delete button was pressed
	if(isset($_GET['delete'])) {
		$loggedUser = get_logged_user();
		if($loggedUser) {
			$result = delete_selected_msg($_GET['delete'], $loggedUser);
		}
	}
	
	do_html_header1('75000g.com');
	
	if(check_user_login()) {
		display_msg_toolbar(1);
	} else {
		display_msg_toolbar(2);
	}
	
	display_new_post();
	
	//Display the tree view of conversation
	display_tree($_SESSION['expanded'],0,0,true,1);
	
	do_html_footer();

?>