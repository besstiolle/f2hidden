<?php

if (!function_exists("cmsms")) exit;

$fieldsRequired = [

	//'sid',
	'project_id',
//	'assigned_to_id',
//	'version_id',
	'created_by_id',
//	'severity',
	'state',
//	'resolution',
	'type',
//	'cmsms_version_id',
	'summary',
//	'description',
//	'created_at',
//	'updated_at',
				];


$fieldsOptional = [
//	'project_id',
	'assigned_to_id',
	'version_id',
//	'created_by_id',
	'severity',
//	'state',
	'resolution',
//	'type',
	'cmsms_version_id',
//	'summary',
	'description',
//	'created_at',
//	'updated_at',
				];