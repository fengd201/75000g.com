<?php

  require_once('coreUser_fns.php');
  require_once('portrait_fns.php');
  session_start();
  do_html_header('Home');
  check_valid_user();
//showing memeber home page content
//if ($url_array = get_user_urls($_SESSION['valid_user'])) {
//	display_user_urls($url_array);
//}
	
  //give menu options
  display_user_menu();
  
  // Get path of stored portrait
  $path = getPortraitPath($_SESSION['valid_user']);
  
  // Display Portrait
  if($path) {
	
	  showPortrait($path);
?>
	<a href="/upload_portrait.php">Change Portrait</a>
<?php	
  } else {
?>
	  <a href="/upload_portrait.php"><img src="/defaultPortrait.png" style="width:100px;height:100px;" /></a>
<?php
  }
  
  do_html_footer();

?>