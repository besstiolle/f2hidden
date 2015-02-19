<?php
	/**
	 * Here you will provide some informations about this new Entity User 
	 *  
	 **/ 
	class OldUser extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','OldUser', null,'OldUsers');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('login'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('email'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('crypted_password'	
				, OrmCAST::$STRING
				, 40	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('salt'	
				, OrmCAST::$STRING
				, 40	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('created_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('updated_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('activation_code'	
				, OrmCAST::$STRING
				, 40	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('activated_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('remember_token'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('remember_token_expires_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('superuser'	
				, OrmCAST::$INTEGER
				, 1	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('full_name'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('password_reset_code'	
				, OrmCAST::$STRING
				, 40	
				, TRUE 
				, NULL 
			));

			$this->garnishAutoincrement();
			$this->garnishDefaultValue("superuser",0);
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>