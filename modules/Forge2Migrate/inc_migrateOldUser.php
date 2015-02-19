<?php

if (!function_exists("cmsms")) exit;

for ($j = 0; $j < $nbIteration; $j++) {

	if($j == 0){
		$tmpStartId = $startId;
	} 

	$example = new OrmExample();
	$example->addCriteria('id', OrmTypeCriteria::$GT, array($tmpStartId));
	$users = OrmCore::findByExample(new OldUser(), $example, null, new OrmLimit(0, $limitIteration + 1));
	$isLast = count($users) < ($limitIteration + 1);

	$queryUser = "INSERT IGNORE INTO cms_module_feusers_users (id, username, password, createdate, expires, nonstd) VALUES ";
	$querySelectPropId = "SELECT MAX(id) FROM cms_module_feusers_properties";
	$queryDeleteProd = "DELETE FROM cms_module_feusers_properties WHERE userid in (%s)";
	$queryProp = "INSERT IGNORE INTO cms_module_feusers_properties (id, userid, title, data) VALUES ";

	$sid = OrmDb::getOne($querySelectPropId) + 1;

	$i = 0;
	$arrUser = array();
	$arrDelProp = array();
	$arrProp = array();
	foreach ($users as $user) {
		if($i++ > 0 ){
			$queryUser .= ' , ';
			$queryProp .= ' , ';
		}
		$queryUser .=  ' ( ? , ?, ?, ?, NULL, NULL) ';
		$arrUser[] = $user->get('id');
		$arrUser[] = $user->get('email');
		$arrUser[] = $user->get('crypted_password');
		$arrUser[] = date("y-m-d H:i:s",$user->get('created_at'));

		$arrDelProp[] = $user->get('id');

		$queryProp .=  ' ( ? , ?, "pseudo", ?) ,  ( ? , ?, "full_name", ?) ';
		$arrProp[] = $sid++;
		$arrProp[] = $user->get('id');
		$arrProp[] = $user->get('login');

		$arrProp[] = $sid++;
		$arrProp[] = $user->get('id');
		$arrProp[] = $user->get('full_name');

	}

	if($i > 0) {
		try{
			
			OrmDb::execute($queryUser, $arrUser);

			$queryDeleteProd = sprintf($queryDeleteProd, implode(', ',$arrDelProp));
			OrmDb::execute($queryDeleteProd);

			OrmDb::execute($queryProp, $arrProp);


			OrmDb::execute("update cms_module_feusers_users_seq set id = (select max(id) from cms_module_feusers_users)");
			OrmDb::execute("update cms_module_feusers_properties_seq set id = (select max(id) from cms_module_feusers_properties)");

		} catch(Exception $e){
			echo $e;
			return;
		}
	}

	if($isLast) {
		break;
	} else {
		$tmpStartId = $user->get('id');
	} 

}

if(!$isLast) {
	$nextEntity = $entityPosition;
	$nextId = $user->get('id');
}
