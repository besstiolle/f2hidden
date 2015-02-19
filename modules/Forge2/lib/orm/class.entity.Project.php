<?php

	/**
	 * Here you will provide some informations about this new Entity Project
	 *  
	 **/ 
	class Project extends OrmEntity {
		public function __construct() {
			parent::__construct('Forge2','Project', null, 'Projects');

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
			$this->add(new OrmField('unix_name'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('description'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('registration_reason'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('project_type'	
				, OrmCAST::$INTEGER
				, 2	
				, TRUE 
				, NULL 
			));

			$this->add(new OrmField('project_category_1'	
				, OrmCAST::$INTEGER
				, 11
				, TRUE 
				, NULL 
			));

			$this->add(new OrmField('project_category_2'	
				, OrmCAST::$INTEGER
				, 11	
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
			$this->add(new OrmField('state'	
				, OrmCAST::$INTEGER
				, 2	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('approved_on'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('approved_by'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('reject_reason'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('license_id'	
				, OrmCAST::$INTEGER
				, NULL	
				, TRUE 
				, OrmKEY::$FK 
				, "License.id"
			));
			$this->add(new OrmField('changelog'	
				, OrmCAST::$BUFFER
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('roadmap'	
				, OrmCAST::$BUFFER
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
			$this->add(new OrmField('next_planned_release'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('repository_type'
				, OrmCAST::$INTEGER
				, 2	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('show_join_request'	
				, OrmCAST::$INTEGER
				, 1	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('last_repository_date'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('last_file_date'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('github_repo'	
				, OrmCAST::$STRING
				, 255	
				, TRUE 
				, NULL 
			));
			$this->add(new OrmField('freshness_date'	
				, OrmCAST::$DATETIME
				, NULL	
				, TRUE 
				, NULL 
			));

			$this->garnishAutoincrement();
			$this->garnishDefaultValue("state", EnumProjectState::pending);
			$this->garnishDefaultValue("repository_type", EnumProjectRepository::svn);
			$this->garnishDefaultValue("show_join_request",0);
			$this->garnishDefaultValue("github_repo","");


			$this->addIndexes("unix_name", true);
			$this->addIndexes("project_type");
			$this->addIndexes("project_category_1");
			$this->addIndexes("project_category_2");
			$this->addIndexes("state");
			$this->addIndexes("downloads");
			$this->addIndexes("last_repository_date");
			$this->addIndexes("last_file_date");
			$this->addIndexes("freshness_date");
		}

		/**
		 * When you declare this function, the framework will try to execute this function as soon as the table is created.
		 * So it's the best place to initiate your tables with some data !
		 */
		public function initTable(){
			
		}
	}
?>