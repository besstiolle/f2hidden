<?php

if (!function_exists("cmsms")) exit;

//TODO make constants and put everything into another file 
//DEFINE('SID', '...');
$fields = [
	'sid' => '#^[0-9]+$#',
	'commentable_id' => '#^[0-9]+$#',
	'user_id' => '#^[0-9]+$#',
	'commentable_type' => '',

	'title' => '',
	'comment' => '',
	'created_at' => '',
];
