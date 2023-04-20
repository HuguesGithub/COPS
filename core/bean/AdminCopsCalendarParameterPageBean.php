<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarParameterPageBean
 * @author Hugues
 * @since 1.22.06.26
 * @version 1.22.06.26
 */
class AdminCopsCalendarParameterPageBean extends AdminCopsCalendarPageBean
{
  public function __construct()
  {
    parent::__construct();

    if (isset($_POST) && !empty($_POST)) {
      $newIngameDate  = str_pad((string) $_POST['heureIngame'], 2, '0', STR_PAD_LEFT).':';
      $newIngameDate .= str_pad((string) $_POST['minuteIngame'], 2, '0', STR_PAD_LEFT).':00 ';
      $newIngameDate .= $_POST['dateIngame'];
      update_option(self::CST_CAL_COPSDATE, $newIngameDate);
    }
  }

  /**
   * @since 1.22.06.26
   * @version 1.22.06.26
   */
  public function getOngletContent()
  {
    $str_copsDate = get_option(self::CST_CAL_COPSDATE);
    $h = substr((string) $str_copsDate, 0, 2);
    $i = substr((string) $str_copsDate, 3, 2);
    $d = substr((string) $str_copsDate, 9, 2);
    $m = substr((string) $str_copsDate, 12, 2);
    $y = substr((string) $str_copsDate, 15, 4);

    // Construction Options Heures
    $strOptionsHeure = '';
    for ($j=0; $j<24; ++$j) {
      $strOptionsHeure .= '<option value="'.$j.'"'.($j==$h ? ' selected="selected"' : '').'>'.$j.'</option>';
    }

    // Construction Options Minutes
    $strOptionsMinute = '';
    for ($j=0; $j<60; $j+=5) {
      $strOptionsMinute .= '<option value="'.$j.'"'.($j==$i*1 ? ' selected="selected"' : '').'>';
      $strOptionsMinute .= str_pad($j, 2, '0', STR_PAD_LEFT).'</option>';
    }

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-parameters.php';
    $attributes = [
        // Date Ingame
        $d.'/'.$m.'/'.$y,
        // Options Heures
        $strOptionsHeure,
        // Options Minutes
        $strOptionsMinute,
    ];
    return $this->getRender($urlTemplate, $attributes);
  }


}
