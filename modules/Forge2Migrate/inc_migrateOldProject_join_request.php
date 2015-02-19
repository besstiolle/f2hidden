<?php

if (!function_exists("cmsms")) exit;

$entity = new OldProject_join_request();

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$elts = OrmCore::findByExample($entity, $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($elts) < ($limitIteration + 1);

	$query = "INSERT IGNORE INTO cms_module_forge2_project_join_requests (id, project_id, user_id, message, state, created_at, updated_at) VALUES ";

	$i = 0;
	$arr = array();
	foreach ($elts as $e) {
		if($i++ > 0 ){
			$query .= ' , ';
		}
		$query .=  ' ( ?, ?, ?, ?, ?, ?, ?) ';
		$arr[] = $e->get('id');
		$arr[] = $e->get('project_id');
		$arr[] = $e->get('user_id');
		$arr[] = $e->get('message');
		//Convert to Enum value
		$val = $e->get('state');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumProjectJoinRequestState::'.$val );
		}
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
