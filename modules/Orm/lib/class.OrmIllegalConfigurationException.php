<?php
 /**
 * Contains the class OrmIllegalArgumentException
 *
 * @since 0.0.1
 * @author Bess
 **/
 

/**
 * Classe extends Exception, used when the initial configuration of Entities isn't correct
 *
 * @since 0.0.1
 * @author Bess
 * @package Orm
*/
class OrmIllegalConfigurationException extends Exception  {
    
    /**
    * the inner error messages
    **/
	private $messages = array();

    /**
    * Public constructor
    *
    * @param string $msg [optional] the error message 
    * @param int $code [optional] the error code
    */
    public function __construct($msg=NULL, $code=0)
    {
    	if(is_array($msg) && count($msg) > 1 ){
    		parent::__construct("Multiple OrmIllegalConfigurationException detected", $code);
    		$this->messages = $msg;

    	} elseif( is_array($msg) ){
    		parent::__construct($msg[0], $code);
    		$this->messages = $msg;	
		} else {
    		parent::__construct($msg, $code);
    		$this->messages[] = $msg;	
    	}
    	
    }

    /**
    * Will return the messages
    *
    * @return string[] the messages
    **/
    public function getMessages(){
    	return $this->messages;
    }
}

?>
