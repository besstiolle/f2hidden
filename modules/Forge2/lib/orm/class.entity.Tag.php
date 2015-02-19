<?php
	/**
	 * Here you will provide some informations about this new Entity Tag 
	 *  
	 **/ 
	class Tag extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','Tag', null , 'Tags');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('name'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));

			$this->garnishAutoincrement();
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>