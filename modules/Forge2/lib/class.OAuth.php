<?php

class OAuth{

	public static function validToken(\ApiResponse $response){
		
		$params = $response->getParams();
		$isTokenValid = false;

		if(!empty($params['token'])){
			$oauthToken = new OAuthToken();
			$tokens = OrmCore::findByIds($oauthToken, array($params['token']));
			if(count($tokens) == 0){
				$response->setCode(401);
				$response->setMessage("Unauthorized token");
			} else {

				$forge2 = ModuleOperations::get_instance()->get_module_instance('Forge2');
				$oauthToken = $tokens[0];
				$timeout = $forge2->GetPreference('token_timeout', 30); 

				//If the token is expired
				if(false && $oauthToken->get('dt') + $timeout < time()){
					$response->setCode(401);
					$response->setMessage("Unauthorized token expired");
				} else{
					$response->setCode(200);
					$response->setMessage("ok");

					$isUnique = $forge2->GetPreference('token_is_unique', FALSE);

					if( $forge2->GetPreference('token_is_unique', FALSE) ){
						//If the token is unique, we delete it
						OrmCore::deleteByIds($oauthToken, array($params['token']));
					} else {
						//else we "touch" it with a new creation date
						$oauthToken->set('dt', time());
						$oauthToken->save();

						// and prepare the information for the return
						$response->setContentToken(array('token' => $oauthToken->get('token'), 
														 'expireOn' => ($oauthToken->get('dt') + $timeout), 
														 'isUnique' => $isUnique ));
					}

					$isTokenValid = true;
				}
			}
		} else {
			$response->setCode(401);
			$response->setMessage("Unauthorized no token");
		}

		//If the token is not valid, we stop here the process
		if(!$isTokenValid){
			echo $response;
			exit;
		}

		return $response;
	}

	public static function getNewToken(\ApiResponse $response){

		$params = $response->getParams();

		if(!empty($params['user']) && !empty($params['pass'])){
			$user = new OAuthUser();
			$example = new OrmExample();
			$example->addCriteria('name', OrmTypeCriteria::$EQ, array($params['user']));
			$users = OrmCore::findByExample($user, $example);
			if(count($users) == 0) {
				$response->setCode(401);
				$response->setMessage("Unauthorized login");
			} else {
				$user = $users[0];
				$values = $user->getValues();
				if($values['password'] == sha1(sha1($params['pass']).$values['salt'])){
					$oauthToken = new OAuthToken();
					$example = new OrmExample();
					$example->addCriteria('user_name', OrmTypeCriteria::$EQ, array($params['user']));
					OrmCore::deleteByExample($oauthToken, $example);
					
					$dt = Time();
					$token = sha1(mt_rand().$dt);
					$oauthToken->set('token', $token);
					$oauthToken->set('user_name', $params['user']);
					$oauthToken->set('dt', $dt);
					$oauthToken->save();

					//Retrive the timeout
					$forge2 = ModuleOperations::get_instance()->get_module_instance('Forge2');
					$timeout = $forge2->GetPreference('token_timeout', 30); 
					$isUnique = $forge2->GetPreference('token_is_unique', FALSE); 

					$response->setCode(200);
					$response->setMessage("ok");
					$response->setContentToken(array('token' => $token, 
													'expireOn' => ($dt + $timeout), 
													'isUnique' => $isUnique ));
				} else {
					$response->setCode(401);
					$response->setMessage("Unauthorized password");
				}
			}
		} else {
			$response->setCode(400);
			$response->setMessage("Bad Request");
		}

		return $response;
	}
}