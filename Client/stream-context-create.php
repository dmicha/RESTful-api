<?php /*
 * @ Author: DM
 * @ Create Time: 2022-09-02 15:25:20
 * @ Modified by: DM
 * @ Modified by: DM2022-09-06 11:25:01
 * @ Modified time: 2022-09-06 11:27:50
 */


$data = $_POST;
$url = 'http://10.11.0.1/ndev/Damian/RESTful/location/';
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => $_SERVER['REQUEST_METHOD'],
        'content' => json_encode($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
}
echo '<pre>';
print_r($result);
echo '</pre>';
if (count($_POST) > 0) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}


echo '<p><a href="GET_POST_Client.html">GET_POST_Client.HTML</a></p>';
