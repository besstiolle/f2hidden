<?php
/**
 * A complex example with a composite primary key : an url of a website, a lang and the title/description in this lang.
 *  We don't want to create 2 entries for the same website for the same lang.
 *  We accept to create 2 entries for the same website if it's not with the same lang
 */ 
class UrlSkeleton extends OrmEntity
{
	public function __construct()
	{
		parent::__construct('ormskeleton','urlskeleton');
		
		$this->add(new OrmField('url'	
			, OrmCAST::$STRING
			, 255	
			, null 		
			, OrmKEY::$PK	// First primary key
		));
        
		$this->add(new OrmField('lang_iso'	
			, OrmCAST::$STRING
			, 10	
			, null 		
			, OrmKEY::$PK	// Second primary key
		));
		
		$this->add(new OrmField('title'	
			, OrmCAST::$STRING
			, 50	
		));
		
		$this->add(new OrmField('description'	
			, OrmCAST::$BUFFER
            , null
            , TRUE
		));

		$this->add(new OrmField('comments'	
			, OrmCAST::$NONE
            , null
			, TRUE 		
			, OrmKEY::$AK
			//We don't need to specifie the field as soon as a comment has many FK, one for each PK of UrlSkeleton
			, 'CommentSkeleton' 
		));
	}	


	/**
	 * When you declare this function, the framework will try to execute this function as soon as the table is created.
	 * So it's the best place to initiate your tables with some data !
	 */
	public function initTable(){
		$website = new UrlSkeleton();
		$website->set('url', 'http://www.furie.be');
		$website->set('lang_iso', 'fr_FR');
		$website->set('title', 'mon site perso');
		$website->save();

		$website = new UrlSkeleton();
		$website->set('url', 'http://www.furie.be');
		$website->set('lang_iso', 'en_US');
		$website->set('title', 'my personal blog');
		$website->save();
	}
}
?>