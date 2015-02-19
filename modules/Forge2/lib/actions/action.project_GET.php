<?php

if (!function_exists("cmsms")) exit;

//Select by example
$example = new OrmExample();
$example->addCriteria('id', OrmTypeCriteria::$EQ, array($params['sid']));

//We don't need the sid anymore
unset($params['sid']);

$projects = OrmCore::findByExample(new Project, 
									$example, 
									new OrmOrderBy(array('last_file_date' => OrmOrderBy::$DESC)), 
									new OrmLimit(0, 10));

$projectsList = array();
foreach ($projects as $project) {
	$projectsList[] = $project->getValues();
}

$response->addContent('projects', $projectsList);
