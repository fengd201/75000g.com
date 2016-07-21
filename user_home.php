<?php

require_once('coreUser_fns.php');
  session_start();
  do_html_header('Home');
  check_valid_user();
//showing memeber home page content
//if ($url_array = get_user_urls($_SESSION['valid_user'])) {
//	display_user_urls($url_array);
//}

  //give menu options
  display_user_menu();

  do_html_footer();

?>