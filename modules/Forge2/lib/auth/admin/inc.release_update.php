<?php

if (!function_exists("cmsms")) exit;

$fieldsRequired = [
	'sid',
	'package_id',
	'name',
				];


$fieldsOptional = [

	'release_notes',
	'changelog',
	'released_by',
	'is_active',
//	'created_at',
//	'updated_at',
				];