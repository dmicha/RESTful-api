<?php
/*
 * @ Author: DM
 * @ Modified by: DM
 * @ Modified by: DM
 * @ Modified time: 2022-09-09 10:12:36
 * @ Description:
*/
// error_reporting(0);
// ini_set('display_errors', 'on');

// Todo 1. file_get_contents jeśli zwróci false to znaczy ze pojawił się jakiś error można pobrać ostatni error error_get_last i go sparsować
// Todo 2. dodatkowo przydało by się ustandaryzować odpowiedz z restfula array() do JSON
// Todo 3. execute można podzielić na buildReguest, sendRequest, getResponse jakoś tak


class Request
{
    private $url = 'http://10.11.0.1/ndev/Damian/RESTful/location/';
    /**
     * @param string $type  GET, POST, PUT or DELETE
     * @param mixed $id|null   [optional] id(miejsce_dostawy)  or null
     * @param array $data|null   [optional] data to post or edit
     * @return true 
     */
    public function execute($type, $id = null, $data = array())
    {
        $url = ($id === null) ? $this->url : $this->url . $id . "/";
        $options = $this->buildReguest($url, $type, $id, $data);
        $context = $this->sendRequest($options);
        $result = $this->getResponse($id, $url, $context);
        $this->errHandler($type, $result);
        $this->printData($result);
        return true;
    }
    private function buildReguest($url, $type, $id = null, $data = array())
    {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => $type,
                'content' => json_encode($data),
                'timeout' => 120,  //120 Seconds is 2 Minutes
            )
        );
        return $options;
    }
    private function sendRequest($options)
    {
        $context  = stream_context_create($options);
        return $context;
    }
    private function getResponse($id, $url, $context)
    {

        $result = file_get_contents($url, false, $context);

        return $result;
    }
    private function errHandler($type, $result)
    {
        if ($result == null) {
            if ($type == "GET") {
                echo '</br>';
                echo 'Nie znaleziono lokalizacji do wyświetlenia!';
            }
            if ($type == "DELETE") {
                echo '</br>';
                echo 'Nie znaleziono lokalizacji do usuniecia!';
            }
            if ($type == "POST") {
                echo '</br>';
                echo 'Nie znaleziono lokalizacji do wstawienia!';
            }
            if ($type == "PUT") {
                echo '</br>';
                echo 'Nie znaleziono lokalizacji do zaaktualizowania!';
            }
        }
        $lastErr = error_get_last();
        // if (is_array($lastErr)) {
        //     foreach ($lastErr as $key => $value) {
        //         echo '</br>';
        //         echo $key . '=>';
        //         echo $value;
        //     }
        // }
        $this->saveLogs($lastErr);
        $lastErr = null;
    }
    private function saveLogs($lastErr)
    {
        //! file put content 
        if (is_array($lastErr)) {
            $date = date('d.m.Y h:i:s a', time());
            $lastErr['time'] = $date;
            $jsonErr = json_encode($lastErr, JSON_PRETTY_PRINT);
            $handle = fopen('log.json', "r+") or die("Unable to open file!");
            fseek($handle, -1, SEEK_END);
            fwrite($handle, ',');
            fwrite($handle, $jsonErr);
            fwrite($handle, ']');
            fclose($handle);
        }
    }
    private function printData($result)
    {
        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
}
$api = new Request;
$api->execute("GET", "11102",);
$api->execute("POST", null, $data = array(
    "id" => "9",
    "miejsce_dostawy" => "99",
    "kod_dostawcy" => "HDS PRASA",
    "kod_oddzialu" => "99",
));
$api->execute("PUT", "11102", $data = array(
    "id" => "id zmienione",
    "kod_dostawcy" => "HDS test!",
));
$api->execute("DELETE", "11120",);

$api->execute("GET");
