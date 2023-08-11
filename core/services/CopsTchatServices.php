<?php
namespace core\services;

use core\daoimpl\CopsTchatDaoImpl;
use core\domain\CopsTchatClass;
use core\domain\CopsTchatStatusClass;
use core\utils\DateUtils;

/**
 * Classe CopsTchatServices
 * @author Hugues
 * @since v1.23.08.05
 * @version v1.23.08.12
 */
class CopsTchatServices extends LocalServices
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
            $this->objDao = new CopsTchatDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_tchat
    ////////////////////////////////////

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getTchats(array $attributes=[], string $when='oneWeekAgo'): array
    {
        // On est sur le Tchat Général par défaut qui a un salonId égal à 1.
        $salonId = $attributes[self::FIELD_SALON_ID] ?? 1;

        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        // On doit récupérer la date de la dernière visite du joueur dans ce salon.
        $objTchatStatut = $this->getTchatStatus($salonId, $objPlayer->getField(self::FIELD_ID));
        $lastRefreshed = $objTchatStatut->getField(self::FIELD_LAST_REFRESHED);

        // La valeur de récupération par défaut
        $tsToday = time();
        if ($when=='oneWeekAgo') {
            $defaultValue = DateUtils::getStrDate('Y-m-d H:i:s', $tsToday-60*60*24*7);
        } else {
            $defaultValue = DateUtils::getStrDate('Y-m-d H:i:s', $tsToday);
        }
        $tsDefault = $attributes[self::FIELD_TIMESTAMP] ?? $defaultValue;

        // Une fois fait, on récupère la date disponible ou celle par défaut selon la plus vieille.
        if ($lastRefreshed=='' || $lastRefreshed>$tsDefault) {
            $lastRefreshed = $tsDefault;
        }

        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $salonId,
            $attributes[self::FIELD_TO_PID] ?? self::SQL_JOKER_SEARCH,
            $lastRefreshed,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_TIMESTAMP,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getTchats($prepAttributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function insertTchat(CopsTchatClass &$obj): void
    { $this->objDao->insertTchat($obj); }

    ////////////////////////////////////
    // wp_7_cops_tchat_status
    ////////////////////////////////////

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getTchatStatus(int $salonId, $playerId): CopsTchatStatusClass
    {
        $attributes = [
            self::FIELD_SALON_ID => $salonId,
            self::FIELD_TO_PID => $playerId
        ];
        $objs = $this->getTchatStatuss($attributes);

        if (empty($objs)) {
            $attributes = [
                self::FIELD_SALON_ID => $salonId,
                self::FIELD_PLAYER_ID => (int)$playerId
            ];
            $obj = new CopsTchatStatusClass($attributes);
        } else {
            $obj = array_shift($objs);
        }

        return $obj;
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getTchatStatuss(array $attributes=[]): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_SALON_ID] ?? 1,
            $attributes[self::FIELD_TO_PID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_LAST_REFRESHED,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_DESC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getTchatStatuss($prepAttributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function insertTchatStatus(CopsTchatStatusClass &$obj): void
    { $this->objDao->insertTchatStatus($obj); }

    /**
     * @since v1.23.08.05
     */
    public function updateTchatStatus(CopsTchatStatusClass &$obj): void
    { $this->objDao->updateTchatStatus($obj); }

}
