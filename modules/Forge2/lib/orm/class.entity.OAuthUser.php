<?php
	
	class OAuthUser extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','OAuthUser');

			$this->add(new OrmField('name'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('password'	
				, OrmCAST::$STRING
				, 255
			));
			$this->add(new OrmField('salt'	
				, OrmCAST::$STRING
				, 255
			));

		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			$user = new OAuthUser();
			$salt = sha1(mt_rand());
			$user->set('name', 'root');
			$user->set('salt', $salt);
			$user->set('password', sha1(sha1('pass').$salt));

			$user->save();
		}
	}
?>