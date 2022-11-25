<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsEventDateAlldayBean
 * @author Hugues
 * @since 1.22.11.25
 * @version 1.22.11.25
 */
class CopsEventDateAlldayBean extends CopsEventDateBean
{
    public function __construct($obj=null)
    {
		parent::__construct($obj);
    }

    /**
	 * @param $tag vaut day, week ou month, selon le type de vue.
	 * @param $tsDisplay la date du jour affiché.
     * @since 1.22.11.25
     * @version 1.22.11.25
     */
	public function getCartouche($tag, $tsDisplay, $nbEvents=0)
	{
		$divClass = '';
		$divStyle = 'margin-top: 0px; ';
		$divAttributes = '';
		$fcDayClass = 'fc-event-';
		///////////////////////////////////////////////////
		// On récupère le jour courant
		$tsToday = self::getCopsDate('tsStart');
		
		///////////////////////////////////////////////////
		// La date passée, présente ou future
		// si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
		if ($tsDisplay==$tsToday) {
			$fcDayClass .= 'today ';
		} elseif ($tsDisplay<$tsToday) {
			$fcDayClass .= 'past ';
		} else {
			$fcDayClass .= 'future ';
		}
		
		if ($tag==self::CST_CAL_DAY) {
			$this->getDayClass($fcDayClass, $tsDisplay);
		} elseif ($tag==self::CST_CAL_WEEK) {
			$this->getWeekClass($fcDayClass, $tsDisplay);
			$divClass = 'fc-daygrid-event-harness-abs';
			$divStyle .= 'top: '.(25*$nbEvents).'px; '; 
			if ($this->objCopsEvent->getNbDays()>1) {
				$divAttributes = ' data-colspan="'.($this->objCopsEvent->getNbDays()-1).'"';
			}
		} elseif ($tag==self::CST_CAL_MONTH) {
			$this->getWeekClass($fcDayClass, $tsDisplay);
			$divClass = 'fc-daygrid-event-harness-abs';
			$divStyle .= 'top: '.(25*$nbEvents).'px; '; 
			if ($this->objCopsEvent->getNbDays()>1) {
				$divAttributes = ' data-colspan="'.($this->objCopsEvent->getNbDays()-1).'"';
			}
		}
		
        $urlTemplate = self::WEB_PPFD_ALLDAY_EVENT;
        $attributes = array(
            // Titre
            $this->objCopsEvent->getField(self::FIELD_EVENT_LIBELLE),
            // getRgbCategorie()
            $this->objCopsEvent->getRgbCategorie(),
			// Si plusieurs colonnes, pas pertinent sur la vue Daily
			$divClass,
			// Les styles éventuels
			$divStyle,
			// D'autres classes supplémentaires
			$fcDayClass,
			// D'autres attributs.
			$divAttributes,
        );
        return $this->getRender($urlTemplate, $attributes);
	}

    /**
     * @since 1.22.11.25
     * @version 1.22.11.25
     */
	public function getDayClass(&$fcDayClass, $tsDisplay)
	{
		///////////////////////////////////////////////////
		// Si c'est le début de l'event
		if ($this->objCopsEvent->isFirstDay($tsDisplay)) {
			$fcDayClass .= 'fc-event-start ';
		}
		///////////////////////////////////////////////////
		// Si c'est la fin de l'event
		if ($this->objCopsEvent->isLastDay($tsDisplay)) {
			$fcDayClass .= 'fc-event-end ';
		}
	}

    /**
     * @since 1.22.11.25
     * @version 1.22.11.25
     */
	public function getWeekClass(&$fcDayClass, $tsDisplay)
	{
		///////////////////////////////////////////////////
		// Si c'est le début de l'event
		if ($this->objCopsEvent->isFirstWeek($tsDisplay)) {
			$fcDayClass .= 'fc-event-start ';
		}
		///////////////////////////////////////////////////
		// Si c'est la fin de l'event
		if ($this->objCopsEvent->isLastWeek($tsDisplay)) {
			$fcDayClass .= 'fc-event-end ';
		}
	}
}
