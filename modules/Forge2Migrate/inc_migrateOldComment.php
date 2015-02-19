<?php

if (!function_exists("cmsms")) exit;

$entity = new OldComment();

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$elts = OrmCore::findByExample($entity, $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($elts) < ($limitIteration + 1);

	$query = "INSERT IGNORE INTO cms_module_forge2_comments (id, title, comment, created_at, commentable_id, commentable_type, user_id) VALUES ";

	$i = 0;
	$arr = array();
	foreach ($elts as $e) {
		if($i++ > 0 ){
			$query .= ' , ';
		}
		$query .=  ' ( ?, ?, ?, ?, ?, ?, ?) ';
		$arr[] = $e->get('id');
		$arr[] = $e->get('title');
		$arr[] = $e->get('comment');
		$arr[] = date("y-m-d H:i:s",$e->get('created_at'));
		$arr[] = $e->get('commentable_id');

		//Convert to Enum value
		$val = $e->get('commentable_type');
		if(empty($val)) {
			$arr[] = null;
		} else {
			$arr[] = Enum::FromString( 'EnumCommentType::'.$val );
		}
		
		$arr[] = $e->get('user_id');

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
