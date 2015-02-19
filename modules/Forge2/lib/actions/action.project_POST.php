<?php

if (!function_exists("cmsms")) exit;

$entities = OrmCore::findByIds(new Project, array($params['sid']));

//We don't need the sid anymore
unset($params['sid']);

if(empty($entities)){
	$response->addContent('warn', 'entity not found');
	return;
}

$entity = $entities[0];
foreach ($params as $key => $value) {
	$entity->set($key, $value);
}

//Save the entity
$entity->save();

$response->addContent('info', 'entity updated with success');