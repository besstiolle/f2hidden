<?php



echo '<p>$_SERVER["REQUEST_METHOD"] : </p>';

echo $_SERVER["REQUEST_METHOD"];

ApiRequest::allowMethods(ApiRequest::$GET, 
							ApiRequest::$PUT, 
							ApiRequest::$POST);

$response = new ApiResponse($params);

echo '<p>var_dump($response->getParams()) : </p>';
var_dump($response->getParams());

