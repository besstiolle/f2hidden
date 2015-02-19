<?php

if (!function_exists("cmsms")) exit;

$entity = new OldTracker_item();

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$elts = OrmCore::findByExample($entity, $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($elts) < ($limitIteration + 1);

	$query = "INSERT IGNORE INTO cms_module_forge2_tracker_items (id, project_id, assigned_to_id, version_id, created_by_id, "
			."severity, state, resolution, type, cmsms_version_id, "
			."summary, description, created_at, updated_at) VALUES ";

	$i = 0;
	$arr = array();
	foreach ($elts as $e) {
		if($i++ > 0 ){
			$query .= ' , ';
		}
		$query .=  ' ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ';
		$arr[] = $e->get('id');
		$arr[] = $e->get('project_id');
		$arr[] = $e->get('assigned_to_id');
		$arr[] = $e->get('version_id');
		$arr[] = $e->get('created_by_id');
		/*//Convert to Enum value
		$val = $e->get('severity_id');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumTrackerItemSeverity::'.$val );
		}*/
		$arr[] = $e->get('severity_id');
		//Convert to Enum value
		$val = $e->get('state');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumTrackerItemState::'.$val );
		}
		/*//Convert to Enum value
		$val = $e->get('resolution_id');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumTrackerItemResolution::'.$val );
		}*/
		$arr[] = $e->get('resolution_id');
		//Convert to Enum value
		$val = $e->get('type');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumTrackerItemType::'.$val );
		}
		$arr[] = $e->get('cmsms_version_id');
		$arr[] = $e->get('summary');
		$arr[] = $e->get('description');
		$arr[] = date("y-m-d H:i:s",$e->get('created_at'));
		$arr[] = date("y-m-d H:i:s",$e->get('updated_at'));

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
