<?php // Plug-in 15: Image Crop

function PIPHP_ImageCrop($image, $x, $y, $w, $h)
{
   // Plug-in 15: Image Crop
   //
   // This plug-in takes a GD image and returns a cropped
   // version of it. If any arguments are out of the
   // image bounds then FALSE is returned. The arguments
   // required are:
   //
   //    $image:   The image source
   //    $x & $y:  The top-left corner
   //    $w & $h : The width and height
	
   $img = ImageCreateFromJpeg($image);	
   $tw = imagesx($img);
   $th = imagesy($img);

   if ($x > $tw || $y > $th || $w > $tw || $h > $th)
      return FALSE;

   $temp = imagecreatetruecolor($w, $h);
   imagecopyresampled($temp, $img, 0, 0, $x, $y,
      $w, $h, $w, $h);
   return $temp;
}

?>
