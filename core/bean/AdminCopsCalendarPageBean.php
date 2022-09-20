<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarPageBean
 * @author Hugues
 * @since 1.22.05.04
 * @version 1.22.05.05
 */
class AdminCopsCalendarPageBean extends AdminCopsPageBean implements ConstantsInterface
{
  public function __construct()
  {
    parent::__construct();

    // On récupère le format à afficher
    $this->subOnglet = (isset($this->urlParams[self::CST_SUBONGLET]) ? $this->urlParams[self::CST_SUBONGLET] : self::CST_CAL_MONTH);
    // On récupère la date du jour
    if (isset($this->urlParams[self::CST_CAL_CURDAY])) {
      $str_copsDate = $this->urlParams[self::CST_CAL_CURDAY];
      $m = substr($str_copsDate, 0, 2);
      $d = substr($str_copsDate, 3, 2);
      $y = substr($str_copsDate, 6, 4);
    } else {
      $str_copsDate = get_option(self::CST_CAL_COPSDATE);
      $h = substr($str_copsDate, 0, 2);
      $i = substr($str_copsDate, 3, 2);
      $s = substr($str_copsDate, 6, 2);
      $d = substr($str_copsDate, 9, 2);
      $m = substr($str_copsDate, 12, 2);
      $y = substr($str_copsDate, 15, 4);
    }
    // On réécrit la date du jour
    $this->curdayFormat = str_pad($m, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT).'-'.$y.' '.$h.':'.$i.':'.$s;

    /////////////////////////////////////////
    // Construction du menu
    $extraUrl = self::CST_CAL_CURDAY.'='.substr($this->curdayFormat, 0, 10);
    $this->arrSubOnglets = array(
      self::CST_CAL_EVENT => array(self::FIELD_LABEL  => 'Événements'),
      self::CST_CAL_MONTH => array(self::FIELD_LABEL  => 'Mois',     self::CST_URL => $extraUrl),
      self::CST_CAL_WEEK  => array(self::FIELD_LABEL  => 'Semaine',  self::CST_URL => $extraUrl),
      self::CST_CAL_DAY   => array(self::FIELD_LABEL  => 'Jour',     self::CST_URL => $extraUrl),
      self::CST_CAL_PARAM => array(self::FIELD_LABEL  => 'Paramètres'),
    );

  }

  /**
   * @return string
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getBoard()
  {
    $this->buildBreadCrumbs('Calendrier', self::ONGLET_CALENDAR, true);

    // Soit on est loggué et on affiche le contenu du bureau du cops
    $urlTemplate = 'web/pages/public/public-board.php';
    $attributes = array(
      // La sidebar
      $this->getSideBar(),
      // Le contenu de la page
      $this->getOngletContent(),
      // L'id
      $this->CopsPlayer->getMaskMatricule(),
      // Le nom
      $this->CopsPlayer->getFullName(),
      // La barre de navigation
      $this->getNavigationBar(),
      // Le content header
      $this->getContentHeader(),
      '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getCalendarContent($prevCurday, $nextCurday, $calendarHeader)
  {
    $urlBase  = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_CALENDAR.'&'.self::CST_SUBONGLET.'=';
    // On défini les url
    $urlToday = $urlBase.$this->subOnglet;
    $urlMonth = $urlBase.self::CST_CAL_MONTH.'&'.self::CST_CAL_CURDAY.'='.substr($this->curdayFormat, 0, 10);
    $urlWeek  = $urlBase.self::CST_CAL_WEEK.'&'.self::CST_CAL_CURDAY.'='.substr($this->curdayFormat, 0, 10);
    $urlDay   = $urlBase.self::CST_CAL_DAY.'&'.self::CST_CAL_CURDAY.'='.substr($this->curdayFormat, 0, 10);
    $urlPrev  = $urlBase.$this->subOnglet.'&'.self::CST_CAL_CURDAY.'='.$prevCurday;
    $urlNext  = $urlBase.$this->subOnglet.'&'.self::CST_CAL_CURDAY.'='.$nextCurday;

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar.php';
    $attributes = array(
      // L'url pour accéder au mois/semaine/jour précédent
      $urlPrev,
      // L'url pour accéder au mois/semaine/jour suivant
      $urlNext,
      // L'url pour accéder au mois/semaine/jour courant
      $urlToday,
      // Le bandeau pour indiquer l'intervalle (mois/semaine/jour) visionné
      $calendarHeader,
      // Permet de définir si le bouton est celui de la vue en cours
      ($this->subOnglet==self::CST_CAL_MONTH ? ' '.self::CST_ACTIVE : ''),
      // L'url pour visualiser le jour courant dans le mois
      $urlMonth,
      // Permet de définir si le bouton est celui de la vue en cours
      ($this->subOnglet==self::CST_CAL_WEEK ? ' '.self::CST_ACTIVE : ''),
      // L'url pour visualiser le jour courant dans la semaine
      $urlWeek,
      // Permet de définir si le bouton est celui de la vue en cours
      ($this->subOnglet==self::CST_CAL_DAY ? ' '.self::CST_ACTIVE : ''),
      // L'url pour visualiser le jour courant dans le jour
      $urlDay,
      // Le contenu du calendrier à visionner
      $this->getCalendarViewContent(),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.06.09
   */
  public function getFcDayClass($tsDisplay)
  {
    // On récupère le jour courant
    $str_copsDate = get_option(self::CST_CAL_COPSDATE);
    $td = substr($str_copsDate, 9, 2);
    $tm = substr($str_copsDate, 12, 2);
    $tY = substr($str_copsDate, 15, 4);
    $tsToday   = mktime(1, 0, 0, $tm, $td, $tY);

    ///////////////////////////////////////////////////
    // On construit la classe de la cellule avec le jour de la semaine
    $strClass = 'fc-day-'.strtolower(date('D', $tsDisplay));
    // La date passée, présente ou future
    // si le jour est dans le passé : fc-day-past, dans le futur : fc-day-future, aujourd'hui : fc-day-today
    if ($tsDisplay==$tsToday) {
      $strClass .= ' fc-day-today';
    } elseif ($tsDisplay<=$tsToday) {
      $strClass .= ' fc-day-past';
    } else {
      $strClass .= ' fc-day-future';
    }
    // Un autre mois ou non
    // si le jour est dans un autre mois : fc-day-other
    if (date('m', $tsDisplay)!=date('m', $tsToday)) {
      $strClass .= ' fc-day-other';
    }
    ///////////////////////////////////////////////////
    return $strClass;
  }

  public static function getCalendarBean($subOnglet)
  {
    switch ($subOnglet) {
      case self::CST_CAL_EVENT :
        $Bean = new AdminCopsCalendarEventPageBean();
      break;
      case self::CST_CAL_PARAM :
        $Bean = new AdminCopsCalendarParameterPageBean();
      break;
      case self::CST_CAL_DAY :
        $Bean = new AdminCopsCalendarDayPageBean();
      break;
      case self::CST_CAL_WEEK :
        $Bean = new AdminCopsCalendarWeekPageBean();
      break;
      case self::CST_CAL_MONTH :
      default :
        $Bean = new AdminCopsCalendarMonthPageBean();
      break;
    }
    return $Bean;
  }
}
