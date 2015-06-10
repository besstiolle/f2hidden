<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [
	'sid' => '#^[0-9]+$#',
	'package_id' => '#^[0-9]+$#',
	'release_notes' => '',
	'changelog' => '',
	'released_by' => '#^[0-9]+$#',
	'is_active' => '#^[0-9]+$#',
	'created_at' => '',
	'updated_at' => '',
	'showOlder' => '',
];