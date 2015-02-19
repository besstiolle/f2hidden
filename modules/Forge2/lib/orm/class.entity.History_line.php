<?php
	/**
	 * Here you will provide some informations about this new Entity History_line 
	 *  
	 **/ 
	class History_line extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','History_line', null , 'History_lines');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('history_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$FK
				, 'History.id' 
			));
			$this->add(new OrmField('field_name'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('field_value_was'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('field_value_actual'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, NULL 
			));

			$this->garnishAutoincrement();

			$this->addIndexes("history_id");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>