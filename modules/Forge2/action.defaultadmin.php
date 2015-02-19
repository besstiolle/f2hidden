<?php

if (!function_exists("cmsms")) exit;


/*OrmCore::createTable(new Comment());*/

var_dump(OrmDb::getBufferQueries());

print_r(OrmDb::getBufferQueries());
?>