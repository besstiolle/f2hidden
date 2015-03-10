<?php

if (!function_exists("cmsms")) exit;

class projectService extends abstractService implements interfaceService {
	
	protected $serviceName = 'project';

	public function __construct($path, $params){
		//All methods allowed
		ApiRequest::allowMethods(ApiRequest::$ALL);

		$this->initResponse($path, $params);
	}

	function get(){

		//Select by example
		$example = new OrmExample();
		$example->addCriteria('id', OrmTypeCriteria::$EQ, array($this->params['sid']));

		//We don't need the sid anymore
		unset($this->params['sid']);

		$projects = OrmCore::findByExample(new Project, 
											$example, 
											new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
											new OrmLimit(0, 10));
		if(empty($projects)){
			$this->response->setCode(404); 
		}

		$projectsList = array();
		foreach ($projects as $project) {
			$projectsList[] = $project->getValues();
		}

		$this->response->addContent('projects', $projectsList);
		return $this->response;
	}

	function getAll(){
		//Select by example
		$example = new OrmExample();
		if(!empty($this->params['state']) ) {
			$example->addCriteria('state', OrmTypeCriteria::$EQ, array($this->params['state']));
		}

		if(!empty($this->params['project_type']) ) {
			$example->addCriteria('project_type', OrmTypeCriteria::$EQ, array($this->params['project_type']));
		}
		
		//$example->addCriteria('state', OrmTypeCriteria::$EQ, array(EnumProjectState::accepted));
		//$example->addCriteria('project_type', OrmTypeCriteria::$EQ, array(EnumProjectType::module));

		if(!empty($this->params['user_id']) ) {
			$exampleAssignment = new OrmExample();
			$exampleAssignment->addCriteria('user_id', OrmTypeCriteria::$EQ, array($this->params['user_id']));
			$assignements = OrmCore::findByExample(new Assignment, $exampleAssignment);
			$projectsIds = array();
			foreach ($assignements as $assignement) {
				$projectsIds[] = $assignement->get('project_id')->get('id');
			}
			$example->addCriteria('id', OrmTypeCriteria::$IN, $projectsIds);
		}

		
/*
		if(!empty($this->params['filterAlpha']) ) {
			$example->addCriteria('unix_name', OrmTypeCriteria::$LIKE, array($this->params['filterAlpha'].'%'));
		}*/


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


		$projects = OrmCore::findByExample(new Project, 
											$example, 
											new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
											new OrmLimit($pos, $n));

		$projectsList = array();
		foreach ($projects as $project) {
			$projectsList[] = $project->getValues();
		}


		$this->response->addContent('projects', $projectsList);

		return $this->response;
	}

	function delete(){

		OrmCore::deleteByIds(new Project, array($this->params['sid']));

		$this->response->addContent('info', 'entity deleted with success');

		return $this->response;
	}

	function create(){

		//Select by example
		$example = new OrmExample();
		$example->addCriteria('unix_name', OrmTypeCriteria::$EQ, array($this->params['unix_name']));

		$entities = OrmCore::findByExample(new Project, $example);

		if(!empty($entities)){
			$this->response->setCode(400); 
			$this->response->addContent('warn', 'entity with same unix_name found');
			return;
		}

		$entity = new Project();
		foreach ($this->params as $key => $value) {
			$entity->set($key, $value);
		}

		$entity->set('created_at',time());

		//Save the entity
		$entity = $entity->save();
		$projectsList = array($entity->getValues());

		$this->response->addContent('info', 'entity created with success');
		$this->response->addContent('projects', $projectsList);

		return $this->response;
	}

	function update(){

		$entities = OrmCore::findByIds(new Project, array($this->params['sid']));

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
		$projectsList = array($entity->getValues());

		$this->response->addContent('info', 'entity updated with success');
		$this->response->addContent('projects', $projectsList);

		return $this->response;
	}

}


?>