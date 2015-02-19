<?php

if (!function_exists("cmsms")) exit;


$entity = new OldProject();

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$elts = OrmCore::findByExample($entity, $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($elts) < ($limitIteration + 1);

	$query = "INSERT IGNORE INTO cms_module_forge2_projects (id, name, unix_name, description, registration_reason, "
		." project_type, project_category_1, project_category_2, created_at, updated_at, "
		." state, approved_on, approved_by, reject_reason, license_id, "
		." changelog, roadmap, downloads, next_planned_release, repository_type, "
		." show_join_request, last_repository_date, last_file_date, github_repo, freshness_date) VALUES ";

	$i = 0;
	$arr = array();
	foreach ($elts as $e) {
		if($i++ > 0 ){
			$query .= ' , ';
		}

		$query .=  ' ( ?, ?, ?, ?, ?,   ?, null, null, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?) ';
		$arr[] = $e->get('id');
		$arr[] = $e->get('name');
		$arr[] = $e->get('unix_name');
		$arr[] = $e->get('description');
		$arr[] = $e->get('registration_reason');

		//Convert to Enum value
		$val = $e->get('project_type');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumProjectType::'.$val );
		}	

		$arr[] = date("y-m-d H:i:s",$e->get('created_at'));
		$arr[] = date("y-m-d H:i:s",$e->get('updated_at'));
		
		//Convert to Enum value
		$val = $e->get('state');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumProjectState::'.$e->get('state') );
		}

		$arr[] = date("y-m-d H:i:s",$e->get('approved_on'));
		$arr[] = $e->get('approved_by');
		$arr[] = $e->get('reject_reason');
		$arr[] = $e->get('license_id');
		$arr[] = $e->get('changelog');
		$arr[] = $e->get('roadmap');
		$arr[] = $e->get('downloads');
		$arr[] = date("y-m-d H:i:s",$e->get('next_planned_release'));
		
		//Convert to Enum value
		$val = $e->get('repository_type');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumProjectRepository::'.$val );
		}

		$arr[] = $e->get('show_join_request');
		$arr[] = date("y-m-d H:i:s",$e->get('last_repository_date'));
		$arr[] = date("y-m-d H:i:s",$e->get('last_file_date'));
		$arr[] = $e->get('github_repo');
		$arr[] = date("y-m-d H:i:s",$e->get('freshness_date'));

	}

	if($i > 0) {
		try{
			OrmDb::execute($query, $arr);
		} catch(Exception $e){
			echo $e;
			return;
		}
	}

	if($isLast) {
		break;
	} else {
		$tmpStartId = $e->get('id');
	} 

}

if(!$isLast) {
	$nextEntity = $entityPosition;
	$nextId = $e->get('id');
}
