<?php

$file = 'release.xml';
$stack = array();

function startTag($parser, $name, $attrs) {
   global $stack;
   $tag=array("name"=>$name,"attrs"=>$attrs);  
   array_push($stack,$tag);
}

function cdata($parser, $cdata) {
    global $stack,$i;
   
    if(trim($cdata)) {    
        $stack[count($stack)-1]['cdata']=$cdata;   
    }
}

function endTag($parser, $name) {
   global $stack;  
   $stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
   array_pop($stack);
}


function delTree($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
	  (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
} 

function mylog($msg){
	file_put_contents('_xml.log',date("H:i:s").' '.$msg."\n", FILE_APPEND);
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startTag", "endTag");
xml_set_character_data_handler($xml_parser, "cdata");

$data = xml_parse($xml_parser,file_get_contents($file));
if(!$data) {
   die(sprintf("XML error: %s at line %d",
	xml_error_string(xml_get_error_code($xml_parser)),
	xml_get_current_line_number($xml_parser)));
}

xml_parser_free($xml_parser);

$base = dirname(__FILE__).'/uploads/projects_release/';
$releases = array();
$count = count($stack[0]['children']);
$pad = 50;
$max = min($pad, $count);
$next=0;

if(isset($_GET['c'])){
	$releases = $stack[0]['children'];
	$releases = array_slice($releases , ($pad*$_GET['c']), $max);
	if(count($releases) == 0){
		echo "end !";
		mylog("end");
		exit;
	} else {
		$next = $_GET['c']+1;
	}
} else {
	mylog('start');
	if(is_dir($base)){
		delTree($base);
	}
}
$urlnext = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?c='.$next;
?>
<html><head>
<script>
</script>
</head><body onload='window.location.href = "<?php echo $urlnext; ?>"'>
<ul>
<?php
$i = 1;
foreach($releases as $release){

	$filename = null;
	$id = null;
	$url = null;

	foreach($release['children'] as $prop){
	
		if($prop['name'] == 'FILENAME'){
			$filename = $prop['cdata'];
		} elseif($prop['name'] == 'RELEASE-ID'){
		    $id = $prop['cdata'];
		} elseif($prop['name'] == 'PUBLIC-FILENAME'){
		    $url = $prop['cdata'];
		}
	}
		
	if($filename == null){
		mylog('filename null '.print_r($release['children'], true));
	}
	if($id == null){
		mylog('id null '.print_r($release['children'], true));
	}
	if($url == null){
		mylog('url null '.print_r($release['children'], true));
	}
	
	
	$path = $base.$id;
	if(!is_dir($path) && !mkdir($path, 0750, true)){
		mylog('cannot create directory '.$path.' '.print_r($release['children'], true));
	}
	if(!file_exists($path.'/'.$filename)){
		if(!file_put_contents($path.'/'.$filename, fopen($url, 'r'))){
			mylog('cannot download file from '.$url.' to '.$path.'/'.$filename.' '.print_r($release['children'], true));
		}
		echo "<li>".(($pad*$_GET['c']) + $i)."/".$count." Downloaded</li>";
	} else {
		echo "<li>".(($pad*$_GET['c']) + $i)."/".$count." Exists already : ".$url."</li>";
	}
	$i++;
}
?>
</ul>
</body></html>