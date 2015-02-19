<style>
.ok, .nok{
    padding: 3px 3px 3px 37px;
}
.ok{
	background: url("themes/OneEleven/images/icons/extra/accept.png") no-repeat scroll 10px 50% #D9E6C3;
}
.nok{
	background: url("themes/OneEleven/images/icons/extra/block.png") no-repeat scroll 10px 50% #F2D4CE;
}
pre{
	display:none;
}
p.title{
    font-size: 1.2em;
    font-weight: bold;
	cursor: pointer;
}
p.title:before{
	content: "[+]";
	position: relative;
	display: inline-block;
	margin: 0 5px;
}
p.title.less:before{
	content: "[-]";
}
div.test{
	display: none;
    border-radius: 5px;
    margin: 5px;
    padding: 5px;
}
div.testsucced{
	display: block;
    background-color: #D9E6C3;
    border-left: 1px solid #77AB13;
}
div.testfailed{
	display: block;
    background-color: #F2D4CE;
    border-left: 1px solid #AE432E;
}

</style>

<script type="text/javascript">
  // <![CDATA[
$(document).ready(function(){
	$( "label.labelExample" ).click(function() {
			$( this ).next().toggle();
	});

	$( "p.ok" ).parent().addClass("testsucced");
	$( "p.nok" ).parent().removeClass("testsucced");
	$( "p.nok" ).parent().addClass("testfailed");
	$( "p.nok" ).parent().children('.title').addClass("less");

	$( "div.testsucced p.ok" ).toggle();
	$( "div.testsucced br" ).toggle();

	$( "p.title" ).click(function() {
		$( this ).nextUntil('label').toggle();
		$( this ).toggleClass('less');
	});
});

// ]]>
  
  </script>


<h1>Units Tests</h1>

<?php

if (!function_exists("cmsms")) exit;

$config = cmsms()->GetConfig();

function load(&$mod, $pattern){
	
	$cssError = 'nok';
	$cssSuccess = 'ok';
	$db = cmsms()->GetDb();
	$config = cmsms()->GetConfig();
	
	$tests = glob(cms_join_path(dirname(__FILE__),"tests",$pattern));
	foreach($tests as $test) {
		echo "<div class='test'>";
		require($test);
		echo "<label class='labelExample' for='ex_".md5($test)."'>Clic to show the code</label><pre name='ex_".md5($test)."' class='toggle'>".htmlentities(file_get_contents($test))."</pre>";
		echo "</div>";
	}
}

function reinitAllTables($mod){
	$entities = $mod->getAllInstances();
	foreach($entities as $anEntity) {
		OrmCore::dropTable($anEntity);
		OrmCore::createTable($anEntity);
	}
}
//$link = $this->CreateLink($id, 'defaultadmin', '', '', array('section' => '%s'), '', true);

//echo "<img src='".$config['root_url']."/modules/OrmUT/img/processing.png' alt='executing the tests'";


echo "<h2>Loading the classes</h2>";
load($this, "000_*.php");
echo "<h2>Table & Sequence Creation/Deletion</h2>";
load($this, "001_*.php");
echo "<h2>Basic Crud Operations</h2>";
load($this, "002_*.php");
echo "<h2>Util test</h2>";
load($this, "003_*.php");
echo "<h2>CAST test</h2>";
load($this, "004_*.php");


//include('tests/005_kevin.php');