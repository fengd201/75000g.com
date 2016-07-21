<?php

	//include function libraries
	include ('coreMsgBoard_fns.php');
	$postid = $_GET['postid'];
	//get post details
	$post = get_post($postid);
	
	do_html_header1($post['title']);
	
	//display post
	display_post($post);
	
	//if post has any replies, show the tree view of them
	if($post['children']) {
		echo "<br><br>";
		display_replies_line();
		display_tree($_SESSION['expanded'], 0, $postid, false, 0);
	}
	
	do_html_footer();
	
?>