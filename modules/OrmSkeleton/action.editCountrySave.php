<?php

if (!function_exists("cmsms")) exit;

if(!empty($params['country_id'])){
	//Let's retrieve our country !
	$country = OrmCore::findById(new CountrySkeleton(), $params['country_id']);
} else {
	$country = new CountrySkeleton();
}	

if(!empty($params['labelCountry'])){
	$country->set('labelCountry', $params['labelCountry']);
} else {
	$country->set('labelCountry', null);
}

//TODO cities

try{
	// We simply save the entity
	$country->save();
	// Please note that this code could also work.
	//OrmCore::insertEntity($country);
	
	//Go back to the default admin page
	$this->Redirect($id, 'defaultadmin', $returnid, $params);
	
// The illegaArgument will happen each time you don't control enough the data before inserting them
} catch (OrmIllegalArgumentException $e){
	// Ho ho ho... there is shitty information ...
	// Let's go inform the user
	$params['error'] = $e->getMessage();
	$this->Redirect($id, 'editCountry', $returnid, $params);
}
?>