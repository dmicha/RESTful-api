<?php /*
 * @ Author: DM
 * @ Create Time: 2022-09-02 15:15:10
 * @ Modified by: DM
 * @ Modified time: 2022-09-02 15:20:40
 * @ Description:
 */


$service_url = 'http://10.11.0.1/ndev/Damian/RESTful/location/';
$curl = curl_init($service_url);
$curl_post_data = array(
    "id" => 42,
    "miejsce_dostawy" => 'przyklad',
);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response = curl_exec($curl);
curl_close($curl);
