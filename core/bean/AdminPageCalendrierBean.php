<?php
namespace core\bean;

use core\utils\DateUtils;

/**
 * AdminPageCalendrierBean
 * @author Hugues
 * @since 1.22.09.20
 * @version v1.23.05.07
 */
class AdminPageCalendrierBean extends AdminPageBean
{
    /**
     * @since 1.22.09.20
     * @version v1.23.05.07
     */
    public static function getStaticContentPage(): string
    {
        ///////////////////////////////////////////:
        $objBean = new AdminPageCalendrierBean();
        return $objBean->getContentOnglet();
    }

    /**
     * @since v1.23.04.26
     */
    public function getContentOnglet(): string
    {
        return 'Default. Specific getContentOnglet() to be defined.';
    }


    /**
     * @since 1.22.09.20
     * @version v1.23.05.01
     */
    public function getContentPage(): string
    {
        // Change-t-on la date ingame ?
        if (static::fromPost(self::CST_CHANGE_DATE)!='') {
            // Le formulaire a été soumis, on défini une nouvelle date et un nouvel horaire.
            $h = static::fromPost('sel-h');
            $i = static::fromPost('sel-i');
            $s = static::fromPost('sel-s');
            $m = static::fromPost('sel-m');
            $d = static::fromPost('sel-d');
            $y = static::fromPost('sel-y');
            DateUtils::setCopsDate(mktime($h, $i, $s, $m, $d, $y));
        } elseif (static::fromGet(self::CST_ACTION)==self::CST_ADD) {
            // On a appuyé sur un des boutons pour incrémenter les secondes, minutes ou heures.
            $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
            $qty = static::fromGet(self::CST_QUANTITY);
            $tsNow += match (static::fromGet(self::CST_UNITE)) {
                's' => $qty,
                'm' => $qty*60,
                'h' => $qty*60*60,
                default => 0,
            };
            DateUtils::setCopsDate($tsNow);
        } else {
            // TODO : gérer l'obligation d'un else sur les if / elseif.
            // Dans quelles conditions on se retrouve ici. Remonter une erreur ?
        }
      
        // On récupère la date ingame courante pour l'afficher.
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        [$d, $m, $y, $h, $ii, $s] = explode(' ', date('d m y h i s', $tsNow));
        // On construite la liste déroulante des jours.
        $dLis = '';
        for ($i=1; $i<=31; ++$i) {
            $dLis .= $this->addOption($i, $d*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construite la liste déroulante des mois.
        $mLis = '';
        for ($i=1; $i<=12; ++$i) {
            $mLis .= $this->addOption($i, $m*1==$i, DateUtils::$arrFullMonths[$i]);
        }
        // On construite la liste déroulante des années.
        $yLis = '';
        for ($i=2030; $i<=2036; ++$i) {
            $yLis .= $this->addOption($i, $y*1==$i, $i);
        }
        // On construite la liste déroulante des heures.
        $hLis = '';
        for ($i=0; $i<=23; ++$i) {
            $hLis .= $this->addOption($i, $h*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construite la liste déroulante des minutes.
        $iLis = '';
        for ($i=0; $i<=59; ++$i) {
            $iLis .= $this->addOption($i, $ii*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construite la liste déroulante des secondes.
        $sLis = '';
        for ($i=0; $i<=59; ++$i) {
            $sLis .= $this->addOption($i, $s*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }

        $urlAttributes = [self::CST_ONGLET=>self::ONGLET_CALENDAR];
        
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
            UrlUtils::getAdminUrl($urlAttributes),
        ];
        return $this->getRender(self::WEB_PA_METEO_CAL, $attributes);
    }

    public function addOption($value, $blnSelected, $label)
    {
        $attributes = [self::ATTR_VALUE=>$value];
        if ($blnSelected) {
            $attributes[self::CST_SELECTED] = self::CST_SELECTED;
        }
        return $this->getBalise(self::TAG_OPTION, $label, $attributes);
    }



}
