<?php

if (!function_exists("cmsms")) exit;

if(!empty($params['error'])) {
	$smarty->assign('error', $params['error']);
}

$entityNameToMigrate = $this->_getEntityNameToMigrate();
$entityNameMigrated = $this->_getEntityNameMigrated();


$counters = array();
foreach ($entityNameToMigrate as $name) {
	$old =  OrmCore::countAll(new $name());
	$new = 'N/A';
	$counters[$name] = array( 'old' => OrmCore::countAll(new $name()), 
							  'new' => $new
							);
}

foreach ($entityNameMigrated as $name) {
	$new = 'N/A';
	try {
		$new = OrmCore::countAll(new $name());
	} catch (Exception $e) {
		//Nothing
	}
	$counters['Old'.$name]['new'] = $new;
}
//The UserPart
$countUser = "SELECT count(*) FROM cms_module_feusers_users";
$nbUsers = OrmDb::getOne($countUser);
$counters['OldUser']['new'] = $nbUsers;

$smarty->assign('startMigration', $this->CreateLink($id, 'startMigration', null, '', array(), '', true));
$smarty->assign('startReinit', $this->CreateLink($id, 'startErasing', null, '', array(), '', true));
$smarty->assign('counters', $counters);
echo $this->ProcessTemplate('defaultadmin.tpl'); 

echo "<hr/>";
var_dump(OrmDb::getBufferQueries());
