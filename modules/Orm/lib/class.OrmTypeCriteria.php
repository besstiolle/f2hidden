<?php
/**
 * Contains the OrmTypeCriteria class
 * 
 * @since 0.0.1
 * @author Bess
 **/
 

/**
 * Enum for the different types of OrmTypeCriteria
 * 
 * 
 * @since 0.0.1
 * @author Bess
 * @package Orm
 **/
class OrmTypeCriteria {
    /**
    * Is equals to
    * 
    * @var string
    */
	public static $EQ = ' = ';
    
    /**
    * Is different Of
    * 
    * @var string
    */
	public static $NEQ = ' != ';
    
    /**
    * is strictly greater  than
    * 
    * @var string
    */
	public static $GT = ' > ';
    
    /**
    * is greater  or equals to
    * 
    * @var string
    */
	public static $GTE = ' >= ';
    
    /**
    * is strictly lesser than
    * 
    * @var string
    */
	public static $LT = ' < ';
    
    /**
    * is lesser or equals to
    * 
    * @var string
    */
	public static $LTE = ' <= ';
	
	/**
    * is NULL or is Empty
    * 
    * @var string
    */
	public static $EMPTY = 'is empty()';
	
    /**
    * is Not NULL and is Not Empty
    * 
    * @var string
    */
	public static $NEMPTY = 'is not empty()';
    
    /**
    * is NULL
    * 
    * @var string
    */
	public static $NULL = ' is null ';
    
    /**
    * is Not NULL
    * 
    * @var string
    */
	public static $NNULL = ' is not null';
    
    /**
    * is before (a Date)
    * 
    * @var string
    */
	public static $BEFORE = ' before ';
    
    /**
    * is after (a Date)
    * 
    * @var string
    */
	public static $AFTER = ' after ';
    
    /**
    * is between (a Date)
    * 
    * @var string
    */
	public static $BETWEEN = ' between ';
     
    /**
    * is contained into the array
    * 
    * /!\ need at last 2 values in the array
    * 
    * @var string
    */
	public static $IN = 'in (%s)';
    
    /**
    * is not contained into the array
    *                                                                   
    * /!\ need at last 2 values in the array
    * 
    * @var string
    */
	public static $NIN = 'not in (%s)';
    
    /**
    * contains the string
    * 
    * /!\ don't forget to add the wildcard '%'
    * 
    * @var string
    */
	public static $LIKE = ' like ';
    
    /**
    * doesn't contain the string
    * 
    * /!\ don't forget to add the wildcard '%'
    * 
    * @var string
    */
	public static $NLIKE = ' not like ';
}
?>
