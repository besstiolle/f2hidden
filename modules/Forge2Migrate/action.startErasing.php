<?php

if (!function_exists("cmsms")) exit;


$entityNameMigrated = $this->_getEntityNameMigrated();

foreach ($entityNameMigrated as $name) {
	OrmCore::dropTable(new $name());
	OrmCore::createTable(new $name());
}

//User part
OrmDb::execute("DELETE FROM cms_module_feusers_properties WHERE 1");
OrmDb::execute("DELETE FROM cms_module_feusers_users WHERE 1");
OrmDb::execute("UPDATE cms_module_feusers_properties_seq SET id = 0");
OrmDb::execute("UPDATE cms_module_feusers_users_seq SET id = 0");

echo "the Re-Initialisation is done. Return to the <a href='".$this->CreateLink($id, 'defaultadmin', null, '', array(), '', true)."'>main page</a>.";


echo "<hr/>";
var_dump(OrmDb::getBufferQueries());
