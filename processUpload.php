<?php
	require_once("PIPHP_UploadFile.php");
		$response=array(
			'message'=>'Unknown Error',
			'type'=>'',
			'code'=>-4, //0=success -1=fail
			'width'=>100,
			'height'=>100,
			'scale'=>1,
			'name'=>'',
			'path'=>'',
			'username'=>''
		);

		if (!empty($_FILES)) {
			$name='Filedata';
			$fileType=Array("image/jpeg", "image/jpg", "image/png", "image/bmp");
			$maxLen=9*1024*1024;
			
			$result=PIPHP_UploadFile($name,$fileType,$maxLen);
			
			$response['code']=$result[0];
			
			if($result[0]==0) {
				$response['message']='Upload Was Successful!';
				$response['type']=$result[1];
				if (isset($_POST['username'])) {
					$fileName=$_POST['username'].'.jpg';
					$response['username']=$_POST['username'];
				} else {
					$response['message']='Invalid User.';
				}
				$response['name']=$fileName;
				$filePath="portrait/";
				file_put_contents($filePath.$fileName, $result[2]);
				list($width,$height)=getimagesize($_SERVER['DOCUMENT_ROOT']."/".$filePath.$fileName);
				$response['width']=$width;
				$response['height']=$height;
				$response['path'] = $filePath.$fileName;
			}
			else {
				switch($result[0]) {
					case -1:$response['message']="Upload Failedaaaaaa"; break;
					case -2:$response['message']="Invalid File Type"; break;
					case -3:$response['message']="The image file you have selected is too large.";break;
					default:$response['message']="Error Code: ".$result[0];
				}
			}	
		}
		else{
			$response['message']="Upload Failed";
		}
		
		$json_str=json_encode($response);
		echo $json_str;
		
?>		