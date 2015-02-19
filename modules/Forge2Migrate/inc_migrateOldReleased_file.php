<?php

if (!function_exists("cmsms")) exit;

$entity = new OldReleased_file();

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$elts = OrmCore::findByExample($entity, $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($elts) < ($limitIteration + 1);

	$query = "INSERT IGNORE INTO cms_module_forge2_released_files (id, release_id, filename, size, deleted_at, created_at, updated_at, downloads, content_type) VALUES ";

	$i = 0;
	$arr = array();
	foreach ($elts as $e) {
		if($i++ > 0 ){
			$query .= ' , ';
		}
		$query .=  ' ( ?, ?, ?, ?, ?, ?, ?, ?, ?) ';
		$arr[] = $e->get('id');
		$arr[] = $e->get('release_id');
		$arr[] = $e->get('filename');
		$arr[] = $e->get('size');
		$arr[] = date("y-m-d H:i:s",$e->get('deleted_at'));
		$arr[] = date("y-m-d H:i:s",$e->get('created_at'));
		$arr[] = date("y-m-d H:i:s",$e->get('updated_at'));
		$arr[] = $e->get('downloads');
		$arr[] = $e->get('content_type');

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
