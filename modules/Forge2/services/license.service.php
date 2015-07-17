<?php

if (!function_exists("cmsms")) exit;

class licenseService extends abstractService implements interfaceService {
	
	protected $serviceName = 'license';

	protected $jsonBlock = 'licenses';

	protected $currentEntity;

	public function __construct($path, $params){
		//All methods allowed
		ApiRequest::allowMethods(ApiRequest::$ALL);

		$this->initResponse($path, $params);

		$this->currentEntity = new License();
	}

	function get(){

		//Select by example
		$example = new OrmExample();
		$example->addCriteria('id', OrmTypeCriteria::$EQ, array($this->params['sid']));

		//We don't need the sid anymore
		unset($this->params['sid']);

		$entities = OrmCore::findByExample($this->currentEntity, 
											$example, 
											null, //new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
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

		$entities = OrmCore::findByExample($this->currentEntity, 
											$example);

		//counter
		$count = OrmCore::selectCountByExample($this->currentEntity, 
											$example);
		$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);


		$this->response->addContent($this->jsonBlock, $entityVals);
		$this->response->addContent('count', $count);

		return $this->response;
	}

	function delete(){

		OrmCore::deleteByIds($this->currentEntity, array($this->params['sid']));

		$this->response->addContent('info', 'entity deleted with success');

		return $this->response;
	}

	function create(){

		//Select by example
	/*	$example = new OrmExample();
		$example->addCriteria('project_id', OrmTypeCriteria::$EQ, array($this->params['project_id']));
		$example->addCriteria('user_id', OrmTypeCriteria::$EQ, array($this->params['user_id']));

		$entities = OrmCore::findByExample($this->currentEntity, $example);

		if(!empty($entities)){
			$this->response->setCode(400); 
			$this->response->addContent('warn', 'entity with same unix_name found');
			return;
		}*/

		$entity = $this->currentEntity;
		foreach ($this->params as $key => $value) {
			$entity->set($key, $value);
		}

		$entity->set('created_at',time());

		//Save the entity
		$entity = $entity->save();
		$entityVals[] = OrmUtils::entityToAbsoluteArray($entity);

		$this->response->addContent('info', 'entity created with success');
		$this->response->addContent($this->jsonBlock, $entityVals);

		return $this->response;
	}

	function update(){

		$this->response->setCode(405);
		$this->response->setMessage("Method not allowed");
		echo $this->response;
		exit;
	}

}


?>