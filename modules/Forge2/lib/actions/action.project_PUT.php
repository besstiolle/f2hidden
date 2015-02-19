<?php

if (!function_exists("cmsms")) exit;

//Select by example
$example = new OrmExample();
$example->addCriteria('unix_name', OrmTypeCriteria::$EQ, array($params['unix_name']));

$entities = OrmCore::findByExample(new Project, $example);

if(!empty($entities)){
	$response->addContent('warn', 'entity with same unix_name found');
	return;
}

$entity = new Project();
foreach ($params as $key => $value) {
	$entity->set($key, $value);
}

//Save the entity
$entity->save();

$response->addContent('info', 'entity created with success');