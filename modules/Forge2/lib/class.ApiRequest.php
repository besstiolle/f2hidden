<?php


class ApiRequest {

	private $params = null;

	public static $GET = 'GET';
	public static $POST = 'POST';
	public static $PUT = 'PUT';
	public static $DELETE = 'DELETE';
	public static $ALL = 'ALL';

	private static $allowedMethod = array();

	public function __construct($paramsCmsms){
		
		//Check the method allowed. We work in paranoïd mode. 
		if( !in_array(ApiRequest::$ALL, ApiRequest::$allowedMethod)
				&& !in_array($_SERVER['REQUEST_METHOD'], ApiRequest::$allowedMethod) ){
			throw new Exception("Error HTTP method ".$_SERVER['REQUEST_METHOD']." not supported ", 1);
		}

		$parametersGET = ApiRequest::sanitizeParameters($_GET);
		//$parametersPOST = ApiRequest::sanitizeParameters($_POST);
		if(ApiRequest::isPOST()){
			$parametersPOST = $_POST;
		}
		
		if(ApiRequest::isPUT()){
			parse_str(file_get_contents("php://input"),$parametersPUT);
		}

		if(ApiRequest::isGET() || ApiRequest::isDELETE()) {
			$this->params = array_merge($paramsCmsms, $parametersGET);
		} else if(ApiRequest::isPOST()) { 
			$this->params = array_merge($paramsCmsms, $parametersGET, $parametersPOST);
		} else if(ApiRequest::isPUT()) { 
			$this->params = array_merge($paramsCmsms, $parametersGET, $parametersPUT);
		} else {
			throw new Exception("Error HTTP method ".$_SERVER['REQUEST_METHOD']." not supported ", 1);
		}
	}


	public static function sanitizeParameters($params){
		$sanitized = array();

		$pattern = '#^[a-zA-Z0-9]+$#';
		if(isset($params['user']) && preg_match($pattern, $params['user'])){
			$sanitized['user'] = $params['user'];
		}

		$pattern = '#^[a-zA-Z0-9]+$#';
		if(isset($params['pass']) && preg_match($pattern, $params['pass'])){
			$sanitized['pass'] = $params['pass'];
		}

		$pattern = '#^[a-zA-Z0-9]+$#';
		if(isset($params['token']) && preg_match($pattern, $params['token'])){
			$sanitized['token'] = $params['token'];
		}

		$pattern = '#^[a-zA-Z0-9]$#';
		if(isset($params['filterAlpha']) && preg_match($pattern, $params['filterAlpha'])){
			$sanitized['filterAlpha'] = $params['filterAlpha'];
		}

		$pattern = '#^[0-9]+$#';
		if(isset($params['p']) && preg_match($pattern, $params['p'])){
			$sanitized['p'] = $params['p'];
		}

		$pattern = '#^[0-9]+$#';
		if(isset($params['n']) && preg_match($pattern, $params['n'])){
			$sanitized['n'] = $params['n'];
		}

	/*	$pattern = '#^[0-9]+$#';
		if(isset($params['projectId']) && preg_match($pattern, $params['projectId'])){
			$sanitized['projectId'] = $params['projectId'];
		}

		$pattern = '#^[a-zA-Z0-9]+$#';
		if(isset($params['projectUnixName']) && preg_match($pattern, $params['projectUnixName'])){
			$sanitized['projectUnixName'] = $params['projectUnixName'];
		}  */

		return $sanitized;
	}

	public static function allowMethods(){
		ApiRequest::$allowedMethod = func_get_args();
	}


	public static function isGET(){
		return $_SERVER['REQUEST_METHOD'] === ApiRequest::$GET; 
	}

	public static function isPOST(){
		return $_SERVER['REQUEST_METHOD'] === ApiRequest::$POST;
	}

	public static function isDELETE(){
		return $_SERVER['REQUEST_METHOD'] === ApiRequest::$DELETE;
	}

	public static function isPUT(){
		return $_SERVER['REQUEST_METHOD'] === ApiRequest::$PUT;
	}

	public function getParams(){
		return $this->params;
	}
}