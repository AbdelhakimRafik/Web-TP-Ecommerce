<?php
/**
 * @file import_data.php
 * @author Abdelhakim RAFIK
 * 
 * @version 1.0.1
 * @date 2021-06
 * 
 * @copyright Copyright (c) 2021
 * 
 */

include_once('config/database.php');

// $response = array(
// 	"status" => 400,
// 	"message" => "Error while getting data"
// );

$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 16;

$pdo = new PDO("mysql:host=".$config['host'].";dbname=".$config['dbName'], $config['username'], $config['password']);

$stmCount = $pdo->query('SELECT count(*) as `total` FROM `products`');
$count = floor($stmCount->fetch()['total']/$limit);

$stm = $pdo->query('SELECT * FROM `products` limit '.$offset*$limit.','.$limit);

$products = $stm->fetchAll(PDO::FETCH_CLASS);

// $response['status'] = 200;
// $response['message'] = "Data retrieved successfully";
// $response['data'] = $stm->fetchAll(PDO::FETCH_CLASS);

// return response
// echo json_encode($response);