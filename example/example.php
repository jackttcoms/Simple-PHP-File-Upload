<?php
/**
 * Simple Upload File Function Example Backend
 */

// Gets Extention from html5 form
$ext = strtolower(end(explode('.', $_FILES['photo']['name'])));
// Call upload function and set parameters (name of form, max size, extentions, mimes, a new name or unique name, directory, if only images can be uploaded?, if you want to check image width/height, set width, set height)
if(uploadFile('photo', 10000000, array('jpeg','jpg','png','gif'), array('image/jpeg','image/png','image/gif'), $data['photo_name'], 'uploads/users/', true, true, '200', '200', $error) === TRUE){
	//If above success, launch method for database and insert
	if( $this->user->updateUserPicture($data, $ext) ){
		// Using flash function shows a short message saying success
		flash('message', 'Your profile image has been changed!', 'alert alert-success mb-0');
		// Using an url function, redirect back to homepage
		redirect('');
	} else{
		// Something went wrong show language error text
		die(LANG['somethingwentwrong']);
	}
} else{
	// Show error if file upload fails
	echo $error;
}