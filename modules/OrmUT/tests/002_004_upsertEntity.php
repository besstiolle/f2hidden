
<p class='title'>002/004 : can I also save directly a Entity with OrmEntity->save() ?</p>
<?php
	
	$country4 = new CountryOrmUT();
	$country4->set('label', 'China');
	$country4->set('iso_code', 'cn');
	$copyChina = $country4->save();
	
	$expected = '4'; 
	$result = $copyChina->get('country_id');
	$class = '';
	if($result == $expected){
		$class = $cssSuccess;
	} else {
		$class = $cssError;
	}
	echo "<p class='$class'>we expected id #$expected in the returned Entity, we have got id #$result label in the returned entity</p>";
	
	$country4 = OrmCore::findById(new CountryOrmUT(),4);
		
	$expected = 'China'; 
	$result = $country4->get('label');
	$class = '';
	if($result == $expected){
		$class = $cssSuccess;
	} else {
		$class = $cssError;
	}
	echo "<p class='$class'>we expected $expected label, we have got $result label in the entity #4</p>";
	
	$country4->set('label', 'China_bis');
	$country4->save();
	
	$country4 = OrmCore::findById(new CountryOrmUT(),4);
		
	$expected = 'China_bis'; 
	$result = $country4->get('label');
	$class = '';
	if($result == $expected){
		$class = $cssSuccess;
	} else {
		$class = $cssError;
	}
	echo "<p class='$class'>we expected $expected label, we have got $result label in the entity #4</p>";
?>