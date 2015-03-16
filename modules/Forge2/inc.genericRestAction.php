<?php

if (!function_exists("cmsms")) exit;
include('services/interface.service.php');
include('services/abstract.service.php');


//To make distinction between get / getall
$all = false;
if(!empty($params['_all'])){
	$all = true;
	unset($params['_all']);
}

switch ($params['action']){
	case 'project';
		include('services/project.service.php');
		$service = new projectService($this->getPath(), $params);
		break;
	case 'assignment';
		include('services/assignment.service.php');
		$service = new assignmentService($this->getPath(), $params);
		break;
	case 'tracker_item';
		include('services/tracker_item.service.php');
		$service = new tracker_itemService($this->getPath(), $params);
		break;

	case 'comment';
		include('services/comment.service.php');
		$service = new commentService($this->getPath(), $params);
		break;

	case 'history';
		include('services/history.service.php');
		$service = new historyService($this->getPath(), $params);
		break;

	default:
		echo "You may forgot to implement action ".$params['action']." into genericRestAction.";
		break;
}



switch ($_SERVER['REQUEST_METHOD']) {
	case ApiRequest::$GET;
		if($all){
			$service->getAllWrapper();
		} else {
			$service->getWrapper();
		}

		break;
	case ApiRequest::$DELETE;
		$service->deleteWrapper();
		break;
	case ApiRequest::$PUT;
		$service->createWrapper();
		break;
	case ApiRequest::$POST;
		$service->updateWrapper();
		break;
	default:
		# code...
		break;
}

echo $service->getResponse();
exit;