<?php

function get_user_privilege($username, $privilegetype) {
	//connec to db
	$conn = db_connect();
	
	//fetch privilege
	$result = $conn->query("select ".$privilegetype." from user_privilege where username='".$username."'");
	
	if (!$result){
		throw new Exception('Could not get user privileges - please try again later.');
	} else {
		$row = $result->fetch_row();
		return $row[0];
	}
}

?>