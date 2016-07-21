<?php
function get_user_urls($username){
	//extract from the database all the urls this user has stored
	
	$conn = db_connect();
	$result = $conn->query("select * from user");
	
	if (!$result){
		return false;
	}
	
	//create an array of the URLs
	$url_array = array();
	for ($count = 1; $row = $result->fetch_row(); ++$count){
		$url_array[$count] = $row[0];
	}
	return $url_array;
}
?>