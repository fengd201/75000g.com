<?php

function do_html_header($title)
{
  // print an HTML header
?>
  <html>
  <head>
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <title><?php echo $title;?></title>
    <style>
	  <?php
		include('baseStyle.css'); 
		include('bgStyle.css');
	  ?>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      hr { color: #3333cc; width=300; text-align=left}
      a { color: #000000 }
    </style>
  </head>
  <body>
  <img src="header1.png" alt="75000g.com logo" border=0
       align=middle width = 100% />
  <hr />
<?php
}

function do_html_footer()
{
  // print an HTML footer
?>
  </body>
  </html>
<?php
}

function do_html_heading($heading)
{
  // print heading
?>
  <h2><?php echo $heading;?></h2>
<?php
}

function do_html_URL($url, $name)
{
  // output URL as link and br
?>
  <br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
}

function display_site_info()
{
  // display some marketing info
?>

<?php
}

function display_login_form()
{
?>
  <div class="input-form-1">
  <form method='post' action='member.php'>
  <h2>Log in to your account</h2>
     <input type='text' name='username' placeholder="Username:">
     <input type='password' name='passwd' placeholder="Password">
     <input type='submit' value='Log in'>
	 <br><br><br>
     <a href='register_form.php'>Not a member?</a> | <a href='forgot_form.php'>Forgot your password?</a>
   </form>
   </div>
<?php
}

function display_registration_form()
{
?>
 <div class="input-form-1">
 <form method='post' action='register_new.php'>
 <h2>User Registration</h2>
     <input type='text' name='email' size=30 maxlength=100 placeholder="Email address">
     <input type='text' name='username'
                     size=16 maxlength=16 placeholder="Preferred username(max 16 chars)">
     <input type='password' name='passwd'
                     size=16 maxlength=16 placeholder="Password(between 6 and 16 chars)">
     <input type='password' name='passwd2' size=16 maxlength=16 placeholder="Confirm password">
     <input type='submit' value='Register'>
 </form>
 </div>
<?php 

}

function display_user_urls($url_array)
{
  // display the table of URLs

  // set global variable, so we can test later if this is on the page
  global $bm_table;
  $bm_table = true;
?>
  <br />
  <form name='bm_table' action='delete_bms.php' method='post'>
  <table width=300 cellpadding=2 cellspacing=0>
  <?php
  $color = "#cccccc";
  echo "<tr bgcolor='$color'><td><strong>Bookmark</strong></td>";
  echo "<td><strong>Delete?</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      // remember to call htmlspecialchars() when we are displaying user data
      echo "<tr bgcolor='$color'><td><a href=\"$url\">".htmlspecialchars($url)."</a></td>";
      echo "<td><input type='checkbox' name=\"del_me[]\"
             value=\"$url\"></td>";
      echo "</tr>"; 
    }
  }
  else
    echo "<tr><td>No bookmarks on record</td></tr>";
?>
  </table> 
  </form>
<?php
}

function display_user_menu()
{
  // display the menu options on this page
?>
<hr />
<a href="index.php">Home</a> &nbsp;|&nbsp;
<a href="change_passwd_form.php">Change password</a> &nbsp;|&nbsp;
<a href="logout.php">Logout</a> 
<hr />

<?php
}

function display_add_bm_form()
{
  // display the form for people to ener a new bookmark in
?>
<form name='bm_table' action='add_bms.php' method='post'>
<table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
<tr><td>New BM:</td><td><input type='text' name='new_url'  value="http://"
                        size=30 maxlength=255></td></tr>
<tr><td colspan=2 align='center'><input type='submit' value='Add bookmark'></td></tr>
</table>
</form>
<?php
}

function display_password_form()
{
  // display html change password form
?>
   <div class="input-form-1">
   <form action='change_passwd.php' method='post'>
       <input type='password' name='old_passwd' placeholder="Old password" size=16 maxlength=16>
       <input type='password' name='new_passwd' placeholder="New password" size=16 maxlength=16>
       <input type='password' name='new_passwd2' placeholder="Repeat new password" size=16 maxlength=16>
       <input type='submit' value='Change password'>
   </div>
<?php
};

function display_forgot_form()
{
  // display HTML form to reset and email password
?>
   <br />
   <form action='forgot_passwd.php' method='post'>
   <table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
   <tr><td>Enter your username</td>
       <td><input type='text' name='username' size=16 maxlength=16></td>
   </tr>
   <tr><td colspan=2 align='center'><input type='submit' value='Change password'>
   </td></tr>
   </table>
   <br />
<?php
};

function display_recommended_urls($url_array)
{
  // similar output to display_user_urls
  // instead of displaying the users bookmarks, display recomendation
?>
  <br />
  <table width=300 cellpadding=2 cellspacing=0>
<?php
  $color = "#cccccc";
  echo "<tr bgcolor=$color><td><strong>Recommendations</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      echo "<tr bgcolor='$color'><td><a href=\"$url\">".htmlspecialchars($url)."</a></td></tr>";
    }
  }
  else
    echo "<tr><td>No recommendations for you today.</td></tr>";
