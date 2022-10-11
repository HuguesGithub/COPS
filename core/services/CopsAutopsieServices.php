<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsAutopsieServices
 * @author Hugues
 * @since 1.22.10.09
 * @version 1.22.10.09
 */
class CopsAutopsieServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version 1.22.10.09
     * @since 1.22.10.09
     */
    public function __construct()
    {
        $this->Dao = new CopsAutopsieDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    /**
     * @param array $attributes [E|S]
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function initFilters(&$attributes=array())
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = array();
        }
        if (!isset($attributes[self::SQL_WHERE_FILTERS]['idxEnquete'])) {
            $attributes[self::SQL_WHERE_FILTERS]['idxEnquete'] = self::SQL_JOKER_SEARCH;
        }
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $attributes[self::SQL_ORDER_BY] = self::FIELD_DSTART;
        }
        if (!isset($attributes[self::SQL_ORDER])) {
            $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        }
        if (!isset($attributes[self::SQL_LIMIT])) {
            $attributes[self::SQL_LIMIT] = -1;
        }
    }

    /**
     * @param array $attributes
     * @return array [CopsAutopsie]
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsies($attributes)
    {
        $this->initFilters($attributes);
        return $this->Dao->getAutopsies($attributes);
    }
  
    /**
     * @param integer
     * @return CopsAutopsie
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsie($autopsieId=-1)
    {
        $attributes = array($autopsieId);
        $row = $this->Dao->getAutopsie($attributes);
        return new CopsAutopsie($row[0]);
    }
  
    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function updateAutopsie($objCopsAutopsie)
    { $this->Dao->updateAutopsie($objCopsAutopsie); }
    
    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function insertAutopsie(&$objCopsAutopsie)
    { $this->Dao->insertAutopsie($objCopsAutopsie); }
}
