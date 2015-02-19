<?php

	class OAuthToken extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','OAuthToken');
			
			$this->add(new OrmField('token'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('user_name'	
				, OrmCAST::$STRING
				, 255	
			));

			$this->add(new OrmField('dt'	
				, OrmCAST::$DATETIME
				, NULL	
			));
			
			$this->addIndexes('token');
		}

	}
?>