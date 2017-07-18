<?php

function expand_all(&$expanded) {
	//Mark all thread with expandFlg equal to 1 as to be shown expanded
	$conn = db_connect();
	$query = "select postId from MSG_DTLS where expandFlg = 1";
	$result = $conn->query($query);
	$num = $result->num_rows;
	for ($i=0; $i<$num; $i++){
		$this_row = $result->fetch_row();
		$expanded[$this_row[0]] = true;
	}
}

//NOT WORKiNG-------------------------------------
//-------------------------------------------------
function reformat_date($datetime)
{
  // put date in US format, discard seconds
  list($year, $month, $day, $hour, $min, $sec) = split( '[: -]', $datetime );
  return "$hour:$min $month/$day/$year";
}

function get_post($postid)
{
  // extract one post from the database and return as an array

  if(!$postid) return false;

  $conn = db_connect();
  
  //get all header information from MSG_DTLS
  $query = "select * from MSG_DTLS where postid = $postid";
  $result = $conn->query($query);
  if($result->num_rows!=1)
    return false;
  $post = $result->fetch_assoc();

  // get message from body and add it to the previous result
  $query = "select * from MSG_BODY where postid = $postid";
  $result2 = $conn->query($query);
  if($result2){
		if($result2->num_rows>0) 
		{
			$body = $result2->fetch_assoc();
			if($body)
			{
			$post['message'] = $body['message'];
			} else {
			$post['message'] = '';
			}
		} else {
			$post['message'] = '';
		}
  } else {
	  $post['message'] = '';
  }
  
  return $post; 
}

function get_post_message($postid) {
	//extract one post's message from the database
	
	if(!postid) {
		return '';
	}
	
	$conn = db_connect();
	
	$query = "select message from MSG_BODY where postid = '".$postid."'";
	$result = $conn->query($query);
	if($result->num_rows>0) {
		$this_row = $result->fetch_array();
		return $this_row[0];
	}
}

function add_quoting($string, $pattern = '>') {
	//add a quoting pattern to mark text quoted in your reply
	return $pattern.str_replace("\n", "<\n$pattern", $string);
}

function store_new_post($post, $poster) {
	//validate clean and store a new post
	
	$conn = db_connect();
	//check no fields are blank
	if (!filled_out($post)) {
		return false;
	}
	$post = clean_all($post);
	
	//check parent exists
	if($post['parent']!=0) {
		$query = "select postid from MSG_DTLS where postid = '".$post['parent']."'";
		$result = $conn->query($query);
		if ($result->num_rows!=1) {
			return false;
		}
	}

	//check not a duplicate
	$query = "select MSG_DTLS.postid from MSG_DTLS, MSG_BODY where MSG_DTLS.postid = MSG_BODY.postid and MSG_DTLS.parent = "
				.$post['parent']." and MSG_DTLS.poster = '".$poster."' and MSG_DTLS.scopeid = '".$post['scope']."' and MSG_BODY.message = '".$post['message']."'";
				
	$result = $conn->query($query);
	if(!$result) {
		return false;
	}
	
	if($result->num_rows>0) {
		$this_row = $result->fetch_array();
		return $this_row[0];
	}
	
	$query = "insert into MSG_DTLS values
				('".$post['parent']."',
				 '".$poster."',
				 0,
				 '".$post['scope']."',
				 now(),
				 NULL,
				 1
				)";
	
	$result = $conn->query($query);
	if(!$result) {
		return false;
	}

	//note that our parent now has a child
	$query = "update MSG_DTLS set children= 1 where postid='".$post['parent']."'";
	$result = $conn->query($query);
	if(!$result) {
		return false;
	}

	//find our postid, note that there could be multiple details that are same except for id and probably posted time
	$query = "select MSG_DTLS.postid from MSG_DTLS left join MSG_BODY on MSG_DTLS.postid = MSG_BODY.postId
				where parent='".$post['parent']."' and poster='".$poster."' and MSG_BODY.postid is NULL";

	$result = $conn->query($query);
	if(!$result) {
		return false;
	}

	if($result->num_rows>0) {
		$this_row = $result->fetch_array();
		$id = $this_row[0];
	}

	if($id) {
		$query = "insert into MSG_BODY values ($id, '".$post['message']."')";
		$result = $conn->query($query);
		if(!$result) {
			return false;
		}
		
		return $id;
	}
}

function delete_selected_msg($postid, $poster) {
	//delele msg
	
	$conn = db_connect();
	
	if($postid) {
		$query = "select poster from MSG_DTLS where postid = '".$postid."'";
		$result = $conn->query($query);
		$this_row = $result->fetch_array();
		$relatedPoster = $this_row[0];
	} else {
		return false;
	}
	
	if(isset($relatedPoster)) {
		if($relatedPoster == $poster) {
			$query = "delete from MSG_DTLS where postid = '".$postid."'";
			$query2 = "delete from MSG_BODY where postid = '".$postid."'";
			$result = $conn->query($query);
			$result2 = $conn->query($query2);
			if(!$result || !$result2) {
				return false;				
			}
			echo "<script type='text/javascript'>alert('Record was deleted.');</script>";
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
	return false;
}

?>