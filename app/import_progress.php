<?php

session_start();

if(isset($_SESSION['fileImportProgress'])) {

	// parse and show the session data
	echo json_encode($_SESSION['fileImportProgress']);
	// clear elements array
	$_SESSION['fileImportProgress']['elements'] = array();
}
else
	echo json_encode(array(
		"status" => 0
	));