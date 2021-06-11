<?php
/**
 * @file add_to_cart.php
 * @author Abdelhakim RAFIK
 * 
 * @version 1.0.1
 * @date 2021-06
 * 
 * @copyright Copyright (c) 2021
 * 
 */

include_once('../config/database.php');

session_start();

if(isset($_GET['action'])) {
	
	if(isset($_GET['sku'])){
		$sku = $_GET['sku'];

		// add to cart
		if($_GET['action'] == 'add'){

			$pdo = new PDO("mysql:host=".$config['host'].";dbname=".$config['dbName'], $config['username'], $config['password']);

			$stm = $pdo->query('SELECT * FROM `products` WHERE `sku` = '.$sku);
			$product = $stm->fetch();

			// create userCart if not exists
			if(!isset($_SESSION['userCart']))
				$_SESSION['userCart'] = array(
					"total" => 0,
					"count" => 0,
					"products" => array()
				);

			if(isset($_SESSION['userCart']['products'][$sku]))
				$_SESSION['userCart']['products'][$sku]['quantity']++;
			else {
				// add product to userCart
				$_SESSION['userCart']['products'][$sku] = array(
					"name" => $product['name'],
					"price" => $product['price'],
					"quantity" => 1,
					"image" => $product['image']
				);
			}

			// increment count
			$_SESSION['userCart']['count']++;
			$_SESSION['userCart']['total'] +=  round($product['price'], 2);

			echo json_encode(array(
				"status" => 200
			));
		}

		// remove element from cart
		else if($_GET['action'] == 'remove') {
			if(isset($_SESSION['userCart']['products'][$sku])) {
				$product = $_SESSION['userCart']['products'][$sku];
				$_SESSION['userCart']['count']--;
				$_SESSION['userCart']['total'] = round($_SESSION['userCart']['total'] - ($product['price'] * $product['quantity']), 2);
				unset($_SESSION['userCart']['products'][$sku]);

				echo json_encode(array(
					"status" => 200
				));
			}
		}

		// update quantity
		else if($_GET['action'] == 'quantityUp' && isset($_GET['quantity'])) {
			if(isset($_SESSION['userCart']['products'][$sku])) {
				$product = $_SESSION['userCart']['products'][$sku];
				// update quantity
				$diff = $_GET['quantity'] - $product['quantity'];
				$_SESSION['userCart']['products'][$sku]['quantity'] = $_GET['quantity'];
				$_SESSION['userCart']['total'] = round($_SESSION['userCart']['total'] + ($product['price'] * $diff), 2);
				
				$_SESSION['userCart']['count'] += $diff;

				echo json_encode(array(
					"status" => 200
				));
			}
		}
	}

	if($_GET['action'] == 'clear'){
		if(isset($_SESSION['userCart'])){
			unset($_SESSION['userCart']);
			session_destroy();
		}
		echo json_encode(array(
			"status" => 200
		));
	}
	else if($_GET['action'] == 'getList') {
		if(isset($_SESSION['userCart']))
			echo json_encode(array(
				"status" => 200,
				"cart" => $_SESSION['userCart']
			));
		else
			echo json_encode(array(
				"status" => 404
			));
	}
}
