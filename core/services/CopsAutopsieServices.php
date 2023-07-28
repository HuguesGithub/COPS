<?php
namespace core\services;

use core\daoimpl\CopsAutopsieDaoImpl;

/**
 * Classe CopsAutopsieServices
 * @author Hugues
 * @since 1.22.10.09
 * @version v1.23.07.29
 */
class CopsAutopsieServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.10.09
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
            $this->objDao = new CopsAutopsieDaoImpl();
        }
    }

    /**
     * @param array $attributes [E|S]
     * @since 1.22.10.09
     * @version v1.23.07.29
     */
    public function initFilters(&$attributes=[])
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = [];
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
     * @version v1.23.07.29
     */
    public function getAutopsies($attributes)
    {
        $this->initFilters($attributes);
        return $this->objDao->getAutopsies($attributes);
    }
  
    /**
     * @param integer
     * @return CopsAutopsie
     * @since 1.22.10.09
     * @version v1.23.07.29
     */
    public function getAutopsie($autopsieId=-1)
    {
        $attributes = [$autopsieId];
        $row = $this->objDao->getAutopsie($attributes);
        return new CopsAutopsie($row[0]);
    }
  
    /**
     * @since 1.22.10.10
     * @version v1.23.07.29
     */
    public function updateAutopsie($objCopsAutopsie)
    { $this->objDao->updateAutopsie($objCopsAutopsie); }
    
    /**
     * @since 1.22.10.10
     * @version v1.23.07.29
     */
    public function insertAutopsie(&$objCopsAutopsie)
    { $this->objDao->insertAutopsie($objCopsAutopsie); }
}
