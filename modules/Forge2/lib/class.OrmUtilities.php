<?php


class OrmUtilities{

	public static function entityToArray($e){
		$arrReturned = array();

		$array = array();
		if(get_parent_class($e) === 'OrmEntity'){
			$array = $e->getValues();
		} else if(is_array($e)) {
			$array = $e;
		}


		foreach ($array as $key => $value) {
			if(get_parent_class($value) === 'OrmEntity' || is_array($value)){
				$arrReturned[$key] = OrmUtilities::entityToArray($value);
			} else {
				$arrReturned[$key] = $value;
			}
		}
		
		return $arrReturned;
	}
}


?>