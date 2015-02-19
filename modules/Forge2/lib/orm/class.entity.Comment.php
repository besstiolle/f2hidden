<?php
	/**
	 * Here you will provide some informations about this new Entity Comment 
	 *  
	 **/ 
	class Comment extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','Comment', null,  'Comments');

			$this->add(new OrmField('id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, OrmKEY::$PK 
			));
			$this->add(new OrmField('title'	
				, OrmCAST::$STRING
				, 50	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('comment'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('created_at'	
				, OrmCAST::$DATETIME
				, NULL	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('commentable_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('commentable_type'	
				, OrmCAST::$INTEGER
				, 11	
				, NULL 
				, NULL 
			));
			$this->add(new OrmField('user_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, NULL 
				, NULL 
			));

			$this->garnishAutoincrement();
			$this->garnishDefaultValue("title","");
			//$this->garnishDefaultValue("created_at","0000-00-00 00:00:00");
			$this->garnishDefaultValue("commentable_id",0);
			$this->garnishDefaultValue("commentable_type",null);
			$this->garnishDefaultValue("user_id",0);

			$this->addIndexes("commentable_id");
			$this->addIndexes("commentable_type");
			$this->addIndexes("user_id");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>