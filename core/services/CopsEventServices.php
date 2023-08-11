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
 * @version v1.23.08.12
 */
class CopsEventServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version v1.23.07.29
     * @since 1.22.06.13
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
            $this->objDao = new CopsEventDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_event
    ////////////////////////////////////

    /**
     * @since v1.23.05.15
     * @version v1.23.08.12
     */
    public function getEvent(int $id): CopsEventClass
    {
        $objsEvent = $this->getEvents([self::FIELD_ID => $id]);
        return !empty($objsEvent) ? array_shift($objsEvent) : new CopsEventClass();
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.08.12
     */
    public function getEvents(array $attributes): array
    {
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
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_CATEG_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_DATE_DEBUT] ?? self::CST_LAST_DATE,
            $attributes[self::FIELD_DATE_FIN] ?? self::CST_FIRST_DATE,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getEvents($prepAttributes);
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.07.29
     */
    public function insertEvent(CopsEventClass &$objEvent): void
    {
        $this->objDao->insertEvent($objEvent);
        $this->addEventDates($objEvent);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.07.29
     */
    public function updateEvent(CopsEventClass $objEvent): void
    {
        $this->objDao->updateEvent($objEvent);
        // On doit vérifier s'il y avait des eventDate associés.
        // Le cas échéant, on doit les supprimer.
        $attributes = [self::FIELD_EVENT_ID => $objEvent->getField(self::FIELD_ID)];
        $this->deleteEventDate($attributes);
        // Une fois event mis à jour, on doit créer les eventDate associés.
        $this->addEventDates($objEvent);
    }

    /**
     * @since v1.23.05.25
     * @version v1.23.07.29
     */
    public function deleteEvent(array $attributes): void
    {
        $attributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
        ];
        $this->objDao->deleteEvent($attributes);
    }

    ////////////////////////////////////
    // wp_7_cops_event_date
    ////////////////////////////////////

    public function addEventDatesByOccurrences(CopsEventClass $objEvent, CopsEventDateClass $objEventDate): void
    {
        $dEvent = $objEventDate->getField(self::FIELD_DSTART);
        $isSeveralDaysEvent = $objEvent->isSeveralDays();
        $repeatEndValue = $objEvent->getField(self::FIELD_REPEAT_END_VALUE);

        // On répète un certain nombre de fois
        for ($i=0; $i<$repeatEndValue; ++$i) {
            if ($isSeveralDaysEvent) {
                $dEnd = DateUtils::getDateAjout($dEvent, [1, 0, 0], self::FORMAT_DATE_YMD);
                $objEventDate->setField(self::FIELD_DEND, $dEnd);
            }
            $this->insertEventDate($objEventDate);

            [$year, $month, $day] = explode('-', $dEvent);
            $dEvent = $this->getNextDate($objEvent, $year, (int)$month, (int)$day, false);
            $objEventDate->setField(self::FIELD_DSTART, $dEvent);
            $objEventDate->setField(self::FIELD_DEND, $dEvent);
        }
    }

    public function addEventDatesBeforeEndDate(
        CopsEventClass $objEvent,
        CopsEventDateClass $objEventDate,
        string $repeatEndValue
    ): void
    {
        $dEvent = $objEventDate->getField(self::FIELD_DSTART);
        $isSeveralDaysEvent = $objEvent->isSeveralDays();

        // On répète jusqu'à dépasser une date
        while ($dEvent<$repeatEndValue) {
            if ($isSeveralDaysEvent) {
                $dEnd = DateUtils::getDateAjout($dEvent, [1, 0, 0], self::FORMAT_DATE_YMD);
                $objEventDate->setField(self::FIELD_DEND, $dEnd);
            }
            $this->insertEventDate($objEventDate);

            [$year, $month, $day] = explode('-', $dEvent);
            $dEvent = $this->getNextDate($objEvent, $year, (int)$month, (int)$day, false);
            $objEventDate->setField(self::FIELD_DSTART, $dEvent);
            $objEventDate->setField(self::FIELD_DEND, $dEvent);
        }
    }

    /**
     * @since v1.23.05.16
     * @version v1.23.06.04
     */
    public function addEventDates(CopsEventClass $objEvent): void
    {
        if ($objEvent->getField(self::FIELD_CUSTOM_EVENT)==1) {
            // Si on a un événement Custom
            $strToTime = $this->getStrToTime($objEvent);
            $this->addCustomEventDates($objEvent, $strToTime);
        } else {
            $repeatEnd = $objEvent->getField(self::FIELD_REPEAT_END);
            
            $arrEventDate = $this->initEventDates($objEvent);
            foreach ($arrEventDate as $objEventDate) {
                switch ($repeatEnd) {
                    case self::CST_EVENT_RT_ENDREPEAT :
                        $this->addEventDatesByOccurrences($objEvent, $objEventDate);
                    break;
                    case self::CST_EVENT_RT_NEVER :
                        // On répète ad vitaam. Bon, techniquement, jusqu'à la dernière date.
                        $repeatEndValue = self::CST_LAST_DATE;
                        $this->addEventDatesBeforeEndDate($objEvent, $objEventDate, $repeatEndValue);
                        break;
                    case self::CST_EVENT_RT_ENDDATE :
                        $repeatEndValue = $objEvent->getField(self::FIELD_REPEAT_END_VALUE);
                        $this->addEventDatesBeforeEndDate($objEvent, $objEventDate, $repeatEndValue);
                    break;
                    default :
                        // Pas de répétition
                        $this->insertEventDate($objEventDate);
                    break;
                }
            }
        }
    }

    /**
     * @since v1.23.06.06
     * @version v1.23.06.11
     */
    public function initEventDates(CopsEventClass $objEvent): array
    {
        $arrEventDates = [];

        $dStart = $objEvent->getField(self::FIELD_DATE_DEBUT);
        $dEnd   = $objEvent->getField(self::FIELD_DATE_FIN);
        [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_DEBUT));
        $tStart = $i+60*(int)$h;
        [$h, $i, ] = explode(':', (string) $objEvent->getField(self::FIELD_HEURE_FIN));
        $tEnd   = $i+60*(int)$h;

        $objEventDate = new CopsEventDateClass();
        // Valeurs par défaut
        $objEventDate->setField(self::FIELD_EVENT_ID, $objEvent->getField(self::FIELD_ID));
        $objEventDate->setField(self::FIELD_DSTART, $dStart);
        $objEventDate->setField(self::FIELD_DEND, $dEnd);
        $objEventDate->setField(self::FIELD_TSTART, $tStart);
        $objEventDate->setField(self::FIELD_TEND, $tEnd);

        // Valeurs spécifiques et on stocke les EventDate
        if ($objEvent->isAllDayEvent()) {
            $objEventDate->setField(self::FIELD_TSTART, 0);
            $objEventDate->setField(self::FIELD_TEND, 1440);

            $arrEventDates[] = $objEventDate;
        } elseif ($objEvent->isContiniousEvent()) {

            $arrEventDates[] = $objEventDate;
        } else {
            while ($dStart<=$dEnd) {
                $objEventDate->setField(self::FIELD_DEND, $dStart);

                $arrEventDates[] = $objEventDate;
                $dStart = DateUtils::getDateAjout($dStart, [1, 0, 0], self::FORMAT_DATE_YMD);

                $objEventDate = new CopsEventDateClass();
                // Valeurs par défaut
                $objEventDate->setField(self::FIELD_EVENT_ID, $objEvent->getField(self::FIELD_ID));
                $objEventDate->setField(self::FIELD_DSTART, $dStart);
                $objEventDate->setField(self::FIELD_DEND, $dStart);
                $objEventDate->setField(self::FIELD_TSTART, $tStart);
                $objEventDate->setField(self::FIELD_TEND, $tEnd);
            }
        }
        return $arrEventDates;
    }


    /**
     * @since v1.23.06.04
     * @version v1.23.06.11
     */
    public function getStrToTime(CopsEventClass $objEvent, int $year=null, int $month=null): string
    {
        if ($year==null) {
            $strDateDebut = UtilitiesBean::fromPost(self::FIELD_DATE_DEBUT);
            $year = (int)substr($strDateDebut, 0, 4);
        }
        if ($month==null) {
            $month = (int)$objEvent->getField(self::FIELD_CUSTOM_MONTH);
        }

        $strToTimeBase  = DateUtils::$arrOrdinals[1*$objEvent->getField(self::FIELD_CUSTOM_DAY)].' ';
        $strToTimeBase .= DateUtils::$arrFullEnglishDays[1*$objEvent->getField(self::FIELD_CUSTOM_DAY_WEEK)].' of ';

        return $strToTimeBase.DateUtils::$arrFullEnglishMonths[$month].' '.$year;
    }

    /**
     * @since v1.23.05.30
     * @version v1.23.06.11
     */
    public function addCustomEventDates(CopsEventClass $objEvent, string $strToTime): void
    {
        $objEventDate = new CopsEventDateClass();

        //////////////////////////////////////////////////////////////
        // On initialise l'objet EventDate par défaut.
        // On intialise les données du premier eventDate à insérer.
        $objEventDate->setField(self::FIELD_EVENT_ID, $objEvent->getField(self::FIELD_ID));
        // Les dates
        // On récupère la date de début de l'event
        $dStart = $objEvent->getField(self::FIELD_DATE_DEBUT);
        $dEnd = $objEvent->getField(self::FIELD_DATE_FIN);
        // On défini la première occurrence de EventDate
        $dEvent = date('Y-m-d', strtotime($strToTime));
        $cpt = 1;
        while ($dEvent<$dStart && $cpt<5) {
            [$year, $month, $day] = explode('-', $dEvent);
            $dEvent = $this->getNextDate($objEvent, $year, (int)$month, (int)$day);
            ++$cpt;
        }
        $objEventDate->setField(self::FIELD_DSTART, $dEvent);
        $objEventDate->setField(self::FIELD_DEND, $dEvent);
        // Les heures. On par du principe que c'est toujours "AllDay".
        $objEventDate->setField(self::FIELD_TSTART, 0);
        $objEventDate->setField(self::FIELD_TEND, 1440);
        //////////////////////////////////////////////////////////////

        $repeatEndValue = $objEvent->getField(self::FIELD_REPEAT_END_VALUE);
        switch ($objEvent->getField(self::FIELD_REPEAT_END)) {
            case self::CST_EVENT_RT_ENDREPEAT :
                // On répète un certain nombre de fois
                for ($i=0; $i<$repeatEndValue && $dEvent<=$dEnd; ++$i) {
                    $this->insertEventDate($objEventDate);
                    [$year, $month, $day] = explode('-', $dEvent);
                    $dEvent = $this->getNextDate($objEvent, $year, (int)$month, (int)$day);
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
                    $this->insertEventDate($objEventDate);
                    [$year, $month, $day] = explode('-', $dEvent);
                    $dEvent = $this->getNextDate($objEvent, $year, (int)$month, (int)$day);
                    $objEventDate->setField(self::FIELD_DSTART, $dEvent);
                    $objEventDate->setField(self::FIELD_DEND, $dEvent);
                }
            break;
            default :
                // Pas de répétition
                $this->insertEventDate($objEventDate);
            break;
        }
    }

    /**
     * @since v1.23.06.06
     * @version v.23.06.11
     */
    public function getNextDate(CopsEventClass $objEvent, int $year, int $month, int $day, bool $isCustom=true): string
    {
        $repeatType = $objEvent->getField(self::FIELD_REPEAT_TYPE);
        $repeatInterval = $objEvent->getField(self::FIELD_REPEAT_INTERVAL);
        // Aussi longtemps que le premier événement Custom n'est pas au-delà de la date de début
        // On incrémente la date en fonction du type de récurrence
        switch ($repeatType) {
            case self::CST_EVENT_RT_YEARLY :
                $year += $repeatInterval;
            break;
            case self::CST_EVENT_RT_MONTHLY :
                $month += $repeatInterval;
                if ($month>12) {
                    ++$year;
                    $month = $month%12;
                }
            break;
            case self::CST_EVENT_RT_WEEKLY :
                $day += 7*$repeatInterval;
            break;
            default :
                $day += $repeatInterval;
            break;
        }
        if ($isCustom) {
            return date(self::FORMAT_DATE_YMD, strtotime($this->getStrToTime($objEvent, $year, $month)));
        } else {
            return date(self::FORMAT_DATE_YMD, mktime(0, 0, 0, $month, $day, $year));
        }
    }

    /**
     * @since v1.23.05.05
     * @version v1.23.08.12
     */
    public function getEventDates(array $attributes): array
    {
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
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_EVENT_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_DSTART] ?? self::CST_LAST_DATE,
            $attributes[self::FIELD_DEND] ?? self::CST_FIRST_DATE,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getEventDates($prepAttributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.07.29
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
        $this->objDao->insertEventDate($attributes);
    }

    /**
     * @since v1.23.05.21
     * @version v1.23.07.29
     */
    public function deleteEventDate(array $attributes): void
    {
        $attributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_EVENT_ID] ?? self::SQL_JOKER_SEARCH,
        ];
        $this->objDao->deleteEventDate($attributes);
    }

    ////////////////////////////////////
    // wp_7_cops_event_categorie
    ////////////////////////////////////

    /**
     * @since v1.23.05.25
     * @version v1.23.08.12
     */
    public function getCategorie(int $id): CopsEventCategorieClass
    {
        $objs = $this->getEventCategories([self::FIELD_ID => $id]);
        return !empty($objs) ? array_shift($objs) : new CopsEventCategorieClass();
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.08.12
    */
    public function getEventCategories(array $attributes=[]): array
    {
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_CATEG_LIBELLE,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getEventCategories($prepAttributes);
    }

}
