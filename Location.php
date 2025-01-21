<?php
/*
 * @ Author: DM
 * @ Modified by: DM
 * @ Modified by: DM
 * @ Modified time: 2022-09-09 09:56:33
 * @ Description:
 */

// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

class Location
{
	public $jsonFile = './location.json';
	public function __construct()
	{
		$_SERVER['HTTP_ACCEPT'] = 'application/json';
		if (!file_exists($this->jsonFile) or file_get_contents($this->jsonFile) === 'null') {

			$response = $this->response('false', 'null', 'Plik nie istnieje');
			echo '<pre>';
			print_r($response);
			echo '</pre>';
			die();
		}
	}

	public function getAllLocation()
	{
		$allLocation = json_decode(file_get_contents($this->jsonFile), true);
		return $this->response(true, $allLocation, 'Dane wyslane!');
	}

	public function getLocation($miejsce_dostawy)
	{
		$json = json_decode(file_get_contents($this->jsonFile), true);
		$response = $this->response(false, null, 'Nie ma takiego obiektu!');
		foreach ($json as $key => $location) {
			if ($location['miejsce_dostawy'] === $miejsce_dostawy) {
				$response = $this->response(true, $location, 'Dane wyslane!');
				break;
			}
		}
		return $response;
	}

	public function addLocation($miejsce_dostawy = '')
	{
		$dataPost = (count($_POST) > 0) ? $_POST : json_decode(file_get_contents('php://input'), true);
		$jsonBase = json_decode(file_get_contents($this->jsonFile), true);
		if ($dataPost == null) {
			return $this->response(false, null, 'brak danych do wstawienia!');
		} else {
			$jsonPost = $dataPost;
		}
		foreach ($jsonBase as $keyJsonBase => $valueJsonBase) {
			foreach ($valueJsonBase as $keyOfKeyJsonBase => $valueOFValueJsonBase) {
				$jsonPostMiejsce_dostawy = (isset($jsonPost['miejsce_dostawy'])) ? $jsonPost['miejsce_dostawy'] : $miejsce_dostawy;
				if ($jsonPostMiejsce_dostawy == $valueJsonBase['miejsce_dostawy']) {
					return $this->response(false, $jsonPost, "Podane miejsce_dostawy juz jest w bazie.");
				}
			}
		}
		$defaultValue = array(
			'id' => '', 'miejsce_dostawy' => '', 'kod_dostawcy' => '', 'nr_psd' => '', 'stanowisko' => '', 'wyswietlacz' => '', 'rejon' => '', 'polka' => '', 'opis' => '', 'lokalizacja' => '', 'marka' => '', 'cecha_3' => '', 'kod_pocztowy' => '', 'miasto' => '', 'ulica' => '', 'numer' => '', 'status' => '', 'id_user' => '', 'dtm' => '', 'dostawa_w_sobote' => '', 'id_wlasciciel' => '', 'is_book' => '', 'is_cmp' => '', 'is_prasa' => '', 'nr_trasy' => '', 'synchro' => ''
		);
		if ($this->reWriteFile($jsonPost, $miejsce_dostawy, $defaultValue) == true) {
			return $this->response(true, $jsonPost, 'Obiekt dodano!');
		} else {
			return $this->response(false, $jsonPost, 'Obiektu nie udalo sie dopisac do pliku');
		}
	}

	public function deleteLocation($miejsce_dostawy)
	{
		$json = json_decode(file_get_contents($this->jsonFile), true);
		$response = $this->response(false, $miejsce_dostawy, "Nie mozna usunac! Nie ma takiego obiektu!");

		foreach ($json as $key => $location) {
			if ($location['miejsce_dostawy'] === $miejsce_dostawy) {
				unset($json[$key]);
				$response = $this->response(true, $miejsce_dostawy, "Obiekt usuniety!");
				break;
			}
		}
		file_put_contents($this->jsonFile, json_encode(array_values($json), JSON_PRETTY_PRINT));
		return $response;
	}

	public function updateLocation($editMiejsce_dostawy)
	{
		$dataPost = (count($_POST) > 0) ? $_POST : json_decode(file_get_contents('php://input'), true);
		$response = $this->response(false, $dataPost, "Nie mozna zaakualizowac! Nie ma takiego obiektu!");
		$jsonBase = json_decode(file_get_contents($this->jsonFile), true);
		if ($dataPost == null) {
			$response = $this->response(false, $dataPost, 'Brak danych do wstawienia!');
		} else {
			$jsonPost = $dataPost;
		}
		foreach ($jsonBase as $keyJsonBase => $valueJsonBase) {
			foreach ($valueJsonBase as $keyOfKeyJsonBase => $valueOFValueJsonBase) {
				$jsonPost['miejsce_dostawy'] = $editMiejsce_dostawy;
				if ($jsonPost['miejsce_dostawy'] === $valueJsonBase['miejsce_dostawy']) {
					$defaultValue = array(
						'id' => $valueJsonBase['id'], 'miejsce_dostawy' => $valueJsonBase['miejsce_dostawy'], 'kod_dostawcy' => $valueJsonBase['kod_dostawcy'], 'nr_psd' => $valueJsonBase['nr_psd'], 'stanowisko' => $valueJsonBase['stanowisko'], 'wyswietlacz' => $valueJsonBase['wyswietlacz'], 'rejon' => $valueJsonBase['rejon'], 'polka' => $valueJsonBase['polka'], 'opis' => $valueJsonBase['opis'], 'lokalizacja' => $valueJsonBase['lokalizacja'], 'marka' => $valueJsonBase['marka'], 'cecha_3' => $valueJsonBase['cecha_3'], 'kod_pocztowy' => $valueJsonBase['kod_pocztowy'], 'miasto' => $valueJsonBase['miasto'], 'ulica' => $valueJsonBase['ulica'], 'numer' => $valueJsonBase['numer'], 'status' => $valueJsonBase['status'], 'id_user' => $valueJsonBase['id_user'], 'dtm' => $valueJsonBase['dtm'], 'dostawa_w_sobote' => $valueJsonBase['dostawa_w_sobote'], 'id_wlasciciel' => $valueJsonBase['id_wlasciciel'], 'is_book' => $valueJsonBase['is_book'], 'is_cmp' => $valueJsonBase['is_cmp'], 'is_prasa' => $valueJsonBase['is_prasa'], 'nr_trasy' => $valueJsonBase['nr_trasy'], 'synchro' => $valueJsonBase['synchro']
					);
					$this->reWriteFile($jsonPost, $editMiejsce_dostawy, $defaultValue);
					$this->deleteLocation($editMiejsce_dostawy);
					$response = $this->response(true, $jsonPost, 'Obiekt zaakualizowano!');
					break;
				}
			}
		}
		return $response;
	}

	private function reWriteFile($jsonPost, $editMiejsce_dostawy, $defaultValue)
	{
		foreach ($defaultValue as $key => &$value) {
			$data[$key] = (isset($jsonPost[$key])) ? $jsonPost[$key] : $value;
		}
		$data['miejsce_dostawy'] = ($data['miejsce_dostawy'] == '') ? $editMiejsce_dostawy : $data['miejsce_dostawy'];
		$json = json_encode($data, JSON_PRETTY_PRINT);
		$handle = fopen($this->jsonFile, "r+") or die("Unable to open file!");
		fseek($handle, -1, SEEK_END);
		fwrite($handle, ',');
		fwrite($handle, $json);
		fwrite($handle, ']');
		fclose($handle);
		return true;
	}

	private function response($isSuccess, $data, $info)
	{
		return array(
			'success' => $isSuccess,
			'data' => $data,
			'info' => $info
		);
	}
}
