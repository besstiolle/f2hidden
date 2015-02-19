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

class Forge2Migrate extends Orm
{   
	function _getEntityNameToMigrate(){
		return array('OldLicense','OldProject','OldPackage','OldRelease','OldReleased_file'
					,'OldBug_version','OldHistory', 'OldHistory_line', 'OldTag', 'OldTagging', 'OldAssignment'
					,'OldComment','OldTracker_item','OldProject_join_request'
					,'OldSsh_key','OldUser');
	}

		function _getEntityNameMigrated(){
		return array('License', 'Project','Package','Release','Released_file'
					, 'Bug_version','History', 'History_line', 'Tag', 'Tagging', 'Assignment'
					, 'Comment','Tracker_item','Project_join_request'
					,'Ssh_key');
	}

	function GetName() {
		return 'Forge2Migrate';
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
		return false;
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

	function InitializeFrontend() {
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
	
	/**
	 * a inner function for factorize some recurrent code
	 **/
	function securize($str){
		return htmlentities($str, ENT_QUOTES, 'UTF-8');
	}
} 
?>
