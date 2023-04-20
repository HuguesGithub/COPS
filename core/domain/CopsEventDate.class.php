<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsEventDate
 * @author Hugues
 * @version 1.22.06.13
 * @since 1.22.11.25
 */
class CopsEventDate extends LocalDomain
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;

    protected $eventId;
    protected $dStart;
    protected $dEnd;
    protected $tStart;
    protected $tEnd;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.06.13
     * @since 1.22.06.13
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'CopsEventDate';
        $this->CopsEventServices = new CopsEventServices();
        // On initialise l'event source
        $this->objCopsEvent = $this->getCopsEvent();
    }
    /**
     * @param array $row
     * @return CopsEventDate
     * @version 1.22.06.13
     * @since 1.22.06.13
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsEventDate(), $row); }
    
    /**
     * @version 1.22.06.13
     * @since 1.22.11.25
     */
    public function getBean()
    {
        $objCopsEvent = $this->getCopsEvent();
        if ($objCopsEvent->isAllDayEvent()) {
            $objBean = new CopsEventDateAlldayBean($this);
        } elseif ($objCopsEvent->isSeveralDays()) {
            $objBean = new CopsEventDateLongBean($this);
        } else {
            $objBean = new CopsEventDateDotBean($this);
        }
        return $objBean;
    }

    /**
     * @version 1.22.06.13
     * @since 1.22.11.25
     */
    public function getCopsEvent()
    { return $this->CopsEventServices->getCopsEvent($this->eventId); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  public function getStrDotTime($format, $slug)
  {
    switch ($slug) {
      case 'tstart' :
        $value = $this->tStart;
      break;
      case 'tend'   :
        $value = $this->tEnd;
      break;
      default       :
        return 'err-s';
      break;
    }

    $mins  = $value%60;
    $value = ($value-$mins)/60;
    $hrs   = $value%24;

    switch ($format) {
      case 'ga' :
        if ($hrs>12) {
          $hrs-=12;
          $last = 'p';
        } else {
          $last = 'a';
        }
        $str = $hrs.$last;
      break;
      default :
        $str = 'err-f';
      break;
    }

    return $str;
  }

  public function saveEventDate()
  { $this->CopsEventServices->saveEventDate($this); }

  public function getInsertAttributes()
  { return [$this->eventId, $this->dStart, $this->dEnd, $this->tStart, $this->tEnd]; }
}
