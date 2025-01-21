<?php
/*
 * @ Author: DM
 * @ Create Time: 2022-09-02 09:39:58
 * @ Modified by: DM
 * @ Modified time: 2022-09-09 09:43:14
 * @ Description:
 */

// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

// echo $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// echo '</br>';

// $uri = explode('/', $uri);

// echo '<pre>';
// print_r($uri);
// echo '</pre>';
require_once("LocationRestHandler.php");
$method = $_SERVER['REQUEST_METHOD'];
// echo $method;

switch ($method) {

	case "GET":
		$dataRestHandler = new LocationRestHandler();
		if (isset($_GET["miejsce_dostawy"])) {
			$dataRestHandler->getLocation($_GET["miejsce_dostawy"]);
		} else {
			$dataRestHandler->getAllLocations();
		}
		break;
	case "POST":
		$mobileRestHandler = new LocationRestHandler();
		$mobileRestHandler->addLocation();
		break;

	case "DELETE":
		// to handle REST Url /location/delete/<row_id>
		$mobileRestHandler = new LocationRestHandler();
		if (count($_GET) > 0) {
			$result = $mobileRestHandler->deleteLocation($_GET["miejsce_dostawy"]);
		}
		break;

	case "PUT":
		// to handle REST Url /location/update/<row_id>
		$mobileRestHandler = new LocationRestHandler();
		$mobileRestHandler->updateLocation($_GET["miejsce_dostawy"]);
		break;
}
