<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [
	'sid' => '#^[0-9]+$#',
	'project_id' => '#^[0-9]+$#',
	'user_id' => '#^[0-9]+$#',
	'role' => '#^[0-9]+$#',
	'created_at' => '',
	'updated_at' => '',
];