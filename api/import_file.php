<?php
/**
 * @file import_file.php
 * @author Abdelhakim RAFIK
 * 
 * @version 1.0.1
 * @date 2021-06
 * 
 * @copyright Copyright (c) 2021
 * 
 */

$response = [
	'status' => 400,
	'message' => 'Bad Request'
];

// open file
if(isset($_FILES['file'])) {
	$file = $_FILES['file'];

	if($file['error'])
		$response['message'] = 'Error occurred while importing file';
	else {
		// check file extension
		$filename = $_FILES['file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if($ext == 'json') {

			$filename = time().'.json';
			move_uploaded_file($file['tmp_name'], '../resources/imports/'.$filename);

			// prepare response
			$response['status'] = 200;
			$response['message'] = 'file uploaded successfully';
			$response['file'] = $filename;
		}
		else {
			$response['status'] = 415;
			$response['message'] = 'Unsupported file type';
		}

	}
}

// return the response
echo json_encode($response);