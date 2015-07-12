<?php

$config = array();
include('config.php');

$user_name = $config['db_username'];
$password = $config['db_password'];
$database = $config['db_name'];
$server = $config['db_hostname'];
$prefix = $config['db_prefix'];
$trusted = $config['trusted_domain'];
$root_path = dirname(__FILE__);

$mysqli = new mysqli($server, $user_name, $password, $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql = 'SELECT * FROM '.$prefix.'module_forge2_forgefiles ORDER BY id ASC limit 0,5';
$result = $mysqli->query($sql);
$toDelete = array();

$finfo = finfo_open(FILEINFO_MIME_TYPE);

while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {

	$toDelete[] = $row['id'];

	//basic Test
	if(empty($row['name']) || empty($row['url']) || empty($row['type']) || empty($row['created_at'])){
		continue;
	}
	
	//only from certain domains
	foreach ($trusted as $trusted_domain) {
		$pos = strpos($row['url'], $trusted_domain);
		if($pos === FALSE || $pos !== 0 ) {
			logger('the file '.$url.' was on an untrusted domain');
			continue;
		}
	}

	//Get file
	$url = $row['url'];
	$file = @file_get_contents($url);
	if(!$file){
		logger('the file '.$url.' was not found');
		continue;
	}

	$type = $row['type'];
	$mime_authorised = array();
	if($type == 1){
		$suffix = 'avatar';
		$mime_authorised = array('image/gif','image/jpeg', 'image/png');
	} else if ($type == 2){
		$suffix = 'show';
		$mime_authorised = array('image/gif','image/jpeg', 'image/png');
	} else if ($type == 3){
		$suffix = 'release';
		$mime_authorised = array('text/xml','application/zip');
	} else {
		logger('the type parameter '.$md5.' is unknown');
		continue;
	}

	$tmp_dir = $root_path.'/tmp/cache';
	$dir = $root_path.'/uploads/projects/'.$row['id_related'].'/'.$suffix;

	file_put_contents($tmp_dir.'/'.$row['name'], $file);
	$md5 = md5_file($tmp_dir.'/'.$row['name']);
	if($md5 != $row['md5']){
		logger('the md5 calculated '.$md5.' is not equals to the md5 expected : '.$row['md5']);
		unlink($tmp_dir.'/'.$row['name']);		
		continue;
	}
	$sha1 = sha1_file($tmp_dir.'/'.$row['name']);

	//Check the mime
	$mime = finfo_file($finfo, $tmp_dir.'/'.$row['name']);
	if(!in_array($mime, $mime_authorised)){
		unlink($tmp_dir.'/'.$row['name']);
		logger('the mime type '.$mime.' is not accepted');
		continue;
	}

	//Move our new file
	if(!is_dir($dir)){
		//Create tmp directory if necessary
		mkdir($dir, 0750, true);
	}
	//Moving to tmp directory
	rename($tmp_dir.'/'.$row['name'], $dir.'/'.$row['name']);

}

$sql = 'DELETE FROM '.$prefix.'module_forge2_forgefiles where id in ('.implode(',', $toDelete).')';
$result = $mysqli->query($sql);

$mysqli->close();

function logger($msg){
	$msg = '[exec_] '.$msg;
	error_log($msg);
//	echo $msg.'<br/>';
}

?>