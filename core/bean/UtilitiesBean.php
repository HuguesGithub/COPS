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
    public $arrFullMonths = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
    'Septembre', 'Octobre', 'Novembre', 'Décembre');
    public $arrShortMonths = array(1=>'Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep',
    'Oct', 'Nov', 'Déc');
    public $arrFullDays = array(0=>'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
    public $arrShortDays = array(0=>'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa');
    public $arrShortEnglishDays = array(0=>'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

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
      return (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='err_login');
    }
    /**
     * @return int
     */
    public static function getWpUserId()
    { return get_current_user_id(); }
    /**
     * @param string
     * @return string
     */
    public static function getCopsDate($format)
    {
        $strCopsDate = get_option('cops_date');
        $h = substr($strCopsDate, 0, 2);
        $i = substr($strCopsDate, 3, 2);
        $s = substr($strCopsDate, 6, 2);
        $his = substr($strCopsDate, 0, 8);
        $d = substr($strCopsDate, 9, 2);
        $m = substr($strCopsDate, 12, 2);
        $y = substr($strCopsDate, 15);
        $dmy = substr($strCopsDate, 9);
        $tsCops = mktime($h, $i, $s, $m, $d, $y);
        $objUtilitiesBean = new UtilitiesBean();
        
        switch ($format) {
            case 'strJour' :
                $attributes = array(
                    $objUtilitiesBean->arrFullDays[date('N', $tsCops)], $d,
                    $objUtilitiesBean->arrFullMonths[$m*1], $y);
                $formatted = implode(' ', $attributes);
                break;
            case 'strSbDown' :
                $formatted = $objUtilitiesBean->arrShortDays[date('N', $tsCops)].' '.$dmy.'<br>'.$his;
                break;
            case 'tsnow' :
                $formatted = mktime($h, $i, $s, $m, $d, $y);
                break;
            case 'Y-m-d' :
                $formatted = date('Y-m-d', mktime($h, $i, $s, $m, $d, $y));
                break;
            default :
                $formatted = $format;
                break;
        }
        return $formatted;
    }
     
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
     * @param string $balise
     * @param string $label
     * @param array $attributes
     * @return string
     */
    public function getBalise($balise, $label='', $attributes=array())
    { return '<'.$balise.$this->getExtraAttributesString($attributes).'>'.$label.'</'.$balise.'>'; }
    /**
     * @param string $urlTemplate
     * @param array $args
     * @return string
     */
    public function getRender($urlTemplate, $args=array())
    { return vsprintf(file_get_contents(PLUGIN_PATH.$urlTemplate), $args); }
    /**
     * @param string
     * @return string
     */
    public function getIcon($tag)
    {
        switch ($tag) {
            case self::I_BACKWARD :
            case self::I_FILE_CIRCLE_CHECK :
            case self::I_FILE_CIRCLE_PLUS :
            case self::I_FILE_CIRCLE_XMARK :
                $prefix = 'fa-solid fa-';
            break;
            default :
                $prefix = 'fa-solid fa-biohazard';
                $tag = '';
            break;
        }
        return $this->getBalise(self::TAG_I, '', array(self::ATTR_CLASS=>$prefix.$tag));
    }
    /**
     * @param string $id
     * @param string $default
     * @return mixed
     */
    public function initVar($id, $default='')
    {
        if (isset($_POST[$id])) {
            return $_POST[$id];
        }
        if (isset($_GET[$id])) {
            return $_GET[$id];
        }
        return $default;
    }
    
    /**
     * @return string
     */
    public function getPublicHeader()
    {
        $urlTemplate = 'web/pages/public/fragments/main-header.php';
        $strNavigation = '';
        $strSubmenu    = '';

        //////////////////////////////////////////////////////////////
        // On enrichi le template et on le retourne
        $args = array(
            // La navigation - 1
            $strNavigation,
            // Sousmenu - 2
            $strSubmenu,
        );
        return $this->getRender($urlTemplate, $args);
    }

    /**
     * @return string
     */
    public function getPublicFooter()
    {
        $urlTemplate = 'web/pages/public/public-main-footer.php';

        $args = array(
            // ajaxUrl - 1
            admin_url('admin-ajax.php'),
        );
        return $this->getRender($urlTemplate, $args);
    }

}