<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEnqueteServices
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.24
 */
class CopsEnqueteServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version 1.22.09.16
     * @since 1.22.09.16
     */
    public function __construct()
    {
        $this->Dao = new CopsEnqueteDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    /**
     * @param array $attributes [E|S]
     * @since 1.22.09.20
     * @version 1.22.09.24
     */
    public function initFilters(&$attributes=array())
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = array();
        }
        if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_STATUT_ENQUETE])) {
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STATUT_ENQUETE] = self::SQL_JOKER_SEARCH;
        }
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $attributes[self::SQL_ORDER_BY] = self::FIELD_DSTART;
        }
        if (!isset($attributes[self::SQL_ORDER])) {
            $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        }
        if (!isset($attributes[self::SQL_LIMIT])) {
            $attributes[self::SQL_LIMIT] = -1;
        }
    }

    /**
     * @param array $attributes
     * @return array [CopsEnquete]
     * @since 1.22.09.20
     * @version 1.22.09.20
     */
    public function getEnquetes($attributes)
    {
        $this->initFilters($attributes);
        return $this->Dao->getEnquetes($attributes);
    }
  
    /**
     * @param integer
     * @return CopsEnquete
     * @since 1.22.09.21
     * @version 1.22.09.21
     */
    public function getEnquete($enqueteId=-1)
    {
        $attributes = array($enqueteId);
        $row = $this->Dao->getEnquete($attributes);
        return new CopsEnquete($row[0]);
    }
  
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public function updateEnquete($objCopsEnquete)
    { $this->Dao->updateEnquete($objCopsEnquete); }
    
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public function insertEnquete(&$objCopsEnquete)
    { $this->Dao->insertEnquete($objCopsEnquete); }
}
