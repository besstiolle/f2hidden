<?php

if (!function_exists("cmsms")) exit;

class file_releaseService extends abstractService implements interfaceService {
	
	protected $serviceName = 'file_release';

	protected $jsonBlock = 'releases';

	protected $currentEntity;

	private $type = EnumForgeFile::Release; 

	public function __construct($path, $params){
		//Create exclusivly
		ApiRequest::allowMethods(ApiRequest::$PUT);

		$this->initResponse($path, $params);

		$this->currentEntity = new ForgeFile();
	}

	function get(){

		//
	}

	function getAll(){

		//
	}

	function delete(){

		//
	}

	function create(){
		
		$entityVals = array();

		$files = $this->params['files'];
		foreach ($files as $name => $info) {
			//TODO tester regex url
			if(!array_key_exists('url', $info)){
				continue;
			}
			if(!array_key_exists('md5', $info)){
				continue;
			}

			$entity = new ForgeFile();
			$entity->set('name', $name);
			$entity->set('url', $info['url']);
			$entity->set('id_related', $this->params['sid']);
			$entity->set('type', $this->type);
			$entity->set('md5', $info['md5']);
			$entity->set('created_at',time());
			
			//Save the entity
			$entity = $entity->save();

			$entityVals[] = OrmUtils::entityToAbsoluteArray($entity);
		}


		$this->response->addContent('info', 'entity created with success');
		$this->response->addContent($this->jsonBlock, $entityVals);

		return $this->response;
	}

	function update(){

		//
	}

}


?>