<?php
	/**
	 * Here you will provide some informations about this new Entity ForgeFile
	 *  
	 **/ 
	class ForgeFile extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','ForgeFile', null ,'ForgeFiles');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('name'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
			));
			$this->add(new OrmField('url'	
				, OrmCAST::$STRING
				, 255	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('md5'	
				, OrmCAST::$STRING
				, 32	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('type'	
				, OrmCAST::$INTEGER
				, 1	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('id_related'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('created_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));

			$this->garnishAutoincrement();
			
			$this->addIndexes("type");
			$this->addIndexes("id_related");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>