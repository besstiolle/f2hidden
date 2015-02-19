<?php

if (!function_exists("cmsms")) exit;

//Paranoïd mode : only GET method
ApiRequest::allowMethods(ApiRequest::$GET);

$response = new ApiResponse($params);

//Check the token
$response = OAuth::validToken($response);

$params = $response->getParams();

//Select by example
$example = new OrmExample();
$example->addCriteria('state', OrmTypeCriteria::$EQ, array(EnumProjectState::accepted));
$example->addCriteria('project_type', OrmTypeCriteria::$EQ, array(EnumProjectType::module));

if(!empty($params['filterAlpha']) ) {
	$example->addCriteria('unix_name', OrmTypeCriteria::$LIKE, array($params['filterAlpha'].'%'));
}


//Number of element to return. Min = 1, default = 10
$n = 10;
if(!empty($params['n']) && preg_match('#^[0-9]+$#', $params['n'])){
	$n = max(1, $params['n']);
}

// position of the element in Sql way
$pos = 0;
if(!empty($params['p']) && preg_match('#^[0-9]+$#', $params['p'])){
	$p = max(1, $params['p']);
	$pos = ($p - 1) *  $n;
}

$projects = OrmCore::findByExample(new Project, 
									$example, 
									new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
									new OrmLimit($pos, $n));

$projectsList = array();
foreach ($projects as $project) {
	$projectsList[] = $project->getValues();
}


$response->addContent('projects', $projectsList);

//Display result
echo $response;
exit;