<?php

if (!function_exists("cmsms")) exit;

class file_avatarService extends abstractService implements interfaceService {
	
	protected $serviceName = 'file_avatar';

	protected $jsonBlock = 'avatars';

	protected $currentEntity;

	private $type = EnumForgeFile::Avatar; 

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

			$entity = $this->currentEntity;
			$entity->set('name', $name);
			$entity->set('url', $info['url']);
			$entity->set('id_related', $this->params['sid']);
			$entity->set('type', $this->type);
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