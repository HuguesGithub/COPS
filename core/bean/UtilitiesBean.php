<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe UtilitiesBean
 * @author Hugues
 * @since 1.00.00
 * @version 1.00.00
 */
class UtilitiesBean implements ConstantsInterface
{
  protected $arrFullMonths = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
  protected $arrShortMonths = array(1=>'Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc');
  protected $arrFullDays = array(0=>'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
  protected $arrShortDays = array(0=>'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa');
  protected $arrShortEnglishDays = array(0=>'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

  /**
   * @return bool
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }
  /**
   * @return bool
   */
  public static function isLogged()
  { return is_user_logged_in(); }
  /**
   * @return bool
   */
  public static function isCopsLogged()
  {
    // On va checker dans les variables de SESSION si les infos relatives à Cops y sont stockées.
    if (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='err_login') {
      // TODO : Il faudrait vérifier que la valeur présente est bien valable.
      return true;
    } else {
      return false;
    }
  }
  /**
   * @param string $balise
   * @param string $label
   * @param array $attributes
   * @return string
   */
  public function getBalise($balise, $label='', $attributes=array())
  { return '<'.$balise.$this->getExtraAttributesString($attributes).'>'.$label.'</'.$balise.'>'; }
  /**
   * @param array $attributes
   * @return array
   */
  private function getExtraAttributesString($attributes)
  {
    $extraAttributes = '';
    if (!empty($attributes)) {
      foreach ($attributes as $key => $value) {
        $extraAttributes .= ' '.$key.'="'.$value.'"';
      }
    }
    return $extraAttributes;
  }
  /**
   * @param string $urlTemplate
   * @param array $args
   * @return string
   */
  public function getRender($urlTemplate, $args=array())
  { return vsprintf(file_get_contents(PLUGIN_PATH.$urlTemplate), $args); }

    public function getIcon($tag)
    {
        switch ($tag) {
            case self::I_BACKWARD :
                $prefix = 'fa-solid fa-';
            break;
            default :
                $prefix = 'fa-solid fa-biohazard';
                $tag = '';
            break;
        }
        return $this->getBalise(self::TAG_I, '', array(self::ATTR_CLASS=>$prefix.$tag));
    }
    
  static public function getCopsDate($format)
{
		$str_copsDate = get_option('cops_date');
		$h = substr($str_copsDate, 0, 2);
		$i = substr($str_copsDate, 3, 2);
		$s = substr($str_copsDate, 6, 2);
		$d = substr($str_copsDate, 9, 2);
		$m = substr($str_copsDate, 12, 2);
		$y = substr($str_copsDate, 15);
		switch ($format) {
			case 'tsnow' :
				$formatted = mktime($h, $i, $s, $m, $d, $y);
			break;
		}
		return $formatted;
}


}