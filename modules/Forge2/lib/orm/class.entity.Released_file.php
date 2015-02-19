<?php
	/**
	 * Here you will provide some informations about this new Entity Released_file
	 *  
	 **/ 
	class Released_file extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','Released_file', null, 'Released_files');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('release_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, OrmKEY::$FK
				, 'Release.id' 
			));
			$this->add(new OrmField('filename'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('size'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('deleted_at'	
				, OrmCAST::$DATETIME
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
			$this->add(new OrmField('downloads'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('content_type'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));

			$this->garnishAutoincrement();


			$this->addIndexes("downloads");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>