<?php

if (!function_exists("cmsms")) exit;


switch($oldversion){
	case '0.0.1':
		OrmCore::createTable(new ForgeFile());
	case '0.0.2':
}

// put mention into the admin log
$this->Audit( 0,  $this->Lang('friendlyname'),  $this->Lang('upgraded', $this->GetVersion()));


?>