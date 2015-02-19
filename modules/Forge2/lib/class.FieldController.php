<?php

if (!function_exists("cmsms")) exit;

class FieldController{

	private $path;
	private $name;
	private $groupe;
	private $method;
	private $params;
	private $warn = array();
	private $notice = array();

	public function __construct($path ,$name, $groupe, $method, $params){
		$this->path = $path;
		$this->name = $name;
		$this->groupe = $groupe;
		$this->method = $method;
		$this->params = $params;
	}

	public function validate(ApiResponse $response){

		// Operation, we remove the cmsms values
		unset($this->params['inline']);
		unset($this->params['action']);
		unset($this->params['returnid']);
		unset($this->params['module']);
		unset($this->params['showtemplate']);
		unset($this->params['token']);
		
		$patterns = $this->path.'lib/auth/inc.project.php';
		$definitions = $this->path.'lib/auth/admin/inc.project_'.$_SERVER['REQUEST_METHOD'].'.php';

		// the Parameter is not know 
		if(!file_exists($patterns)){
			$response->setCode(400); 
			$response->setMessage("Bad Request");
			echo $response;
			exit;
		}

		// the Parameter is know but is not linked for the method
		if(!file_exists($definitions) ){
			$response->setCode(405);
			$response->setMessage("Method not allowed");
			echo $response;
			exit;
		}
		$fields = array();
		$fieldsRequired = array();
		$fieldsOptional = array();

		require_once($patterns);
		require_once($definitions);

		//Test #1 : the fields required are presents
		foreach ($fieldsRequired as $fieldName) {

			//Field missing
			if(empty($this->params[$fieldName])){
				$this->warn[] = 'field '.$fieldName.' is required';
				continue;
			}

			//Control the pattern;
			$result = $this->controlPattern($this->params[$fieldName], $fields[$fieldName]);
			if(!$result) {
				$this->warn[] = 'field '.$fieldName.' is malformed. Pattern was : '.$fields[$fieldName];
				continue;
			}
		}

		//Test #1 : the fields optionnal, if there are present, must respect the pattern.
		foreach ($fieldsOptional as $fieldName) {
			if(!empty($this->params[$fieldName])){
				
				//Control the pattern;
				$result = $this->controlPattern($this->params[$fieldName], $fields[$fieldName]);
				if(!$result) {
					$this->warn[] = 'field '.$fieldName.' is malformed. Pattern was : '.$fields[$fieldName];
					continue;
				}
			}
		}

		//Test #3 : the extra fields are ignored
		foreach ($this->params as $fieldName => $values) {
			if(!in_array($fieldName, $fieldsOptional) && !in_array($fieldName, $fieldsRequired)){
				$this->notice[] = 'field '.$fieldName.' was unexpected and have been dropped from request';
				unset($this->params[$fieldName]);
				continue;
			}
		}

	}

	private function controlPattern($value, $pattern){
		//No control set
		if($pattern == ''){
			return true;
		}

		//No control explicitly defined
		if($pattern == '*'){
			return true;
		}

		return preg_match($pattern, $value);
	} 

	public function getParams(){
		return $this->params;
	}

	public function getWarn(){
		return $this->warn;
	}


	public function getNotice(){
		return $this->notice;
	}
}