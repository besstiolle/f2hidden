<?php
	/**
	 * Here you will provide some informations about this new Entity OldRelease 
	 *  
	 **/ 
	class OldRelease extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','OldRelease', null,'OldReleases');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('package_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('name'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('release_notes'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('changelog'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('released_by'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('is_active'	
				, OrmCAST::$INTEGER
				, 1	
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
			$this->garnishDefaultValue("is_active",1);
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>