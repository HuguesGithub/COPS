<?php
namespace core\services;

use core\daoimpl\CopsPlayerDaoImpl;
use core\domain\CopsPlayerClass;
use core\utils\SessionUtils;

/**
 * Classe CopsPlayerServices
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.12
 */
class CopsPlayerServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
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
            $this->objDao = new CopsPlayerDaoImpl();
        }
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public static function getCurrentPlayer(): CopsPlayerClass
    {
        $objServices = new CopsPlayerServices();
        $attributes = [
            self::FIELD_MATRICULE => SessionUtils::fromSession(self::FIELD_MATRICULE)
        ];
        $objsCopsPlayer = $objServices->getCopsPlayers($attributes);
        return !empty($objsCopsPlayer) ? array_shift(($objsCopsPlayer)) : new CopsPlayerClass();
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getPlayer(int $id): CopsPlayerClass
    {
        $attributes = [self::FIELD_ID => $id];
        $objsCopsPlayer = $this->getCopsPlayers($attributes);
        return !empty($objsCopsPlayer) ? array_shift(($objsCopsPlayer)) : new CopsPlayerClass();
    }
    
    /**
     * @since v1.23.06.19
     * @version v1.23.08.12
     */
    public function getCopsPlayers(array $attributes=[]): array
    {
        $orderBy = '';
        $order = '';
        $this->buildOrderByAndOrderMultiple($attributes, self::FIELD_MATRICULE, self::SQL_ORDER_ASC, $orderBy, $order);
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_MATRICULE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_PASSWORD] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_GRADE] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_SECTION] ?? self::SQL_JOKER_SEARCH,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getPlayers($prepAttributes);
    }

    /**
     * @since v1.23.06.21
     * @version v1.23.07.29
     */
    public function updatePlayer(CopsPlayerClass $objPlayer): void
    {
        // Une mise à jour.
        $this->objDao->updatePlayer($objPlayer);
    }









}
