<?php
/**
 * @param $tag			| the name of the input field that was used to upload the file
 * @param $size			| the allowed size of the file in bytes
 * @param $format		| the allowed formats for the file
 * @param $type			| the allowed file mime types for the file (extra security, never trust the user)
 * @param $filename		| the base name of the file after upload
 * @param $directory	| the directory in which the file will be stored
 * @param $checkimg		| true for only image / false for other files 
 * @param $checkdim		| if true will check uploaded image dimentions with $w & $h
 * @param $error		| the error generated from the function
 * @return boolean      | returns true if the file was uploaded successfully, otherwise false
 *
 * Simple Upload File Function
 */
function uploadFile($tag, $size, $format, $type, $filename, $directory, $checkimg, $checkdim, $w=null, $h=null, &$error=null)
{
	// Added Image Security (Enable/Disable)
	if($checkimg) {
		if(getimagesize($_FILES[$tag]["tmp_name"]) === false) {
			$error = 'Upload a valid image file';
			return;
		}
	}
	
	// If Image Enabled  - Check Width & Height
	if($checkimg && $checkdim) {
		list($width, $height) = getimagesize($_FILES[$tag]["tmp_name"]);
		if($width > $w || $height > $h) {
			die("This image does not meet the image dimension limits.");
		}
	}
	
	// Check if File Exists
	if(file_exists($target_file)) {
		$error = 'File already exists';
		return;
	}
	
	// Check File Size
	if($_FILES[$tag]["size"] > $size) {
		$error = 'Your file is larger than '.($size/1024).' KBs';
		return;
	}
	
	// Allow Certain File Extentions
	@$imageFileType = array_pop(explode(".", strtolower($_FILES[$tag]["name"])));
	$target_file =  $directory.'/'.$filename.'.'.$imageFileType;

	if(!in_array($imageFileType, $format)) {
		$error = 'The allowed formats are '.implode(', ', $format);
		return;
	}

	// Allow Certain File Mime Types
	if (!in_array($_FILES[$tag]["type"], $type)) {
		$error = 'The allowed mime formats are '.implode(', ', $type);
		return;
	}

	// If Validation Passed Try Upload
	if (move_uploaded_file($_FILES[$tag]["tmp_name"], $target_file)) {
		return true;
	}

	$error = 'There was an error uploading your file';
	return false;
}