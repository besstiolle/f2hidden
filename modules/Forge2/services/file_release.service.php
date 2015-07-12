<?php

if (!function_exists("cmsms")) exit;

class file_releaseService extends abstractService implements interfaceService {
	
	protected $serviceName = 'file_release';

	protected $jsonBlock = 'releases';

	protected $currentEntity;

	private $type = EnumForgeFile::Release; 

	public function __construct($path, $params){
		//Create exclusivly
		ApiRequest::allowMethods(ApiRequest::$PUT, ApiRequest::$GET);

		$this->initResponse($path, $params);

		$this->currentEntity = new ForgeFile();
	}

	function get(){

		//Select by example
		$example = new OrmExample();
		$example->addCriteria('id', OrmTypeCriteria::$EQ, array($this->params['sid']));

		//We don't need the sid anymore
		unset($this->params['sid']);

		$entities = OrmCore::findByExample($this->currentEntity, 
											$example, 
											null, 
											new OrmLimit(0, 10));

		if(empty($entities)){
			$this->response->setCode(404); 
		}

		$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);

		$this->response->addContent($this->jsonBlock, $entityVals);
		return $this->response;
	}

	function getAll(){

		//Select by example
		$example = new OrmExample();
		if(!empty($this->params['id_related']) ) {
			$example->addCriteria('id_related', OrmTypeCriteria::$EQ, array($this->params['id_related']));
		}
		$example->addCriteria('type',OrmTypeCriteria::$EQ, array($this->type));
		
		$entities = OrmCore::findByExample($this->currentEntity, $example);

		//counter
		$count = OrmCore::selectCountByExample($this->currentEntity, 
											$example);
		$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);

		$this->response->addContent($this->jsonBlock, $entityVals);
		$this->response->addContent('count', $count);

		return $this->response;
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