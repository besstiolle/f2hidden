<?php

if (!function_exists("cmsms")) exit;

class assignmentService extends abstractService implements interfaceService {
	
	protected $serviceName = 'assignment';

	protected $jsonBlock = 'assignments';

	protected $currentEntity;

	public function __construct($path, $params){
		//All methods allowed
		ApiRequest::allowMethods(ApiRequest::$ALL);

		$this->initResponse($path, $params);

		$this->currentEntity = new Assignment();
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
		if(!empty($this->params['role']) ) {
			$example->addCriteria('role', OrmTypeCriteria::$EQ, array($this->params['role']));
		}

		if(!empty($this->params['project_id']) ) {
			$example->addCriteria('project_id', OrmTypeCriteria::$EQ, array($this->params['project_id']));
		}
		
		if(!empty($this->params['user_id']) ) {
			$example->addCriteria('user_id', OrmTypeCriteria::$EQ, array($this->params['user_id']));
		}

		//Number of element to return. Min = 1, default = 10
		$n = 10;
		if(!empty($this->params['n']) && preg_match('#^[0-9]+$#', $this->params['n'])){
			$n = max(1, $this->params['n']);
			unset($this->params['n']);
		}

		// position of the element in Sql way
		$pos = 0;
		if(!empty($this->params['p']) && preg_match('#^[0-9]+$#', $this->params['p'])){
			$p = max(1, $this->params['p']);
			$pos = ($p - 1) *  $n;
			unset($this->params['p']);
		}


		$entities = OrmCore::findByExample($this->currentEntity, 
											$example, 
											null, //new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
											new OrmLimit($pos, $n));

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
		$example = new OrmExample();
		$example->addCriteria('project_id', OrmTypeCriteria::$EQ, array($this->params['project_id']));
		$example->addCriteria('user_id', OrmTypeCriteria::$EQ, array($this->params['user_id']));

		$entities = OrmCore::findByExample($this->currentEntity, $example);

		if(!empty($entities)){
			$this->response->setCode(400); 
			$this->response->addContent('warn', 'entity with same unix_name found');
			return;
		}

		$entity = $this->currentEntity;
		foreach ($this->params as $key => $value) {
			$entity->set($key, $value);
		}

		$entity->set('created_at',time());
		$entity->set('updated_at',time());

		//Save the entity
		$entity = $entity->save();
		$entityVals[] = OrmUtils::entityToAbsoluteArray($entity);

		$this->response->addContent('info', 'entity created with success');
		$this->response->addContent($this->jsonBlock, $entityVals);

		return $this->response;
	}

	function update(){

		$entities = OrmCore::findByIds($this->currentEntity, array($this->params['sid']));

		//We don't need the sid anymore
		unset($this->params['sid']);

		if(empty($entities)){
			$this->response->setCode(404); 
			$this->response->addContent('warn', 'entity not found');
			return;
		}

		$entity = $entities[0];
		foreach ($this->params as $key => $value) {
			$entity->set($key, $value);
		}

		$entity->set('updated_at',time());

		//Save the entity
		$entity = $entity->save();
		$entityVals[] = OrmUtils::entityToAbsoluteArray($entity);

		$this->response->addContent('info', 'entity updated with success');
		$this->response->addContent($this->jsonBlock, $entityVals);

		return $this->response;
	}

}


?>