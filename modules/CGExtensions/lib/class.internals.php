<?php

/**
 * Some internal methods
 *
 * @ignore
 */

namespace CGExtensions;

/**
 * Some internal methods
 *
 * @ignore
 */
final class internals
{
    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * @ignore
     */
    public static function reset_countries()
    {
        $db = cmsms()->GetDb();
        $query = 'TRUNCATE TABLE '.CGEXTENSIONS_TABLE_COUNTRIES;
        $db->Execute($query);

        $fn = cms_join_path(dirname(__DIR__),'countries.txt');
        $raw_countries = @file($fn);
        $n = 1;
        $query = 'INSERT INTO '.CGEXTENSIONS_TABLE_COUNTRIES.' (code,name,sorting) VALUES (?,?,?)';
        foreach($raw_countries as $one) {
            list($acronym,$country_name) = explode(',',$one);
            $acronym = trim($acronym);
            $country_name = trim($country_name);
            $db->Execute($query,array($acronym,$country_name,$n++));
        }
    }

    /**
     * @ignore
     */
    public static function reset_states()
    {
        $db = cmsms()->GetDb();
        $query = 'TRUNCATE TABLE '.CGEXTENSIONS_TABLE_STATES;
        $db->Execute($query);

        $fn = cms_join_path(dirname(__DIR__),'states.txt');
        $raw_states = @file($fn);
        $query = 'INSERT INTO '.CGEXTENSIONS_TABLE_STATES.' (code,name,sorting) VALUES (?,?,?)';
        $n = 1;
        foreach($raw_states as $one) {
            list($acronym,$state_name) = explode(',',$one);
            $acronym = trim($acronym);
            $state_name = trim($state_name);
            $db->Execute($query,array($acronym,$state_name,$n++));
        }
    }
}
?>