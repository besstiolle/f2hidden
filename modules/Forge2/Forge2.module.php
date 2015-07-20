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
$pathShared = $config['root_path'].'/modules/Forge2/lib/shared/';
require_once($pathShared.'class.Enum.php');
require_once($pathShared.'class.EnumProjectState.php');
require_once($pathShared.'class.EnumProjectRepository.php');
require_once($pathShared.'class.EnumProjectJoinRequestState.php');
require_once($pathShared.'class.EnumProjectType.php');
require_once($pathShared.'class.EnumAssignmentRole.php');
require_once($pathShared.'class.EnumTaggingType.php');
require_once($pathShared.'class.EnumHistoryType.php');
require_once($pathShared.'class.EnumCommentType.php');
require_once($pathShared.'class.EnumTrackerItemResolution.php');
require_once($pathShared.'class.EnumTrackerItemSeverity.php');
require_once($pathShared.'class.EnumTrackerItemState.php');
require_once($pathShared.'class.EnumTrackerItemType.php');
require_once($pathShared.'class.EnumForgeFile.php');

class Forge2 extends Orm
{   
	function GetName() {
		return 'Forge2';
	}

	function GetFriendlyName() {
		return $this->Lang('friendlyname');
	}

	function GetVersion() {
		return '0.0.2';
	}

	function GetDependencies() {
		return array('Orm'=>'0.3.3');
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
	}

	function CreateStaticRoutes() {

		$prefix = 'rest';
		$version = 'v1';
		$sid = '(?P<sid>[0-9]+)';

		//Get Token
		$route = $this->_generateRoute($prefix, $version, 'token');
		$this->_add_static($route, array('action'=>'token'));

		//Standard rest routes
		$restRoutes = ['project', 'assignment', 'tracker_item', 'comment',
					'history',
					'package',
					'release',
					'license'];
		foreach ($restRoutes as $restRoute) {
			
			//get/delete/update a $restRoute
			$route = $this->_generateRoute($prefix, $version, $restRoute,$sid);
			$this->_add_static($route, array('action'=>$restRoute));

			//getAll/create $restRoute(s)
			$route = $this->_generateRoute($prefix, $version, $restRoute);
			$this->_add_static($route, array('action'=>$restRoute, '_all'=>TRUE));
		}
		
		//Notification files rest routes
		$restRoutes = [
					'file_avatar' => 'files\/project\/'.$sid.'\/avatar',
					'file_show' => 'files\/project\/'.$sid.'\/show',
					'file_release' => 'files\/release\/'.$sid.'\/file'];
		foreach ($restRoutes as $action => $restRoute) {
			$route = $this->_generateRoute($prefix, $version, $restRoute);
			$this->_add_static($route, array('action'=>$action, '_all'=>TRUE));
		}

		//sandbox for quick test
		$route = $this->_generateRoute($prefix, $version, 'sandbox');
		$this->_add_static($route, array('action'=>'default'));
	}

	private function _generateRoute(){
   		return '/^'.implode('\/', func_get_args()).'$/';
    }

    private function _add_static($route, $params){
		cms_route_manager::add_static(new CmsRoute($route, $this->GetName(), array_merge($params, array( 'showtemplate' => 'false' ))));
    }

	function InitializeAdmin() {
	}

	function AllowSmartyCaching() {
		return true;
	}

	function LazyLoadFrontend() {
		return true;
	}

	function LazyLoadAdmin() {
		return true;
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
