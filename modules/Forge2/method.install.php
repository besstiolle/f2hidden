<?php

if (!function_exists("cmsms")) exit;

//Create all the tables automatically 
/*$entities = MyAutoload::getAllInstances($this->GetName());
foreach($entities as $anEntity) {
	OrmCore::createTable($anEntity);
}*/

$this->SetPreference('token_timeout', 30); 
$this->SetPreference('token_is_unique', FALSE); 

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );
?>
