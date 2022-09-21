<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsEvent
 * @author Hugues
 * @version 1.22.06.13
 * @since 1.22.06.13
 */
class CopsEvent extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
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
  public function setDateDebut($dateDebut)
  {
    if (strpos($dateDebut, '-')!==false) {
      $this->dateDebut = $dateDebut;
    } else {
      list($d, $m, $Y) = explode('/', $dateDebut);
      $this->dateDebut = $Y.'-'.$m.'-'.$d;
    }
  }
  public function setDateFin($dateFin)
  {
    if (strpos($dateFin, '-')!==false) {
      $this->dateFin = $dateFin;
    } else {
      list($d, $m, $Y) = explode('/', $dateFin);
      $this->dateFin = $Y.'-'.$m.'-'.$d;
    }
  }
  public function setRepeatEndValue($repeatEndValue)
  {
    if (strpos($repeatEndValue, '/')!==false) {
      list($d, $m, $Y) = explode('/', $repeatEndValue);
      $repeatEndValue = $Y.'-'.$m.'-'.$d;
    }
    $this->repeatEndValue = $repeatEndValue;
  }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.06.13
   * @since 1.22.06.13
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsEvent';
    $this->CopsEventServices = new CopsEventServices();
  }
  /**
   * @param array $row
   * @return CopsEvent
   * @version 1.22.06.13
   * @since 1.22.06.13
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsEvent(), $row); }
  /**
   * @return CopsEventBean
   * @version 1.22.06.13
   * @since 1.22.06.13
   */
  public function getBean()
  { return new CopsEventBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  public function isSeveralDays()
  { return ($this->dateDebut!=$this->dateFin); }

  public function isAllDayEvent()
  { return ($this->allDayEvent==1); }

  public function isFirstDay($tsDisplay)
  { return (date('Y-m-d', $tsDisplay)==$this->dateDebut); }

  public function isLastDay($tsDisplay)
  { return (date('Y-m-d', $tsDisplay)==$this->dateFin); }

  public function isSeveralWeeks()
  {
    $tsFin = mktime(0, 0, 0, substr($this->dateFin, 5, 2), substr($this->dateFin, 8), substr($this->dateFin, 0, 4));
    $tsDeb = mktime(0, 0, 0, substr($this->dateDebut, 5, 2), substr($this->dateDebut, 8), substr($this->dateDebut, 0, 4));
    return (date('W', $tsDeb)!=date('W', $tsFin));
  }

  public function isOverThisWeek($tsDisplay)
  {
    $tsFin = mktime(0, 0, 0, substr($this->dateFin, 5, 2), substr($this->dateFin, 8), substr($this->dateFin, 0, 4));
    return (date('W', $tsDisplay)!=date('W', $tsFin));
  }

  public function getColSpan($tsDeb=null)
  {
    $tsFin = mktime(0, 0, 0, substr($this->dateFin, 5, 2), substr($this->dateFin, 8), substr($this->dateFin, 0, 4));
    if ($tsDeb==null) {
      $tsDeb = mktime(0, 0, 0, substr($this->dateDebut, 5, 2), substr($this->dateDebut, 8), substr($this->dateDebut, 0, 4));
    }
    return round(($tsFin-$tsDeb)/(60*60*24));
  }

  public function isValidInterval()
  {
    list($Yd, $md, $dd) = explode('-', $this->dateDebut);
    list($Yf, $mf, $df) = explode('-', $this->dateFin);
    if (!$this->isAllDayEvent()) {
      list($hd, $id, $sd) = explode(':', $this->heureDebut);
      list($hf, $ifin, $sf) = explode(':', $this->heureFin);
    } else {
      $hd = 0;
      $id = 0;
      $sd = 0;
      $hf = 0;
      $ifin = 0;
      $sf = 0;
    }
    $tsDebut = mktime($hd, $id, $sd, $md, $dd, $Yd);
    $tsFin = mktime($hf, $ifin, $sf, $mf, $df, $Yf);
    return ($tsDebut<=$tsFin);
  }

  public function saveEvent()
  {
    $this->CopsEventServices->saveEvent($this);
    if ($this->repeatStatus==1) {
      switch ($this->repeatType) {
        case 'daily' :
          $this->createDailyEventDate();
        break;
        case 'weekly' :
          $this->createWeeklyEventDate();
        break;
        case 'monthly' :
          $this->createMonthlyEventDate();
        break;
        case 'yearly' :
          $this->createYearlyEventDate();
        break;
        default;
        break;
      }
    } else {
      $interval = $this->repeatInterval;
      $repeatEnd = $this->repeatEnd;
      $repeatEndValue = $this->repeatEndValue;
      if (strpos($repeatEndValue, '/')!==false) {
        list($d, $m, $Y) = explode('/', $repeatEndValue);
        $repeatEndValue = $Y.'-'.$m.'-'.$d;
      }

      $CopsEventDate = new CopsEventDate();
      $CopsEventDate->setField('eventId', $this->id);
      if ($this->allDayEvent==1) {
        $CopsEventDate->setField('tStart', 0);
        $CopsEventDate->setField('tEnd', 24*60);
      } else {
        list($h, $i, $s) = explode(':', $this->heureDebut);
        $CopsEventDate->setField('tStart', $i+$h*60);
        list($h, $i, $s) = explode(':', $this->heureFin);
        $CopsEventDate->setField('tEnd', $i+$h*60);
      }
      list($Y, $m, $d) = explode('-', $this->dateDebut);
      $dStart = mktime($h, $i, $s, $m, $d+$cpt, $Y);
      $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
      list($Y, $m, $d) = explode('-', $this->dateFin);
      $dEnd = mktime($h, $i, $s, $m, $d+$cpt, $Y);
      $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));
      $CopsEventDate->saveEventDate();
    }
  }

  public function createDailyEventDate()
  {
    $interval = $this->repeatInterval;
    $repeatEnd = $this->repeatEnd;
    $repeatEndValue = $this->repeatEndValue;
    if (strpos($repeatEndValue, '/')!==false) {
      list($d, $m, $Y) = explode('/', $repeatEndValue);
      $repeatEndValue = $Y.'-'.$m.'-'.$d;
    }

    // TODO : Réfléchir à comment implémenter le 'never'
    if ($repeatEnd=='endDate') {
      // On répète jusqu'à dépassement d'une date
      $blnOk = true;
      $cpt = 0;
      while ($blnOk) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*60);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d+$cpt, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d+$cpt, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        if ($CopsEventDate->getField('dStart')<=$repeatEndValue) {
          $CopsEventDate->saveEventDate();
        } else {
          $blnOk = false;
        }
        $cpt++;
      }
    } elseif ($repeatEnd=='endRepeat') {
      // On répète un certain nombre de fois.
      for ($cpt=0; $cpt<$repeatEndValue; $cpt++) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*36);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d+$cpt, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d+$cpt, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        $CopsEventDate->saveEventDate();
      }
    }
  }

  public function createWeeklyEventDate()
  {
    $interval = $this->repeatInterval;
    $repeatEnd = $this->repeatEnd;
    $repeatEndValue = $this->repeatEndValue;
    if (strpos($repeatEndValue, '/')!==false) {
      list($d, $m, $Y) = explode('/', $repeatEndValue);
      $repeatEndValue = $Y.'-'.$m.'-'.$d;
    }

    // TODO : Réfléchir à comment implémenter le 'never'
    if ($repeatEnd=='endDate') {
      // On répète jusqu'à dépassement d'une date
      $blnOk = true;
      $cpt = 0;
      while ($blnOk) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*60);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d+$cpt*7, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d+$cpt*7, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        if ($CopsEventDate->getField('dStart')<=$repeatEndValue) {
          $CopsEventDate->saveEventDate();
        } else {
          $blnOk = false;
        }
        $cpt++;
      }
    } elseif ($repeatEnd=='endRepeat') {
      // On répète un certain nombre de fois.
      for ($cpt=0; $cpt<$repeatEndValue; $cpt++) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*36);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d+$cpt*7, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d+$cpt*7, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        $CopsEventDate->saveEventDate();
      }
    }
  }

  public function createMonthlyEventDate()
  {
    $interval = $this->repeatInterval;
    $repeatEnd = $this->repeatEnd;
    $repeatEndValue = $this->repeatEndValue;
    if (strpos($repeatEndValue, '/')!==false) {
      list($d, $m, $Y) = explode('/', $repeatEndValue);
      $repeatEndValue = $Y.'-'.$m.'-'.$d;
    }

    // TODO : Réfléchir à comment implémenter le 'never'
    if ($repeatEnd=='endDate') {
      // On répète jusqu'à dépassement d'une date
      $blnOk = true;
      $cpt = 0;
      while ($blnOk) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField(self::FIELD_EVENT_ID, $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField(self::FIELD_TSTART, 0);
          $CopsEventDate->setField(self::FIELD_TEND, 24*60);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m+$cpt, $d, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m+$cpt, $d, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        if ($CopsEventDate->getField('dStart')<=$repeatEndValue) {
          $CopsEventDate->saveEventDate();
        } else {
          $blnOk = false;
        }
        $cpt++;
      }
    } elseif ($repeatEnd=='endRepeat') {
      // On répète un certain nombre de fois.
      for ($cpt=0; $cpt<$repeatEndValue; $cpt++) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField(self::FIELD_TSTART, 0);
          $CopsEventDate->setField('tEnd', 24*36);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m+$cpt, $d, $Y);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m+$cpt, $d, $Y);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        $CopsEventDate->saveEventDate();
      }
    }
  }

  public function createYearlyEventDate()
  {
    $interval = $this->repeatInterval;
    $repeatEnd = $this->repeatEnd;
    $repeatEndValue = $this->repeatEndValue;
    if (strpos($repeatEndValue, '/')!==false) {
      list($d, $m, $Y) = explode('/', $repeatEndValue);
      $repeatEndValue = $Y.'-'.$m.'-'.$d;
    }

    // TODO : Réfléchir à comment implémenter le 'never'
    if ($repeatEnd=='endDate') {
      // On répète jusqu'à dépassement d'une date
      $blnOk = true;
      $cpt = 0;
      while ($blnOk) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*60);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d, $Y+$cpt);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d, $Y+$cpt);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        if ($CopsEventDate->getField('dStart')<=$repeatEndValue) {
          $CopsEventDate->saveEventDate();
        } else {
          $blnOk = false;
        }
        $cpt++;
      }
    } elseif ($repeatEnd=='endRepeat') {
      // On répète un certain nombre de fois.
      for ($cpt=0; $cpt<$repeatEndValue; $cpt++) {
        $CopsEventDate = new CopsEventDate();
        $CopsEventDate->setField('eventId', $this->id);
        if ($this->allDayEvent==1) {
          $CopsEventDate->setField('tStart', 0);
          $CopsEventDate->setField('tEnd', 24*36);
        } else {
          list($h, $i, $s) = explode(':', $this->heureDebut);
          $CopsEventDate->setField('tStart', $i+$h*60);
          list($h, $i, $s) = explode(':', $this->heureFin);
          $CopsEventDate->setField('tEnd', $i+$h*60);
        }
        list($Y, $m, $d) = explode('-', $this->dateDebut);
        $dStart = mktime($h, $i, $s, $m, $d, $Y+$cpt);
        $CopsEventDate->setField('dStart', date('Y-m-d', $dStart));
        list($Y, $m, $d) = explode('-', $this->dateFin);
        $dEnd = mktime($h, $i, $s, $m, $d, $Y+$cpt);
        $CopsEventDate->setField('dEnd', date('Y-m-d', $dEnd));

        $CopsEventDate->saveEventDate();
      }
    }
  }

  public function getInsertAttributes()
  {
    return array($this->eventLibelle, $this->categorieId, $this->dateDebut, $this->dateFin, $this->allDayEvent, $this->heureDebut, $this->heureFin,
      $this->repeatStatus, $this->repeatType, $this->repeatInterval, $this->repeatEnd, $this->repeatEndValue);
  }

  public function getRgbCategorie()
  {
    list($r, $g, $b) = sscanf($this->getCategorieCouleur(), "%02x%02x%02x");
    return $r.', '.$g.', '.$b;
  }

  public function getCategorie()
  { return $this->CopsEventServices->getCategorie($this->categorieId); }

  public function getCategorieCouleur()
  { return $this->getCategorie()->getField('categorieCouleur'); }
}
