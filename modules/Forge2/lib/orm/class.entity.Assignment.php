<?php
	/**
	 * Here you will provide some informations about this new Entity Assignment
	 *  
	 **/ 
	class Assignment extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','Assignment', null ,'Assignments');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('project_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, OrmKEY::$FK 
				, "Project.id"
			));
			$this->add(new OrmField('user_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('role'	
				, OrmCAST::$INTEGER
				, NULL	
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

			$this->garnishAutoincrement();
			
			$this->addIndexes("user_id");
			$this->addIndexes("role");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>