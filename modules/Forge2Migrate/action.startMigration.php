<?php

if (!function_exists("cmsms")) exit;


$entityNameToMigrate = $this->_getEntityNameToMigrate();
					
$limitIteration = 500;
$nbIteration = 10;

$config = cmsms()->GetConfig();
$path = $config['root_path'];

//Just for initialisation
EnumAssignmentRole::Administrator;
EnumTaggingType::Unknown;
EnumHistoryType::Unknown;
EnumProjectState::hidden;
EnumProjectRepository::git;
EnumProjectType::module;
EnumCommentType::TrackerItem;
EnumProjectJoinRequestState::pending;
EnumTrackerItemResolution::None;
EnumTrackerItemSeverity::None;
EnumTrackerItemState::Open;
EnumTrackerItemType::Bug;

//By default : first entity of the list, else the name of entity passed by parameter.
$entityPosition = 0;
if(isset($params['entityPosition'])) {
	$entityPosition = $params['entityPosition'];
}
$entityName = $entityNameToMigrate[$entityPosition];

//By default : the first object, else the object with the id > passed by parameter
$startId = 0;
if(isset($params['startId'])) {
	$startId = $params['startId'];
}

// For information only, give the step
$step = 0;
if(isset($params['step'])) {
	$step = $params['step'];
}

$steps = 0;
if(isset($params['steps'])) {
	$steps = $params['steps'];
} else {
	include($path.'/modules/'.$this->GetName().'/inc_getSteps.php');
	$steps = getSteps($entityNameToMigrate, $limitIteration, $nbIteration);
}

$nextEntity = $entityPosition + 1;
$nextId = 0;

include($path.'/modules/'.$this->GetName().'/inc_migrate'.$entityName.'.php');

$smarty->assign('nextEntity',$nextEntity);
$smarty->assign('entityPosition',$entityPosition);
$smarty->assign('entityName',$entityName);
$smarty->assign('startId',$startId);
$smarty->assign('nextId',$nextId);
$smarty->assign('entityNameToMigrate',$entityNameToMigrate);
$smarty->assign('ratio',round(++$step * 100 / $steps, 2));

$smarty->assign('returnLink',$this->CreateLink($id, 'defaultadmin', null, '', array(), '', true));
$smarty->assign('nextLink',$this->CreateLink($id, 'startMigration', null, '', 
		array('entityPosition'=>$nextEntity.'','startId'=>$nextId.'', 'step' => $step.'','steps' => $steps.''), '', true));


echo $this->ProcessTemplate('startMigration.tpl'); 

$qs = OrmDb::getBufferQueries();
echo "<hr/>".var_dump($qs)."<pre>".print_r($qs[count($qs)-1], true)."</pre>";