<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [

	'sid' => '#^[0-9]+$#',
//	'name' => '',
//	'url' => '',
//	'type' => '#^[0-9]$#',
//	'id_related' => '#^[0-9]+$#',
//	'created_at' => '',
	'files' => '', //array,
	'onTransfert' => '#^[0-1]$#',
];