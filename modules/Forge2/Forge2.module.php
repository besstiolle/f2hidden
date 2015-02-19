<?php

/* Force the loading of Orm Framework BEFORE this module */
$config = cmsms()->GetConfig();
$Orm = $config['root_path'].'/modules/Orm/Orm.module.php';
if( !is_readable( $Orm ) ) {
  echo '<h1><font color="red">ERROR: The Orm Framework could not be found [<a href="https://github.com/besstiolle/orm-ms/wiki">help</a>].</font></h1>';
  return;
}
require_once($Orm);

//$config = cmsms()->GetConfig();
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.Enum.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumProjectState.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumProjectRepository.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumProjectJoinRequestState.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumProjectType.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumAssignmentRole.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumTaggingType.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumHistoryType.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumCommentType.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumTrackerItemResolution.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumTrackerItemSeverity.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumTrackerItemState.php');
require_once($config['root_path'].'/modules/Forge2/lib/Enum/class.EnumTrackerItemType.php');

class Forge2 extends Orm
{   
	function GetName() {
		return 'Forge2';
	}

	function GetFriendlyName() {
		return $this->Lang('friendlyname');
	}

	function GetVersion() {
		return '0.0.1';
	}

	function GetDependencies() {
		return array('Orm'=>'0.3.1');
	}

	function GetHelp() {
		return $this->Lang('help');
	}

	function GetAuthor() {
		return 'Kevin Danezis (aka Bess)';
	}

	function GetAuthorEmail() {
		return 'contact at furie point be';
	}

	function GetChangeLog() {
		return $this->Lang('changelog');
	}

	function GetAdminDescription() {
		return $this->Lang('moddescription');
	}

	function MinimumCMSVersion() {
		return "1.11.0";
	}

	function IsPluginModule() {
		return true;
	}

	function HasAdmin() {
		return true;
	}

	function GetAdminSection() {
		return 'extensions';
	}

	function VisibleToAdminUser() {
		return true;
	}

	function InitializeFrontend()
	{
		$this->RegisterModulePlugin(true, false);
	/*	$this->RestrictUnknownParams();
		$this->SetParameterType('projectName',CLEAN_STRING);
		$this->SetParameterType('packageId',CLEAN_INT);*/
	/*	$this->SetParameterType('max_height',CLEAN_INT);
		$this->SetParameterType('min_width',CLEAN_INT);
		$this->SetParameterType('min_height',CLEAN_INT);
		$this->SetParameterType('clean_name',CLEAN_STRING);
		$this->SetParameterType('dir_path',CLEAN_STRING);
		$this->SetParameterType('dir_url',CLEAN_STRING);
		$this->SetParameterType('template',CLEAN_STRING);*/
	}

	function CreateStaticRoutes() {

		$prefix = 'rest';
		$version = 'v1';
		$prefixProject = 'projects';
		$sid = '(?P<sid>[0-9]+)';
		$projectName = '(?P<projectName>[a-zA-Z0-9\-\_\:]+)';
		$packageId = '(?P<packageId>[0-9]+)';

		//Get Token
		$route = $this->_generateRoute($prefix, $version, 'token');
		$this->_add_static($route, array('action'=>'token'));

		//List of Projects
		$route = $this->_generateRoute($prefix, $version, 'projects');
		$this->_add_static($route, array('action'=>'projects'));

		//Page of project
		/*$route = $this->_generateRoute($prefix, $version, 'projects',$sid, $projectName);
		$this->_add_static($route, array('action'=>'projects'));*/
		
		//Page of project
		$route = $this->_generateRoute($prefix, $version, 'projects',$sid, 'a');
		$this->_add_static($route, array('action'=>'project'));
		
		//Page of project+ADD
		$route = $this->_generateRoute($prefix, $version, 'projects', 'add');
		$this->_add_static($route, array('action'=>'project'));

		//sandbox for quick test
		$route = $this->_generateRoute($prefix, $version, 'sandbox');
		$this->_add_static($route, array('action'=>'default'));

		/*$route = new CmsRoute('/rest\/v1\/projects\/(?P<sid>[a-z0-9]+)$/',$this->GetName(),array('action'=>'default'));
		cms_route_manager::add_static($route);*/
	}

	private function _generateRoute(){
   		return '/'.implode('\/', func_get_args()).'$/';
    }

    private function _add_static($route, $params){
		cms_route_manager::add_static(new CmsRoute($route, $this->GetName(), array_merge($params, array( 'showtemplate' => 'false' ))));
    }

	function InitializeAdmin() {
	}

	function AllowSmartyCaching() {
		return false;
	}

	function LazyLoadFrontend() {
		return false;
	}

	function LazyLoadAdmin() {
		return false;
	}

	function InstallPostMessage() {
		return $this->Lang('postinstall');
	}

	function UninstallPostMessage() {
		return $this->Lang('postuninstall');
	}

	function UninstallPreMessage() {
		return $this->Lang('really_uninstall');
	}

	function DisplayErrorPage($msg) {
		echo "<h3>".$msg."</h3>";
	}  

	function getPath(){
		$config = cmsms()->GetConfig();
		return $config['root_path'].'/modules/'.$this->GetName().'/';
	}
} 
?>
