<?php

include_once('../config/database.php');

// $result = [
// 	'status' => 200,
// 	'message' => 'data imported successfully'
// ];

// echo 1;
// // open file
// if(isset($_FILES['file'])) {
// 	echo 2;
// 	$file = $_FILES['file'];

// 	if($file['error']) {
// 		$result['status'] = 403;
// 		$result['message'] = 'error occured while importing file';
// 	}
// 	else {
		// echo 3;
		// $fileName = '../resources/imports/'.time().'.json';
		// move_uploaded_file($file['tmp_name'], $fileName);

		// read file data
		// $fileData = file_get_contents($fileName);
		// // echo 4;
		// print_r($fileData);
// 	}

// 	echo json_encode($result);
// }



$fileData = file_get_contents('../resources/imports/data.json');

$fileData = json_decode($fileData);


$pdo = new PDO("mysql:host=".$config['host'].";dbname=".$config['dbName'], $config['username'], $config['password']);

// $pdo = new PDO("mysql:host=localhost;dbname=boutique", 'root', '');

$stmCategories = $pdo->prepare('INSERT INTO `categories` VALUES(:id, :name)');

$stmProduct_Categories = $pdo->prepare('INSERT INTO `product-categories` (product, category) VALUES(:product, :category)');

$stmProduct = $pdo->prepare('INSERT INTO `products` VALUES(:sku, :name, :type, :price, :upc, :shipping, :description, :manufacturer, :model, :url, :image)');

$insetedCategories = array();

session_start();

// create a session to store the progress
$_SESSION['fileImportProgress'] = array(
	"status" => 1,
	"inserted" => 0,
	"ignored" => 0,
	"total" => count($fileData),
	"elements" => array()
);

$i = 0;

foreach($fileData as $product) {

	if(session_status() === PHP_SESSION_NONE)
		session_start();
	
	try {

		// fill product information
		$stmProduct->bindParam(':sku', $product->sku);
		$stmProduct->bindParam(':name', $product->name);
		$stmProduct->bindParam(':type', $product->type);
		$stmProduct->bindParam(':price', $product->price);
		$stmProduct->bindParam(':upc', $product->upc);
		$stmProduct->bindParam(':shipping', $product->shipping);
		$stmProduct->bindParam(':description', $product->description);
		$stmProduct->bindParam(':manufacturer', $product->manufacturer);
		$stmProduct->bindParam(':model', $product->model);
		$stmProduct->bindParam(':url', $product->url);
		$stmProduct->bindParam(':image', $product->image);

		$stmProduct->execute();

		// fill categories
		foreach($product->category as $category) {
			if(!in_array($category->id, $insetedCategories)) {
				$stmCategories->bindParam(':id', $category->id);
				$stmCategories->bindParam(':name', $category->name);
				array_push($insetedCategories, $category->id);
				$stmCategories->execute();
			}

			// make a relation between product and its categories
			$stmProduct_Categories->bindParam(':product', $product->sku);
			$stmProduct_Categories->bindParam(':category', $category->id);
			$stmProduct_Categories->execute();
		}
	
		$_SESSION['fileImportProgress']["elements"][] = array(
			"id" => $product->sku,
			"status" => 200,
			"message" => "Added successfully"
		);
		$_SESSION['fileImportProgress']["inserted"]++;
	}
	catch (Exception $e) {
		$_SESSION['fileImportProgress']["elements"][] = array(
			"id" => $product->sku,
			"status" => 500,
			"code" => $e->getCode(),
			"message" => $e->getMessage()
		);
		$_SESSION['fileImportProgress']["ignored"]++;
	}

	session_write_close();
	sleep(1);

	if(++$i == 40)
		break;
}

// clear the session
session_destroy();
