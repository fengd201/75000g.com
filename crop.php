<?php
	require_once('PIPHP_ImageCrop.php');
	require_once('core_pic_upload_fns.php');
	$json_str = json_decode($_POST['data']);
	$x = $json_str->x1;
	$y = $json_str->y1;
	$scale = $json_str->scale;
	$cropWidth = $json_str->cropWidth;
	$cropHeight = $json_str->cropHeight;
	$path = $json_str->path;
	$filename = $json_str->name;
	$tofilename = $filename;
	$username = $json_str->username;
	
	$realX = $x/$scale;
	$realY = $y/$scale;
	$realWidth = $cropWidth/$scale;
	$realHeight = $cropHeight/$scale;
	
	$cropImage=PIPHP_ImageCrop("http://".$_SERVER['SERVER_NAME']."/".$path, $realX, $realY, $realWidth, $realHeight);
	
	$targetDir = '/portrait2/';
	$targetFile = $targetDir.$tofilename;
	
	imagejpeg($cropImage, $_SERVER['DOCUMENT_ROOT'].$targetFile);
	$result = uploadPortrait($username, $targetDir.$filename);
	
	if($result) {
		echo $targetDir.$filename.'?'.time();
	} else {
		echo "upload failed.";
	}

	//echo $_SERVER['DOCUMENT_ROOT'].$targetFile;
?>	