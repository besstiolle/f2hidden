<?php

if (!function_exists("cmsms")) exit;

$fieldsRequired = [
	//'sid', //not in creation
	'name',
	'unix_name',
	'project_type',
				];


$fieldsOptional = [
	'description',
	'registration_reason',
	'project_category_1',
	'project_category_2',
//	'created_at',
//	'updated_at',
	'state',
	'approved_on',
	'approved_by',
	'reject_reason',
	'license_id',
	'changelog',
	'roadmap',
	'downloads',
	'next_planned_release',
	'repository_type',
	'show_join_request',
	'last_repository_date',
	'last_file_date',
	'github_repo',
	'freshness_date',
				];