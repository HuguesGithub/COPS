<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageCalendrierBean
 * @author Hugues
 * @version 1.22.09.20
 * @since 1.22.09.20
 */
class AdminPageCalendrierBean extends AdminPageBean
{
  protected $urlTemplateAdminPageCalendrier       = 'web/pages/admin/page-admin-calendrier.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
  }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.22.09.20
   * @since 1.22.09.20
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageCalendrierBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @return string
   * @version 1.22.09.20
   * @since 1.22.09.20
   */
  public function getContentPage()
  {
	  if (isset($_POST['changeDate'])) {
		  $str_copsDate  = str_pad($_POST['sel-h'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['sel-i'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['sel-s'], 2, '0', STR_PAD_LEFT);
		  $str_copsDate .= ' '.str_pad($_POST['sel-d'], 2, '0', STR_PAD_LEFT).'/'.str_pad($_POST['sel-m'], 2, '0', STR_PAD_LEFT).'/'.$_POST['sel-y'];
			update_option('cops_date', $str_copsDate);
	  } elseif (isset($_GET['action']) && $_GET['action']=='add') {
		$tsNow = self::getCopsDate('tsnow');
		$qty = $_GET['quantite'];
		switch ($_GET['unite']) {
			case 's' :
				$tsNow += $qty;
			break;
			case 'm' :
				$tsNow += $qty*60;
			break;
			case 'h' :
				$tsNow += $qty*60*60;
			break;
		}
		update_option('cops_date', date('h:i:s d/m/Y', $tsNow));
	  }
	  
	  $tsNow = self::getCopsDate('tsnow');
		$dLis = '';
		for ($i=1; $i<=31; $i++) {
			$dLis .= '<option value="'.$i.'"'.(date('d', $tsNow)*1==$i ? ' selected' : '').'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
		}
		$mLis = '';
		for ($i=1; $i<=12; $i++) {
			$mLis .= '<option value="'.$i.'"'.(date('m', $tsNow)*1==$i ? ' selected' : '').'>'.$this->arrFullMonths[$i].'</option>';
		}
		$yLis = '';
		for ($i=2030; $i<=2035; $i++) {
			$yLis .= '<option value="'.$i.'"'.(date('Y', $tsNow)*1==$i ? ' selected' : '').'>'.$i.'</option>';
		}
		$hLis = '';
		for ($i=0; $i<=23; $i++) {
			$hLis .= '<option value="'.$i.'"'.(date('h', $tsNow)*1==$i ? ' selected' : '').'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
		}
		$iLis = '';
		for ($i=0; $i<=59; $i++) {
			$iLis .= '<option value="'.$i.'"'.(date('i', $tsNow)*1==$i ? ' selected' : '').'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
		}
		$sLis = '';
		for ($i=0; $i<=59; $i++) {
			$sLis .= '<option value="'.$i.'"'.(date('s', $tsNow)*1==$i ? ' selected' : '').'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>';
		}
		
    $attributes = array(
		// Jour courant
		$dLis,
		// Mois courant
		$mLis,
		// Année courante
		$yLis,
		// Heure courante
		$hLis,
		// Minute courante
		$iLis,
		// Seconde courante
		$sLis,
		// Url
		'/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=calendrier',
	'', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 
    );
    return $this->getRender($this->urlTemplateAdminPageCalendrier, $attributes);
  }





}
