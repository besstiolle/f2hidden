<?php
if (!function_exists("cmsms")) exit;

//Paranoïd mode : only GET method
ApiRequest::allowMethods(ApiRequest::$GET);

$response = new ApiResponse($params);

//Generate a new Token.
$response = OAuth::getNewToken($response);

//Display result
echo $response;
exit;
