<?php

if (!function_exists("cmsms")) exit;

abstract class abstractService implements interfaceService {

	protected $params;
	protected $response;
	protected $path;
	protected $serviceName;

	public function __construct($path, $params){
		//Paranoïd mode : No methods allowed by default
		ApiRequest::allowMethods();

		$this->initResponse($path, $params);
	}

	protected function initResponse($path, $params){

		$response = new ApiResponse($params);

		//Check the token
		$this->response = OAuth::validToken($response);

		$this->params = $this->response->getParams();
		$this->path = $path;
	}

	function initFieldController($action){
		$groupe = OAuth::getUserGroup($this->response);
		//Include fieldController
		$fc = new FieldController($this->path, $this->serviceName, $groupe, $action, $this->params);
		$fc->validate($this->response);
		$this->params = $fc->getParams();

		if($fc->getWarn()){
			$this->response->addContent('warning', $fc->getWarn());
			
			//We stop if we have warning.
			$this->response->setCode(400); 
			$this->response->setMessage("Bad Request");
			echo $this->response;
			exit;
		}

		if($fc->getNotice()){
			$this->response->addContent('notice', $fc->getNotice());
		}
	}

	public function getResponse(){
		return $this->response;
	}

	public final function getWrapper(){
		$this->initFieldController('get');
		$this->get();
	}

	public function getAllWrapper(){
		$this->initFieldController('getAll');
		$this->getAll();
	}

	public function deleteWrapper(){
		$this->initFieldController('delete');
		$this->delete();
	}

	public function createWrapper(){
		$this->initFieldController('create');
		$this->create();
	}

	public function updateWrapper(){
		$this->initFieldController('update');
		$this->update();
	}

	abstract protected function get();
	abstract protected function getAll();
	abstract protected function delete();
	abstract protected function create();
	abstract protected function update();
}