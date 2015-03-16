<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [

	'sid' => '#^[0-9]+$#',
	'historizable_id'	 => '#^[0-9]+$#',
	'historizable_type'	 => '#^[0-9]+$#',
	'created_at'	 => '',
];