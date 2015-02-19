<?php

if (!function_exists("cmsms")) exit;

OrmCore::deleteByIds(new Project, array($params['sid']));

$response->addContent('info', 'entity deleted with success');