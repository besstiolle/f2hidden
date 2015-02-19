<?php
	/**
	 * Here you will provide some informations about this new Entity History
	 *  
	 **/ 
	class History extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','History', null , 'Histories');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('historizable_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('historizable_type'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('created_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, NULL 
				, NULL 
			));

			$this->garnishAutoincrement();

			$this->addIndexes("historizable_id");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>