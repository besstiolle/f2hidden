<?php

if (!function_exists("cmsms")) exit;

//Create all the tables automatically 
$entities = MyAutoload::getAllInstances($this->GetName());
foreach($entities as $anEntity) {
	OrmCore::createTable($anEntity);
}

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );
?>
