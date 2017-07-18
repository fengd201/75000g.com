<?php
	include ('coreMsgBoard_fns.php');
	
	//$title = $_POST['title'];
	if(isset($_POST['poster'])) {
		$poster = $_POST['poster'];
	}
	
	if(isset($_POST['message'])) {
		$message = $_POST['message'];
	}	
	
	if(isset($_GET['parent'])) {
		$parent = $_GET['parent'];
	} else if(isset($_POST['parent'])){
		$parent = $_POST['parent'];
	}
	
	if(isset($scope)){
		if(!$scope) {
		$scope = 1;
		}
	}	
	
	if (isset($error)) {
		if(!$error) {
		if(!$parent) {
			$parent = 0;
			//if(!$title) {
			//	$title = 'New Post';
			//}
		} else {
			//Get post name
			//$title = get_post_title($parent);
			
			//Append re: (If it is not on main page)
			//if($parent != 0 && $parent != 1000) {
				//if(strstr($title, 'Re: ') == false) {
					//$title = 'Re: '.$title;
				//}
			}
			
			//make sure title will fit in db
			//$title = substr($title, 0, 20);
			
			//prepend a quoting pattern to the post you are replying to
			//$message = add_quoting(get_post_message($parent));
		}
	}
	
	if (isset($parent) && isset($scope) && isset($message) && isset($poster)) {
		display_new_post_form($parent, $scope, $message, $poster);
	} else {
		display_new_post_form();
	}

	if (isset($error)) {
		if($error) {
		echo "<p>Your message was not stored.</p>
			  <p>Make sure you have filled in all fields and try again.</p>";	
		}
	}

	do_html_footer();
	
?>