
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

/**
 * Create placeholders for query values
 */
function placeholders($text, $count=0, $separator=',') {
	$resutl = array();
	if($count > 0) {
		for($i=0; $i<$count; ++$i)
			$result[] = $text;
	}
	return implode($separator, $result);
}


$fileData = file_get_contents('../resources/imports/data.json');

$fileData = json_decode($fileData);

// $pdo = new PDO("mysql:host=$config['host'];dbname=$config['dbName']", $config['username'], $config['password']);

$pdo = new PDO("mysql:host=localhost;dbname=boutique", 'root', '');

// query placeholders
$productMarks = array();
$categoryMarks = array();
$productCategoriesMarks = array();

// values containers
$productValues = array();
$categoryValues = array();
$productCategoriesValues = array();

$categoriesCount = 0;
$productsCount = 0;


foreach($fileData as $product) {

	// prepare products values
	$productMarks[] = '(' . placeholders('?', 11) . ')';
	$productValues[] = $product->sku;
	$productValues[] = $product->name;
	$productValues[] = $product->type;
	$productValues[] = $product->price;
	$productValues[] = $product->upc;
	$productValues[] = isset($product->shipping) ? $product->shipping : null;
	$productValues[] = isset($product->description) ? $product->description : null;
	$productValues[] = isset($product->manufacturer) ? $product->manufacturer : null;
	$productValues[] = isset($product->model) ? $product->model : null;
	$productValues[] = isset($product->url) ? $product->url : null;
	$productValues[] = isset($product->image) ? $product->image : null;

	++$productsCount;

	// prepare categories values
	foreach($product->category as $category) {
		if(!in_array($category->id, $categoryValues)) {
			$categoryMarks[] = '(' . placeholders('?', 2) . ')';
			$categoryValues[] = $category->id;
			$categoryValues[] = $category->name;
			++$categoriesCount;
		}

		// prepare product-categories relation values
		$productCategoriesMarks[] = '(' . placeholders('?', 2) . ')';
		$productCategoriesValues[] = $product->sku;
		$productCategoriesValues[] = $category->id;
	}
}

// queries
$productQuery = 'INSERT INTO `products` (`sku`, `name`, `type`, `price`, `upc`, `shipping`, `description`, `manufacturer`, `model`, `url`, `image`) VALUES '.implode(',', $productMarks);
$categoryQuery = 'INSERT INTO `categories` (`id`, `name`) VALUES ' . implode(',', $categoryMarks);
$productCategoriesQuery = 'INSERT INTO `product-categories` (`product`, `category`) VALUES ' . implode(',', $productCategoriesMarks);

// prepare pdo
$stm = $pdo->prepare($categoryQuery);
$stm->execute($categoryValues);

$stm = $pdo->prepare($productQuery);
$stm->execute($productValues);

$stm = $pdo->prepare($productCategoriesQuery);
$stm->execute($productCategoriesValues);

