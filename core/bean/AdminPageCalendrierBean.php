<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * AdminPageCalendrierBean
 * @author Hugues
 * @since 1.22.09.20
 * @version v1.23.04.30
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
   * @since 1.22.09.20
   * @version v1.23.04.30
   */
    public function getContentPage(): string
    {
        if (static::fromPost('changeDate')!='') {
            $h = static::fromPost('sel-h');
            $i = static::fromPost('sel-i');
            $s = static::fromPost('sel-s');
            $m = static::fromPost('sel-m');
            $d = static::fromPost('sel-d');
            $y = static::fromPost('sel-y');
            DateUtils::setCopsDate(mktime($h, $i, $s, $m, $d, $y));
        } elseif (static::fromGet('action')=='add') {
            $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
            $qty = static::fromGet('quantite');
            switch (static::fromGet('unite')) {
                case 's' :
                    $tsNow += $qty;
                break;
                case 'm' :
                    $tsNow += $qty*60;
                break;
                case 'h' :
                    $tsNow += $qty*60*60;
                break;
                default :
                    break;
            }
            DateUtils::setCopsDate($tsNow);
        } else {
            // TODO
        }
      
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        [$d, $m, $y, $h, $ii, $s] = explode(' ', date('d m y h i s', $tsNow));
        $dLis = '';
        for ($i=1; $i<=31; ++$i) {
            $dLis .= $this->addOption($i, $d*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        $mLis = '';
        for ($i=1; $i<=12; ++$i) {
            $mLis .= $this->addOption($i, $m*1==$i, DateUtils::arrFullMonths[$i]);
        }
        $yLis = '';
        for ($i=2030; $i<=2035; ++$i) {
            $yLis .= $this->addOption($i, $y*1==$i, $i);
        }
        $hLis = '';
        for ($i=0; $i<=23; ++$i) {
            $hLis .= $this->addOption($i, $h*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        $iLis = '';
        for ($i=0; $i<=59; ++$i) {
            $iLis .= $this->addOption($i, $ii*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        $sLis = '';
        for ($i=0; $i<=59; ++$i) {
            $sLis .= $this->addOption($i, $s*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        
        $attributes = [
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
        ];
        return $this->getRender($this->urlTemplateAdminPageCalendrier, $attributes);
    }

    public function addOption($value, $blnSelected, $label)
    {
        return '<option value="'.$value.'"'.($blnSelected ? ' selected' : '').'>'.$label.'</option>';
    }



}
