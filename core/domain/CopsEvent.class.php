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

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function checkFields()
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
			if (($this->repeatEnd=='endDate' || $this->repeatEnd=='endRepeat') && $this->repeatEndValue=='') {
				$blnOk = false;
			}
		}
		return $blnOk;
	}

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function isValidInterval()
    {
		$dF = $this->dateFin;
		$dD = $this->dateDebut;
		return !(!$this->isAllDayEvent() && ($dF<$dD || $dF==$dD && $this->heureFin<$this->heureDebut));
	}

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function isAllDayEvent()
    { return ($this->allDayEvent==1); }

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function isRepetitive()
    { return ($this->repeatStatus==1); }
	
	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function saveEvent()
    {
        $this->CopsEventServices->saveEvent($this);
		
		if ($this->isRepetitive()) {
			// On créé un nouvel objet event_date
			$objCopsEventDate = new CopsEventDate();
			// Que l'on assigne à l'event créé à l'instant.
			$objCopsEventDate->setField(self::FIELD_EVENT_ID, $this->id);
			if ($this->isAllDayEvent()) {
				// Toute la journée
				$objCopsEventDate->setField(self::FIELD_TSTART, 0);
				$objCopsEventDate->setField(self::FIELD_TEND, 1440);
			} else {
				// Heures et minutes renseignées
				list($h, $i,) = explode(':', $this->heureDebut);
				$objCopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
				list($h, $i,) = explode(':', $this->heureFin);
				$objCopsEventDate->setField(self::FIELD_TEND, $i+$h*60);
			}

			$dateDebut = $this->dateDebut;
			$dateFin = $this->dateFin;
			
			// Selon le critère de fin de répétition, on gère différemment.
			switch ($this->repeatEnd) {
				case 'endDate' :
					// On répète jusqu'à dépasser une date
					while ($dateDebut<$this->repeatEndValue) {
						// On insère l'event_date
						$objCopsEventDate->setField(self::FIELD_DSTART, $dateDebut);
						$objCopsEventDate->setField(self::FIELD_DEND, $dateFin);
						$objCopsEventDate->saveEventDate();
						$this->incrementerDates($dateDebut, $dateFin);
					}
				break;
				case 'endRepeat' :
					// On répète un certain nombre de fois
					for ($i=0; $i<$this->repeatEndValue; $i++) {
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
					// Ensuite, lorsqu'on affiche un écran, il faut vérifier qu'aucun event "never" ne devrait s'y afficher.
					// Si c'est le cas, on créé l'event_date et tous ceux manquants depuis le dernier.
					// TODO
				break;
			}
		} else {
			// S'il ne se répète pas, on insère une seule entrée dans event_date.
			$objCopsEventDate = new CopsEventDate();
			$objCopsEventDate->setField(self::FIELD_EVENT_ID, $this->id);
			$objCopsEventDate->setField(self::FIELD_DSTART, $this->dateDebut);
			$objCopsEventDate->setField(self::FIELD_DEND, $this->dateFin);
			if ($this->isAllDayEvent()) {
				// Toute la journée
				$objCopsEventDate->setField(self::FIELD_TSTART, 0);
				$objCopsEventDate->setField(self::FIELD_TEND, 1440);
			} else {
				// Heures et minutes renseignées
				list($h, $i,) = explode(':', $this->heureDebut);
				$objCopsEventDate->setField(self::FIELD_TSTART, $i+$h*60);
				list($h, $i,) = explode(':', $this->heureFin);
				$objCopsEventDate->setField(self::FIELD_TEND, $i+$h*60);
			}
			$objCopsEventDate->saveEventDate();
		}
    }
	
	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
	public function incrementerDates(&$dateDebut, &$dateFin)
	{
		// On parse les dates
		list($yd, $md, $dd) = explode('-', $dateDebut);
		list($yf, $mf, $df) = explode('-', $dateFin);
		// Selon le type de répétition, on incrémente de l'intervalle la donnée correspondante
		switch ($this->repeatType) {
			case 'daily' :
				$dd += $this->repeatInterval;
				$df += $this->repeatInterval;
			break;
			case 'weekly' :
				$dd += 7*$this->repeatInterval;
				$df += 7*$this->repeatInterval;
			break;
			case 'monthly' :
				$md += $this->repeatInterval;
				$mf += $this->repeatInterval;
			break;
			case 'yearly' :
				$yd += $this->repeatInterval;
				$yf += $this->repeatInterval;
			break;
			default :
			break;
		}
		// On met à jour les nouvelles dates.
		$dateDebut = date('Y-m-d', mktime(0, 0, 0, $md, $dd, $yd));
		$dateFin = date('Y-m-d', mktime(0, 0, 0, $mf, $df, $yf));
	}

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function getRgbCategorie()
    {
        list($r, $g, $b) = sscanf($this->getCategorieCouleur(), "%02x%02x%02x");
        return $r.', '.$g.', '.$b;
    }

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function getCategorieCouleur()
    { return $this->getCategorie()->getField('categorieCouleur'); }

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function getCategorie()
    { return $this->CopsEventServices->getCategorie($this->categorieId); }

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
	public function isSeveralDays()
	{ return ($this->dateDebut!=$this->dateFin); }

	/**
	 * @since v1.22.11.22
	 * @version v1.22.11.22
	 */
    public function isFirstDay($tsDisplay)
    { return (date('Y-m-d', $tsDisplay)==$this->dateDebut); }
	
	/**
	 * @since v1.22.11.25
	 * @version v1.22.11.25
	 */
	public function isLastDay($tsDisplay)
	{ return (date('Y-m-d', $tsDisplay)==$this->dateFin); }

	/**
	 * @since v1.22.11.25
	 * @version v1.22.11.25
	 */
    public function isFirstWeek($tsDisplay)
    {
		list($y, $m, $d) = explode('-', $this->dateDebut);
		return (date('W', $tsDisplay)==date('W', mktime(0, 0, 0, $m, $d, $y)));
	}

	/**
	 * @since v1.22.11.25
	 * @version v1.22.11.25
	 */
    public function isLastWeek($tsDisplay)
    {
		list($y, $m, $d) = explode('-', $this->dateFin);
		return (date('W', $tsDisplay)==date('W', mktime(0, 0, 0, $m, $d, $y)));
	}
	

	/**
	 * @since v1.22.11.24
	 * @version v1.22.11.24
	 */
    public function getNbDays()
    {
		list($y, $m, $d) = explode('-', $this->dateDebut);
		$tsDeb = mktime(0, 0, 0, $m, $d, $y);
		list($y, $m, $d) = explode('-', $this->dateFin);
		$tsFin = mktime(0, 0, 0, $m, $d, $y);
		return 1+($tsFin-$tsDeb)/(60*60*24);
	}

	/**
	 * @since v1.22.11.24
	 * @version v1.22.11.24
	 */
    public function getNbDaysSinceFirst($tsDisplay)
    {
		list($y, $m, $d) = explode('-', $this->dateDebut);
		$tsDeb = mktime(0, 0, 0, $m, $d, $y);
		return ($tsDisplay-$tsDeb)/(60*60*24)+1;
	}

	/**
	 * @since v1.22.11.25
	 * @version v1.22.11.25
	 */
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

  public function getInsertAttributes()
  {
    return array($this->eventLibelle, $this->categorieId, $this->dateDebut, $this->dateFin, $this->allDayEvent, $this->heureDebut, $this->heureFin,
      $this->repeatStatus, $this->repeatType, $this->repeatInterval, $this->repeatEnd, $this->repeatEndValue);
  }
}
