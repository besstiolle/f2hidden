<?php

if (!function_exists("cmsms")) exit;


function getSteps($entityNameToMigrate, $limitIteration, $nbIteration){
	$ratio = $limitIteration * $nbIteration;
	$steps = 0;
	foreach ($entityNameToMigrate as $entityName) {
		$cpt = OrmCore::countAll(new $entityName());
		$steps += ceil($cpt / $ratio);
	}
	return $steps;
}