<?php

function uploadPortrait($username, $path) {
	// function to save user name and upload path to db
	// return true or exceptions
	if(!isset($username) || !isset($path)) {
		throw new Exception('Could not get user name or upload dir');
	}
	
	//connec to db
	$conn = db_connect();
	
	$result = $conn->query("INSERT INTO portrait (username, portrait_dir) VALUES ('".$username."', '".$path."') ON DUPLICATE KEY UPDATE portrait_dir='".$path."'");
	
	if (!$result){
		throw new Exception('Could not execute query');
	} else {
		return true;
	}
		
}

function getPortraitPath($username) {
	if(!isset($username)) {
		throw new Exception('Could not get user name');
	}
	
	//connec to db
	$conn = db_connect();
	
	$result = $conn->query("SELECT portrait_dir FROM portrait WHERE username='".$username."'");
	
	if (!$result){
		throw new Exception('Could not execute query for getting portrait path');
	} else {
		$row = $result->fetch_row();
		return $row[0];
	}
	
}

?>