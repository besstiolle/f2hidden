<?php

class ApiResponse {

	private $startdt;

	private $params = array();

	private $code; // Something like 200, 401, 403 ...
	private $message; // Something like "Ok" or "Forbidden"

	private $content = [];     // will contain the response
	private $contentToken = ''; // will contain the informations about the token
	
	public function __construct($paramsCmsms){
		$this->startdt = microtime(true);
		$request = null;
		try{
			$request = new ApiRequest($paramsCmsms);
		} catch (Exception $e){
			$this->code = 405;
			$this->message = "Method Not Allowed";
			echo $this;
			exit;
		}
		$this->params = $request->getParams();

	}

	public function getParams(){
		return $this->params;
	}

	public function setCode($code){
		$this->code = $code;
	}
	public function getCode(){
		return $this->code;
	}

	public function setMessage($message){
		$this->message = $message;
	}
	public function getMessage(){
		return $this->message;
	}

	public function addContent($key, $value){
		$this->content[$key] = $value;
	}
	public function getContent(){
		return $this->content;
	}

	public function setContentToken($contentToken){
		$this->contentToken = $contentToken;
	}
	public function getContentToken(){
		return $this->contentToken;
	}

	public function __toString(){

		header($_SERVER["SERVER_PROTOCOL"]." ".$this->code." ".$this->message); 
		http_response_code($this->code);
		header('Content-Type: application/json');

		$json = array(
					'request' => $this->params, //for debug only
					'server' => array(
								'microtime' => microtime(true) - $this->startdt,
								'code' => $this->code,
								'message' => $this->message,
								'token' => $this->contentToken,
							),
					'data' => $this->content,
				);

		return json_encode($json);
	}

}