<?php
namespace core\services;

use core\bean\UtilitiesBean;
use core\daoimpl\CopsEventDaoImpl;
use core\domain\CopsEventClass;
use core\domain\CopsEventCategorieClass;
use core\domain\CopsEventDateClass;
use core\utils\DateUtils;

/**
 * Classe CopsEventServices
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.05.28
 */
class CopsEventServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version 1.22.06.13
     * @since 1.22.06.13
     */
    public function __construct()
    {
        $this->Dao = new CopsEventDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_event
    ////////////////////////////////////

    /**
     * @since v1.23.05.15
     * @version v1.23.05.28
     */
    public function getEvent(int $id): CopsEventClass
    {
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => $id,
            ]
        ];
        $objsEvent = $this->getEvents($attributes);
        return !empty($objsEvent) ? array_shift($objsEvent) : new CopsEventClass();
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getEvents(array $attributes): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $startDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_DEBUT] ?? self::CST_LAST_DATE;
        $endDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_FIN] ?? self::CST_FIRST_DATE;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;

        // Traitement spécifique pour gérer le tri multi-colonnes
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $orderBy = self::FIELD_DATE_DEBUT;
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
            $startDate,
            $endDate,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getEvents($prepAttributes);
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function insertEvent(CopsEventClass &$objEvent): void
    {
        $this->Dao->insertEvent($objEvent);
        
        $this->addEventDates($objEvent);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function updateEvent(CopsEventClass $objEvent): void
    {
        // Une mise à jour.
        $this->Dao->updateEvent($objEvent);

        // On doit vérifier s'il y avait des eventDate associés.
        // Le cas échéant, on doit les supprimer.
        $attributes = [self::FIELD_EVENT_ID => $objEvent->getField(self::FIELD_ID)];
        $this->deleteEventDate($attributes);

        // Une fois event mis à jour, on doit créer les eventDate associés.
        $this->addEventDates($objEvent);
    }

    /**
     * @since v1.23.05.25
     * @version v1.23.05.28
     */
    public function deleteEvent(array $attributes): void
    {
        $attributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
        ];
        $this->Dao->deleteEvent($attributes);
    }

    ////////////////////////////////////
    // wp_7_cops_event_date
    ////////////////////////////////////

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getEventDates(array $attributes): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $startDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DSTART] ?? self::CST_FIRST_DATE;
        $endDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DEND] ?? self::CST_LAST_DATE;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;

        // Traitement spécifique pour gérer le tri multi-colonnes
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $orderBy = self::FIELD_DSTART;
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
            $startDate,
            $endDate,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getEventDates($prepAttributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function insertEventDate(CopsEventDateClass $objEventDate): void
    {
        $attributes = [
            $objEventDate->getField(self::FIELD_EVENT_ID),
            $objEventDate->getField(self::FIELD_DSTART),
            $objEventDate->getField(self::FIELD_DEND),
            $objEventDate->getField(self::FIELD_TSTART),
            $objEventDate->getField(self::FIELD_TEND),
        ];
        $this->Dao->insertEventDate($attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function deleteEventDate(array $attributes): void
    {
        $attributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_EVENT_ID] ?? self::SQL_JOKER_SEARCH,
        ];
        $this->Dao->deleteEventDate($attributes);
    }

    /**
     * @since v1.23.05.16
     * @version v1.23.06.04
     */
    public function addEventDates(CopsEventClass $objEvent): void
    {
        if ($objEvent->getField(self::FIELD_CUSTOM_EVENT)==1) {
            $this->addCustomEventDates($objEvent);
        } else {
            $this->addBasicEventDates($objEvent);
        }
    }

    /**
     * @since v1.23.05.30
     * @version v1.23.06.04
     */
    public function addCustomEventDates(CopsEventClass $objEvent): void
    {
        $objEventServices = new CopsEventServices();
        $objEventDate = new CopsEventDateClass();

        // TODO
        // La date de début indique à partir de quand on place ces jours Custom.
        // Si on veut le dernier lundi de mai, et qu'on a comme date de début le 01/01/2030, ça retourne le 27/05/2030
        // Si on a comme date de début le 01/06/2030, ça retourne le 26/05/2031
        // Mais pour le moment, on va juste récupérer l'année de la date de début...
        $strDateDebut = UtilitiesBean::fromPost(self::FIELD_DATE_DEBUT);
        $year = substr($strDateDebut, 0, 4);

        $strToTimeBase  = DateUtils::$arrOrdinals[1*$objEvent->getField(self::FIELD_CUSTOM_DAY)].' ';
        $strToTimeBase .= DateUtils::$arrFullEnglishDays[1*$objEvent->getField(self::FIELD_CUSTOM_DAY_WEEK)].' of ';

        // Si la répétition est mensuelle, doit faire tourner la valeur du mois. Sinon, elle est fixée.
        $repeatType = $objEvent->getField(self::FIELD_REPEAT_TYPE);
        if ($repeatType==self::CST_EVENT_RT_YEARLY) {
            $month = 1*$objEvent->getField(self::FIELD_CUSTOM_MONTH);
        } elseif ($repeatType==self::CST_EVENT_RT_MONTHLY) {
            $month = 1;
        } else {
            // TODO : On a choisi une mauvaise valeur comme type de répétition.
            return;
        }
        $strToTime = $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
        $dEvent = date('Y-m-d', strtotime($strToTime));

        // On intialise les données du premier eventDate à insérer.
        $objEventDate->setField(self::FIELD_EVENT_ID, $objEvent->getField(self::FIELD_ID));
        // Les dates
        $objEventDate->setField(self::FIELD_DSTART, $dEvent);
        $objEventDate->setField(self::FIELD_DEND, $dEvent);
        // Les heures
        if ($objEvent->isAllDayEvent()) {
            $tStart = 0;
            $tEnd   = 1440;
        } else {
            [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_DEBUT));
            $tStart = $i+60*(int)$h;
            [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_FIN));
            $tEnd   = $i+60*(int)$h;
        }
        $objEventDate->setField(self::FIELD_TSTART, $tStart);
        $objEventDate->setField(self::FIELD_TEND, $tEnd);

        $repeatEndValue = $objEvent->getField(self::FIELD_REPEAT_END_VALUE);
        switch ($objEvent->getField(self::FIELD_REPEAT_END)) {
            case self::CST_EVENT_RT_ENDREPEAT :
                // On répète un certain nombre de fois
                for ($i=0; $i<$repeatEndValue; ++$i) {
                    $objEventServices->insertEventDate($objEventDate);

                    if ($repeatType==self::CST_EVENT_RT_YEARLY) {
                        ++$year;
                        $strToTime = $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
                    } else {
                        // Monthly
                        ++$month;
                        if ($month>12) {
                            $month = 1;
                            ++$year;
                        }
                        $strToTime = $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
                    }
                    $dEvent = date('Y-m-d', strtotime($strToTime));
                    $objEventDate->setField(self::FIELD_DSTART, $dEvent);
                    $objEventDate->setField(self::FIELD_DEND, $dEvent);
                }
            break;
            case self::CST_EVENT_RT_NEVER :
                // On répète ad vitaam. Bon, techniquement, jusqu'à la dernière date.
                $repeatEndValue = self::CST_LAST_DATE;
            // no break
            case self::CST_EVENT_RT_ENDDATE :
                // On répète jusqu'à dépasser une date
                while ($dEvent<$repeatEndValue) {
                    // On insère l'event_date
                    $objEventServices->insertEventDate($objEventDate);

                    if ($repeatType==self::CST_EVENT_RT_YEARLY) {
                        ++$year;
                        $strToTime = $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
                    } else {
                        // Monthly
                        ++$month;
                        if ($month>12) {
                            $month = 1;
                            ++$year;
                        }
                        $strToTime = $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
                    }
                    $dEvent = date('Y-m-d', strtotime($strToTime));
                    $objEventDate->setField(self::FIELD_DSTART, $dEvent);
                    $objEventDate->setField(self::FIELD_DEND, $dEvent);
                }
            break;
            default :
                // Pas de répétition
                $objEventServices->insertEventDate($objEventDate);
            break;
        }
    }

    /**
     * @since v1.23.05.30
     * @version v1.23.06.04
     */
    public function addBasicEventDates(CopsEventClass $objEvent): void
    {
        $objEventServices = new CopsEventServices();
        $objEventDate = new CopsEventDateClass();

        // On intialise les données du premier eventDate à insérer.
        $objEventDate->setField(self::FIELD_EVENT_ID, $objEvent->getField(self::FIELD_ID));
        // Les dates
        $dStart = $objEvent->getField(self::FIELD_DATE_DEBUT);
        $objEventDate->setField(self::FIELD_DSTART, $dStart);
        $dEnd   = $objEvent->getField(self::FIELD_DATE_FIN);
        $objEventDate->setField(self::FIELD_DEND, $dEnd);
        // Les heures
        if ($objEvent->isAllDayEvent()) {
            $tStart = 0;
            $tEnd   = 1440;
        } else {
            [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_DEBUT));
            $tStart = $i+60*(int)$h;
            [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_FIN));
            $tEnd   = $i+60*(int)$h;
        }
        $objEventDate->setField(self::FIELD_TSTART, $tStart);
        $objEventDate->setField(self::FIELD_TEND, $tEnd);

        // On récupère les valeurs d'incrément et de répétition.
        // Ca ne sert pas pour le default, mais ça mutualise pour les autres.
        $arrIncr = $this->getArrIncrement($objEvent);
        $repeatEndValue = $objEvent->getField(self::FIELD_REPEAT_END_VALUE);

        // Selon le type de répétition, si répétition il y a
        switch ($objEvent->getField(self::FIELD_REPEAT_END)) {
            case self::CST_EVENT_RT_ENDREPEAT :
                // On répète un certain nombre de fois
                for ($i=0; $i<$repeatEndValue; ++$i) {
                    // On insère l'event_date
                    $objEventServices->insertEventDate($objEventDate);
                    // On incrémente les date de début et de fin
                    $dStart = DateUtils::getDateAjout($dStart, $arrIncr, self::FORMAT_DATE_YMD);
                    $dEnd = DateUtils::getDateAjout($dEnd, $arrIncr, self::FORMAT_DATE_YMD);
                    // On met àjour l'objet
                    $objEventDate->setField(self::FIELD_DSTART, $dStart);
                    $objEventDate->setField(self::FIELD_DEND, $dEnd);
                }
            break;
            case self::CST_EVENT_RT_NEVER :
                // On répète ad vitaam. Bon, techniquement, jusqu'à la dernière date.
                $repeatEndValue = self::CST_LAST_DATE;
            // no break
            case self::CST_EVENT_RT_ENDDATE :
                // On répète jusqu'à dépasser une date
                while ($dStart<$repeatEndValue) {
                    // On insère l'event_date
                    $objEventServices->insertEventDate($objEventDate);
                    // On incrémente les date de début et de fin
                    $dStart = DateUtils::getDateAjout($dStart, $arrIncr, self::FORMAT_DATE_YMD);
                    $dEnd = DateUtils::getDateAjout($dEnd, $arrIncr, self::FORMAT_DATE_YMD);
                    // On met àjour l'objet
                    $objEventDate->setField(self::FIELD_DSTART, $dStart);
                    $objEventDate->setField(self::FIELD_DEND, $dEnd);
                }
            break;
            default :
                // Pas de répétition
                $objEventServices->insertEventDate($objEventDate);
            break;
        }
            
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.05.21
     */
    public function getArrIncrement(CopsEventClass $objEvent): array
    {
        $repeatInterval = $objEvent->getField(self::FIELD_REPEAT_INTERVAL);
        switch ($objEvent->getField(self::FIELD_REPEAT_TYPE)) {
            case self::CST_EVENT_RT_DAILY :
                $arr = [$repeatInterval, 0, 0];
            break;
            case self::CST_EVENT_RT_WEEKLY :
                $arr = [7*$repeatInterval, 0, 0];
            break;
            case self::CST_EVENT_RT_MONTHLY :
                $arr = [0, $repeatInterval, 0];
            break;
            case self::CST_EVENT_RT_YEARLY :
                $arr = [0, 0, $repeatInterval];
            break;
            default :
                // Custom
                // TODO
                $arr = [0, 0, 0];
            break;
        }
        return $arr;
    }

    ////////////////////////////////////
    // wp_7_cops_event_categorie
    ////////////////////////////////////

    /**
     * @since v1.23.05.25
     * @version v1.23.05.28
     */
    public function getCategorie(int $id): CopsEventCategorieClass
    {
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => $id,
            ]
        ];
        $objsEventCategories = $this->getEventCategories($attributes);
        return !empty($objsEventCategories) ? array_shift($objsEventCategories) : new CopsEventCategorieClass();
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
    */
    public function getEventCategories(array $attributes=[]): array
    {
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;

        $orderBy = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_CATEG_LIBELLE;
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;

        $prepAttributes = [
            $id,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getEventCategories($prepAttributes);
    }

}
