
<?php

$result = [
	'status' => 200,
	'message' => 'data imported successfully'
];

// open file
if(isset($_FILES['file'])) {

	$file = $_FILES['file'];

	if($file['error']) {
		$result['status'] = 403;
		$result['message'] = 'error occured while importing file';
	}
	else {
		$fileName = './resources/imports/'.time().'.json';
		move_uploaded_file($file['tmp_name'], $fileName);

		// read file data
		$fileData = file_get_contents($fileName);

		print_r($fileData);
	}

	echo json_encode($result);
}

