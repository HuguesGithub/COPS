<?php
namespace core\services;

use core\daoimpl\CopsEnqueteDaoImpl;

/**
 * Classe CopsEnqueteServices
 * @author Hugues
 * @since 1.22.09.16
 * @version v1.23.07.29
 */
class CopsEnqueteServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.09.16
     * @version v1.23.07.29
     */
    public function __construct()
    {
        $this->initDao();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    private function initDao(): void
    {
        if ($this->objDao==null) {
            $this->objDao = new CopsEnqueteDaoImpl();
        }
    }

    /**
     * @param array $attributes [E|S]
     * @since 1.22.09.20
     * @version 1.22.09.24
     */
    public function initFilters(&$attributes=[])
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = [];
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
     * @version v1.23.07.29
     */
    public function getEnquetes($attributes)
    {
        $this->initFilters($attributes);
        return $this->objDao->getEnquetes($attributes);
    }
  
    /**
     * @param integer
     * @return CopsEnquete
     * @since 1.22.09.21
     * @version v1.23.07.29
     */
    public function getEnquete($enqueteId=-1)
    {
        $attributes = [$enqueteId];
        $row = $this->objDao->getEnquete($attributes);
        return new CopsEnquete($row[0]);
    }
  
    /**
     * @since 1.22.09.24
     * @version v1.23.07.29
     */
    public function updateEnquete($objCopsEnquete)
    { $this->objDao->updateEnquete($objCopsEnquete); }
    
    /**
     * @since 1.22.09.24
     * @version v1.23.07.29
     */
    public function insertEnquete(&$objCopsEnquete)
    { $this->objDao->insertEnquete($objCopsEnquete); }
}
