<?php
namespace core\domain;

use core\services\CopsEventServices;
use core\utils\DateUtils;

/**
 * Classe CopsEventClass
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.05.14
 */
class CopsEventClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $eventLibelle;
    protected $categorieId;
    protected $dateDebut;
    protected $dateFin;
    protected $allDayEvent;
    protected $heureDebut;
    protected $heureFin;
    protected $repeatStatus;
    protected $repeatType;
    protected $repeatInterval;
    protected $repeatEnd;
    protected $repeatEndValue;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    /**
     * @since 1.23.04.21
     */
    public function setDateDebut(string $dateDebut): void
    {
        if (str_contains((string) $dateDebut, '-')) {
            $this->dateDebut = $dateDebut;
        } else {
            [$d, $m, $Y] = explode('/', (string) $dateDebut);
            $this->dateDebut = $Y.'-'.$m.'-'.$d;
        }
    }

    /**
     * @since 1.23.04.21
     */
    public function setDateFin(string $dateFin): void
    {
        if (str_contains((string) $dateFin, '-')) {
            $this->dateFin = $dateFin;
        } else {
            [$d, $m, $Y] = explode('/', (string) $dateFin);
            $this->dateFin = $Y.'-'.$m.'-'.$d;
        }
    }

    /**
     * @since 1.23.04.21
     */
    public function setRepeatEndValue(string $repeatEndValue): void
    {
        if (str_contains((string) $repeatEndValue, '/')) {
            [$d, $m, $Y] = explode('/', (string) $repeatEndValue);
            $repeatEndValue = $Y.'-'.$m.'-'.$d;
        }
        $this->repeatEndValue = $repeatEndValue;
    }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since 1.23.04.21
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsEventClass';
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.07
     */
    public static function convertElement($row): CopsEventClass
    {
        return parent::convertRootElement(new CopsEventClass(), $row);
    }

    /**
     * @since 1.23.04.21
     */
    public function getBean(): CopsEventBean
    { return new CopsEventBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since 1.23.04.21
     */
    public function checkFields(): bool
    {
        $blnOk = true;
        if ($this->eventLibelle=='' || $this->dateDebut=='' || $this->dateFin=='') {
            $blnOk = false;
        } elseif (!$this->isValidInterval()) {
            $blnOk = false;
        } elseif ($this->repeatStatus==1) {
            if ($this->repeatInterval=='' || $this->repeatEnd=='') {
                $blnOk = false;
            }
            if (
                ($this->repeatEnd==self::CST_EVENT_RT_ENDDATE || $this->repeatEnd==self::CST_EVENT_RT_ENDREPEAT) &&
                $this->repeatEndValue==''
            ) {
                $blnOk = false;
            }
        } else {
            // TODO
        }
        return $blnOk;
    }

    /**
     * @since 1.23.04.21
     */
    public function isValidInterval(): bool
    {
        $dF = $this->dateFin;
        $dD = $this->dateDebut;
        return !(!$this->isAllDayEvent() && ($dF<$dD || $dF==$dD && $this->heureFin<$this->heureDebut));
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isAllDayEvent(): bool
    {
        return $this->allDayEvent==1;
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isRepetitive(): bool
    {
        return $this->repeatStatus==1;
    }
    
    /**
     * @since 1.23.04.21
     */
    public function saveEvent(): void
    {
        $this->objCopsEventServices->saveEvent($this);
        
        if ($this->isRepetitive()) {
            // On créé un nouvel objet event_date
            $objCopsEventDate = new CopsEventDateClass();
            // Que l'on assigne à l'event créé à l'instant.
            $objCopsEventDate->setField(self::FIELD_EVENT_ID, $this->id);
            if ($this->isAllDayEvent()) {
                // Toute la journée
                $objCopsEventDate->setField(self::FIELD_TSTART, 0);
                $objCopsEventDate->setField(self::FIELD_TEND, 1440);
            } else {
                // Heures et minutes renseignées
                [$h, $i, ] = explode(':', (string) $this->heureDebut);
                $objCopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
                [$h, $i, ] = explode(':', (string) $this->heureFin);
                $objCopsEventDate->setField(self::FIELD_TEND, $i+$h*60);
            }

            $dateDebut = $this->dateDebut;
            $dateFin = $this->dateFin;
            
            // Selon le critère de fin de répétition, on gère différemment.
            switch ($this->repeatEnd) {
                case self::CST_EVENT_RT_ENDDATE :
                    // On répète jusqu'à dépasser une date
                    while ($dateDebut<$this->repeatEndValue) {
                        // On insère l'event_date
                        $objCopsEventDate->setField(self::FIELD_DSTART, $dateDebut);
                        $objCopsEventDate->setField(self::FIELD_DEND, $dateFin);
                        $objCopsEventDate->saveEventDate();
                        $this->incrementerDates($dateDebut, $dateFin);
                    }
                break;
                case self::CST_EVENT_RT_ENDREPEAT :
                    // On répète un certain nombre de fois
                    for ($i=0; $i<$this->repeatEndValue; ++$i) {
                        // On insère l'event_date
                        $objCopsEventDate->setField(self::FIELD_DSTART, $dateDebut);
                        $objCopsEventDate->setField(self::FIELD_DEND, $dateFin);
                        $objCopsEventDate->saveEventDate();
                        $this->incrementerDates($dateDebut, $dateFin);
                    }
                break;
                default :
                    // On récupère le dernier événement pour récupérer sa date de début
                    // TODO
                    $endDateValue = '';
                    // On répète jusqu'au-delà du dernier événement
                    do {
                        // On insère l'event_date
                        $objCopsEventDate->setField(self::FIELD_DSTART, $dateDebut);
                        $objCopsEventDate->setField(self::FIELD_DEND, $dateFin);
                        $objCopsEventDate->saveEventDate();
                        $this->incrementerDates($dateDebut, $dateFin);
                    } while ($dateDebut<$endDateValue);
                    // Ensuite, lorsqu'on affiche un écran,
                    // il faut vérifier qu'aucun event "never" ne devrait s'y afficher.
                    // Si c'est le cas, on créé l'event_date et tous ceux manquants depuis le dernier.
                    // TODO
                break;
            }
        } else {
            // S'il ne se répète pas, on insère une seule entrée dans event_date.
            $objCopsEventDate = new CopsEventDateClass();
            $objCopsEventDate->setField(self::FIELD_EVENT_ID, $this->id);
            $objCopsEventDate->setField(self::FIELD_DSTART, $this->dateDebut);
            $objCopsEventDate->setField(self::FIELD_DEND, $this->dateFin);
            if ($this->isAllDayEvent()) {
                // Toute la journée
                $objCopsEventDate->setField(self::FIELD_TSTART, 0);
                $objCopsEventDate->setField(self::FIELD_TEND, 1440);
            } else {
                // Heures et minutes renseignées
                [$h, $i, ] = explode(':', (string) $this->heureDebut);
                $objCopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
                [$h, $i, ] = explode(':', (string) $this->heureFin);
                $objCopsEventDate->setField(self::FIELD_TEND, $i+$h*60);
            }
            $objCopsEventDate->saveEventDate();
        }
    }
    
    /**
     * @since 1.23.04.21
     */
    public function incrementerDates(string &$dateDebut, string &$dateFin): void
    {
        // On parse les dates
        [$yd, $md, $dd] = explode('-', (string) $dateDebut);
        [$yf, $mf, $df] = explode('-', (string) $dateFin);
        // Selon le type de répétition, on incrémente de l'intervalle la donnée correspondante
        switch ($this->repeatType) {
            case self::CST_EVENT_RT_DAILY :
                $dd += $this->repeatInterval;
                $df += $this->repeatInterval;
            break;
            case self::CST_EVENT_RT_WEEKLY :
                $dd += 7*$this->repeatInterval;
                $df += 7*$this->repeatInterval;
            break;
            case self::CST_EVENT_RT_MONTHLY :
                $md += $this->repeatInterval;
                $mf += $this->repeatInterval;
            break;
            case self::CST_EVENT_RT_YEARLY :
                $yd += $this->repeatInterval;
                $yf += $this->repeatInterval;
            break;
            default :
            break;
        }
        // On met à jour les nouvelles dates.
        $dateDebut = date(self::FORMAT_DATE_YMD, mktime(0, 0, 0, $md, $dd, $yd));
        $dateFin = date(self::FORMAT_DATE_YMD, mktime(0, 0, 0, $mf, $df, $yf));
    }

    /**
     * @since 1.23.04.21
     */
    public function getRgbCategorie(): string
    {
        return implode(', ', sscanf($this->getCategorieCouleur(), "%02x%02x%02x"));
    }

    /**
     * @since 1.23.04.21
     */
    public function getCategorieCouleur(): string
    {
        return $this->getCategorie()->getField(self::FIELD_CATEG_COLOR);
    }

    /**
     * @since 1.23.04.21
     */
    public function getCategorie(): CopsEventCategorieClass
    {
        $this->CopsEventServices = new CopsEventServices();
        return $this->CopsEventServices->getCategorie($this->categorieId);
    }

    /**
     * @since v1.23.05.11
     * @version v1.23.05.14
     */
    public function isToday(string $displayDate): bool
    {
        return $this->dateDebut!=$displayDate;
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isSeveralDays(): bool
    {
        return $this->dateDebut!=$this->dateFin;
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isFirstDay(string $curDate): bool
    {
        return $curDate==$this->dateDebut;
    }
    
    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isLastDay(string $curDate): bool
    {
        return $curDate==$this->dateFin;
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isFirstWeek(string $curDate): bool
    {
        [$y, $m, $d] = explode('-', (string) $this->dateDebut);
        return DateUtils::getStrDate('W', $curDate)==date('W', mktime(0, 0, 0, $m, $d, $y));
    }

    /**
     * @since 1.23.04.21
     * @version v1.23.05.14
     */
    public function isLastWeek(string $curDate): bool
    {
        [$y, $m, $d] = explode('-', (string) $this->dateFin);
        return DateUtils::getStrDate('W', $curDate)==date('W', mktime(0, 0, 0, $m, $d, $y));
    }
    
    /**
     * @since 1.23.04.21
     */
    public function getNbDays(): int
    {
        [$y, $m, $d] = explode('-', (string) $this->dateDebut);
        $tsDeb = mktime(0, 0, 0, $m, $d, $y);
        [$y, $m, $d] = explode('-', (string) $this->dateFin);
        $tsFin = mktime(0, 0, 0, $m, $d, $y);
        return 1+($tsFin-$tsDeb)/(60*60*24);
    }
    
    /**
     * @since 1.23.04.21
     */
    public function getNbDaysSinceFirst(int $tsDisplay): int
    {
        [$y, $m, $d] = explode('-', (string) $this->dateDebut);
        $tsDeb = mktime(0, 0, 0, $m, $d, $y);
        return ($tsDisplay-$tsDeb)/(60*60*24)+1;
    }

    /**
     * @since 1.23.04.21
     */
    public function isSeveralWeeks(): bool
    {
        $m = substr((string) $this->dateFin, 5, 2);
        $d= substr((string) $this->dateFin, 8);
        $y = substr((string) $this->dateFin, 0, 4);
        $tsFin = mktime(0, 0, 0, $m, $d, $y);
        $m = substr((string) $this->dateDebut, 5, 2);
        $d = substr((string) $this->dateDebut, 8);
        $y = substr((string) $this->dateDebut, 0, 4);
        $tsDeb = mktime(0, 0, 0, $m, $d, $y);
        return (date('W', $tsDeb)!=date('W', $tsFin));
    }

    /**
     * @since 1.23.04.21
     */
    public function isOverThisWeek(int $tsDisplay): bool
    {
        $m = substr((string) $this->dateFin, 5, 2);
        $d = substr((string) $this->dateFin, 8);
        $y = substr((string) $this->dateFin, 0, 4);
        $tsFin = mktime(0, 0, 0, $m, $d, $y);
        return (date('W', $tsDisplay)!=date('W', $tsFin));
    }

    /**
     * @since 1.23.04.21
     */
    public function getColSpan(int $tsDeb=-1): bool
    {
        $m = substr((string) $this->dateFin, 5, 2);
        $d = substr((string) $this->dateFin, 8);
        $y = substr((string) $this->dateFin, 0, 4);
        $tsFin = mktime(0, 0, 0, $m, $d, $y);
        if ($tsDeb==-1) {
            $m = substr((string) $this->dateDebut, 5, 2);
            $d = substr((string) $this->dateDebut, 8);
            $y = substr((string) $this->dateDebut, 0, 4);
            $tsDeb = mktime(0, 0, 0, $m, $d, $y);
        }
        return round(($tsFin-$tsDeb)/(60*60*24));
    }

    /**
     * @since 1.23.04.21
     */
    public function getInsertAttributes(): array
    {
        return [
            $this->eventLibelle,
            $this->categorieId,
            $this->dateDebut,
            $this->dateFin,
            $this->allDayEvent,
            $this->heureDebut,
            $this->heureFin,
            $this->repeatStatus,
            $this->repeatType,
            $this->repeatInterval,
            $this->repeatEnd,
            $this->repeatEndValue
        ];
    }
}
