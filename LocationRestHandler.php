<?php
/*
 * @ Author: DM
 * @ Modified by: DM
 * @ Modified time: 2022-09-09 08:57:01
 * @ Modified time: 2022-09-09 09:43:02
 * @ Description:
 */

// error_reporting(E_ALL);
// ini_set('display_errors', 'on');
require_once("SimpleRest.php");
require_once("Location.php");

class LocationRestHandler extends SimpleRest
{
	function getAllLocations()
	{
		$location = new Location();
		$rawData = $location->getAllLocation();
		if (empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'Nie znaleziono lokalizacji!');
		} else {
			$statusCode = 200;
		}
		$this->dataHandler($statusCode, $rawData);
	}
	public function getLocation($miejsce_dostawy)
	{
		$location = new Location();
		$rawData = $location->getLocation($miejsce_dostawy);
		if (empty($rawData)) {
			$statusCode = 404;
			$rawData = 'Nie znaleziono lokalizacji!';
		} else {
			$statusCode = 200;
		}
		$this->dataHandler($statusCode, $rawData);
	}
	public function addLocation()
	{
		$location = new Location();
		$rawData = $location->addLocation();
		if (empty($rawData)) {
			$statusCode = 404;
			// $rawData = ' brak danych ';
		} else {
			$statusCode = 201;
		}
		$this->dataHandler($statusCode, $rawData);
	}
	public function deleteLocation($miejsce_dostawy)
	{
		$location = new Location();
		$rawData = $location->deleteLocation($miejsce_dostawy);
		if (empty($rawData)) {
			$statusCode = 404;
			$rawData = ' brak danych';
		} else {
			$statusCode = 202;
		}
		$this->dataHandler($statusCode, $rawData);
	}
	public function updateLocation($miejsce_dostawy)
	{
		$location = new Location();
		$rawData = $location->updateLocation($miejsce_dostawy);
		if (empty($rawData)) {
			$statusCode = 404;
			$rawData = ' brak danych';
		} else {
			$statusCode = 202;
		}
		$this->dataHandler($statusCode, $rawData);
	}
	public function dataHandler($statusCode, $rawData)
	{
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this->setHttpHeaders($requestContentType, $statusCode);
		if (strpos($requestContentType, 'application/json') !== false) {
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if (strpos($requestContentType, 'text/html') !== false) {
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if (strpos($requestContentType, 'application/xml') !== false) {
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	public function encodeHtml($responseData)
	{
		$json = json_decode($responseData);
	}
	public function encodeJson($responseData)
	{
		$json = json_encode($responseData, JSON_PRETTY_PRINT);
		echo '<pre>';
		print_r($json);
		echo '</pre>';
	}
	public function encodeXml($responseData)
	{
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><location></location>');
		foreach ($responseData as $key => $value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}
