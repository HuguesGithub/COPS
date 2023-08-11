<?php
namespace core\services;

use core\daoimpl\CopsAutopsieDaoImpl;
use core\domain\CopsAutopsieClass;

/**
 * Classe CopsAutopsieServices
 * @author Hugues
 * @since 1.22.10.09
 * @version v1.23.08.12
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
     * @since 1.22.10.09
     * @version v1.23.08.12
     */
    public function getAutopsie(int $autopsieId=-1): CopsAutopsieClass
    {
        $objs = $this->objDao->getAutopsies([self::FIELD_ID=>$autopsieId]);
        return empty($objs) ? new CopsAutopsieClass() : array_shift($objs);
    }

    /**
     * @since 1.22.10.09
     * @version v1.23.08.12
     */
    public function getAutopsies(array $attributes=[]): array
    {
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_IDX_ENQUETE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_DSTART,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_DESC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];

        return $this->objDao->getAutopsies($prepAttributes);
    }
  
    /**
     * @since 1.22.10.10
     * @version v1.23.08.12
     */
    public function updateAutopsie(CopsAutopsieClass $objCopsAutopsie): void
    { $this->objDao->updateAutopsie($objCopsAutopsie); }
    
    /**
     * @since 1.22.10.10
     * @version v1.23.08.12
     */
    public function insertAutopsie(CopsAutopsieClass &$objCopsAutopsie): void
    { $this->objDao->insertAutopsie($objCopsAutopsie); }
}
