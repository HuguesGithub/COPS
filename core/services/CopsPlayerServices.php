<?php
namespace core\services;

use core\daoimpl\CopsPlayerDaoImpl;
use core\domain\CopsPlayerClass;

/**
 * Classe CopsPlayerServices
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.07.29
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
     * @since v1.23.06.19
     * @version v1.23.07.29
     */
    public function getCopsPlayers(array $attributes=[]): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $matricule = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_MATRICULE] ?? self::SQL_JOKER_SEARCH;
        $password = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_PASSWORD] ?? self::SQL_JOKER_SEARCH;
        $grade = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] ?? self::SQL_JOKER_SEARCH;
        $section = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_SECTION] ?? self::SQL_JOKER_SEARCH;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;

        // Traitement spécifique pour gérer le tri multi-colonnes
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $orderBy = self::FIELD_MATRICULE;
        } elseif (is_array($attributes[self::SQL_ORDER_BY])) {
            $orderBy = '';
            while (!empty($attributes[self::SQL_ORDER_BY])) {
                $orderBy .= array_shift($attributes[self::SQL_ORDER_BY]).' ';
                $orderBy .= array_shift($attributes[self::SQL_ORDER]).', ';
            }
            $orderBy = substr($orderBy, 0, -2);
            $order = '';
        } else {
            $orderBy = $attributes[self::SQL_ORDER_BY];
        }
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $id,
            $matricule,
            $password,
            $grade,
            $section,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getCopsPlayers($prepAttributes);
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
