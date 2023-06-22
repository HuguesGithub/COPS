<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalendarHomeBean
 * @author Hugues
 * @since v1.23.05.02
 * @version v1.23.06.25
 */
class AdminPageCalendarHomeBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.02
     * @version v1.23.05.28
     */
    public function getContentOnglet(): string
    {
        $this->dealWithGetActions();

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();
        
        // Construction du Breadcrumbs
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
        ];
        $strLink = HtmlUtils::getLink(self::LABEL_HOME, UrlUtils::getAdminUrl($urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);

        $strCards = $this->getCard();

        //
        $attributes = [
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_CALENDAR, $attributes);
    }

    /**
     * @since v1.23.05.02
     * @version v1.23.05.28
     */
    public function getCard(): string
    {
        // On récupère la date ingame courante pour l'afficher.
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        [$d, $m, $y, $h, $ii, $s] = explode(' ', DateUtils::getStrDate('d m y h i s', $tsNow));
        // On construit la liste déroulante des jours.
        $dLis = '';
        for ($i=1; $i<=31; ++$i) {
            $dLis .= $this->addOption($i, $d*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construit la liste déroulante des mois.
        $mLis = '';
        for ($i=1; $i<=12; ++$i) {
            $mLis .= $this->addOption($i, $m*1==$i, DateUtils::$arrFullMonths[$i]);
        }
        // On construit la liste déroulante des années.
        $yLis = '';
        for ($i=2030; $i<=2036; ++$i) {
            $yLis .= $this->addOption($i, $y*1==$i, $i);
        }
        // On construit la liste déroulante des heures.
        $hLis = '';
        for ($i=0; $i<=23; ++$i) {
            $hLis .= $this->addOption($i, $h*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construit la liste déroulante des minutes.
        $iLis = '';
        for ($i=0; $i<=59; ++$i) {
            $iLis .= $this->addOption($i, $ii*1==$i, str_pad($i, 2, '0', STR_PAD_LEFT));
        }
        // On construit la liste déroulante des secondes.
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
        return $this->getRender(self::WEB_PA_CALENDAR_HOME, $attributes);
    }

    /**
     * @since v1.23.05.02
     * @version v1.23.05.28
     */
    public function addOption($value, $blnSelected, $label)
    {
        return HtmlUtils::getOption($label, $value, $blnSelected);
    }

    /**
     * @since v1.23.05.02
     * @version v1.23.06.25
     */
    public function dealWithGetActions(): void
    {
        // Change-t-on la date ingame ?
        if (SessionUtils::fromPost(self::CST_CHANGE_DATE)!='') {
            // Le formulaire a été soumis, on défini une nouvelle date et un nouvel horaire.
            $h = SessionUtils::fromPost('sel-h');
            $i = SessionUtils::fromPost('sel-i');
            $s = SessionUtils::fromPost('sel-s');
            $m = SessionUtils::fromPost('sel-m');
            $d = SessionUtils::fromPost('sel-d');
            $y = SessionUtils::fromPost('sel-y');
            DateUtils::setCopsDate(mktime($h, $i, $s, $m, $d, $y));
        } elseif (SessionUtils::fromGet(self::CST_ACTION)==self::CST_ADD) {
            // On a appuyé sur un des boutons pour incrémenter les secondes, minutes ou heures.
            $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
            $qty = SessionUtils::fromGet(self::CST_QUANTITY);
            $tsNow += match (SessionUtils::fromGet(self::CST_UNITE)) {
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
    }

}
