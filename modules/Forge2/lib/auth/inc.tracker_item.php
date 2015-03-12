<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [
	'sid' => '#^[0-9]+$#',
	'project_id' => '#^[0-9]+$#',
	'user_id' => '#^[0-9]+$#',
	'role' => '#^[0-9]+$#',

	'sid' => '#^[0-9]+$#',
	'project_id' => '#^[0-9]+$#',
	'assigned_to_id' => '#^[0-9]+$#',
	'version_id' => '',
	'created_by_id' => '#^[0-9]+$#',
	'severity' => '#^[0-9]+$#',
	'state' => '#^[0-9]+$#',
	'resolution' => '#^[0-9]+$#',
	'type' => '#^[0-9]+$#',
	'cmsms_version_id' => '',
	'summary' => '',
	'description' => '',
	'created_at' => '',
	'updated_at' => '',
];