?>
  </table>
<?php
};

?>

<?php

function display_tree($expanded, $row = 0, $start = 0, $root=false, $scopeid=0)
{
  // display the tree view of conversations

  global $table_width;
  echo "<table class=\"message-board-1\" width = $table_width>";

  // see if we are displaying the whole list or a sublist
  if($start>0)
    $sublist = true;
  else
    $sublist = false;

  // construct tree structure to represent conversation summary 
  $tree = new treenode($start, '', '', 1, true, -1, $expanded, $sublist, $root, $scopeid);
  $tree->fetchingAll();

  // tell tree to display itself
  $tree->display($row, $sublist);
  
  echo '</table>';
}

?>

<?php

function do_html_header1($title)
{
  // print an HTML header
?>
  <html>
  <head>
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <title><?php echo $title;?></title>
    <style>
	  <?php
		include('baseStyle.css');
		include('bgStyle.css');	
	  ?>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; background-color: #2E2E2E; }
      hr { color: #3333cc; width=300; text-align=left}
      a { color: #000000 }
	  
    </style>
  </head>
  <body>
  <img src="header4.png" alt="75000g.com logo" border=0
       align=middle width = 100% />
<?php
}
?>

<?php

function display_msg_toolbar($case)
{
  global $table_width;
?>
  <table class="toolbar" cellpadding = 4 cellspacing = 0>
  <colgroup>
     <col width="5%">
     <col width="5%">
	 <col width="5%">
	 <col >
  </colgroup>
  <tr align = "right">
    <td >
	  <a href = "index.php">	
	  <div class="toolbardiv">
      <button class="toolbarbtn">HOME</button>
	  </div>
	  </a>
	</td>
	<td >
	  <div class="toolbardiv">
      <button class="toolbarbtn">ABOUT</button>
	  </div>
	</td>
	<td >
	  <div class= "dropdown">
      <button class="dropdownbtn">CONTACT</button>
	  <div class="dropdown-content">
		<a>contact@75000g.com</a>
	  </div>
	  </div>
	</td>
	<?php
	if ($case == 1) {
		echo "<td align=\"right\">"
	."<td align=\"right\">"
	."<a href = \"user_home.php\">"	
	."<div class=\"toolbardiv\">"
    ."<button class=\"toolbarbtn\">USER HOME</button>"
	."</div>"
	."</a>"
	."</td>";
	} else {
		echo "<td align=\"right\">"
	."<td align=\"right\">"
	."<a href = \"login.php\">"	
	."<div class=\"toolbardiv\">"
    ."<button class=\"toolbarbtn\">LOG IN</button>"
	."</div>"
	."</a>"
	."</td>";
	}
	?>
  </tr>
  </table>
<?php
}
?>

<?php
function display_post($post)
{
  global $table_width;
 
  if(!$post)
    return;
?>
  <table width = <?php echo $table_width?> cellpadding = 4 cellspacing = 0>
  <tr>
    <td bgcolor = "#cccccc">
      <b>From: <?php echo $post['poster'];?></b><br />
      <b>Posted: <?php echo $post['posted'];?></b>
    </td>
    <td bgcolor = "#cccccc" align = "right">
      <a href = 'new_post.php?parent=0'><img src='images/new-post.gif' 
                 border = 0 width = 99 height = 39 /></a><a
         href = 'new_post.php?parent=<?php echo $post['postid'];?>'><img 
                 src='images/reply.gif' border = 0 width = 99 
                 height = 39 /></a><a
         href = 'index.php?expanded=<?php echo $post['postid'];?>'><img 
                 src="images/index.gif" border = 0 width = 99 height = 39 /></a>
  </td>
  </tr>
  <tr><td colspan = 2>
  <?php echo nl2br($post['message']);?>
  </td></tr>
  </table>
<?php
}
?>

<?php
function display_new_post_form($parent = 0, $scope = 1, $message='', $poster='')
{
  global $table_width;
?>
  <table class="input-form-new-post">
  <form action = "store_new_post.php?expand=<?php echo $parent;?>#<?php echo $parent;?>" 
        method = "post">
  <tr>
    <td colspan = 2 >
      <textarea name = "message" rows = 10 cols = 55><?php echo stripslashes($message);?></textarea>
    </td>
  </tr>
  <tr>
    <td colspan = 2 align = "center">
      <input type = "submit" name = "post" value = "POST">
    </td>
    <input type = "hidden" name = "parent" value = <?php echo $parent;?> >
    <input type = "hidden" name = "scope" value = <?php echo $scope;?> >
  </tr>
  </form>
  </table>
<?php
}
?>

<?php
function display_new_post()
{
	include ('new_post.php');
}
?>

<?php

function redirect_to_home()
{
  // go to home page
?>
  <html>
  <head>
	<meta http-equiv="refresh" content="1;url=index.php">
  </head>
  <body>
        If you are not redirected automatically, follow the <a href='index.php'>link</a>
  </body>
<?php
}
?>

<?php

function showPortrait($path) {
	
	echo '<img src="'.$path.'" style="width:100px;height:100px;"/>';

}
?>
