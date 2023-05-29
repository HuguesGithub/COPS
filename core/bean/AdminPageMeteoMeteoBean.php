<?php
namespace core\bean;

use core\services\CopsMeteoServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoMeteoBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.05.28
 */
class AdminPageMeteoMeteoBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_meteo pour une journée donnée.
     * Par défaut, la journée affichée est la journée ingame.
     * @since v1.23.04.26
     * @version v1.23.05.28
     */
    public function getContentOnglet(): string
    {
        $objCopsMeteoServices = new CopsMeteoServices();

        // On récupère le paramètre relatif à la date.
        $strDate = static::fromGet(self::CST_DATE);
        if ($strDate=='') {
            $strDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        }
        // TODO : A supprimer. Mis en place pour les besoins du dèv le temps de tester.
        $strDate = str_replace('-', '', $strDate);
        // TODO : Fin suppression.

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du contenu du header
        $trContent  = '';
        $trContent .= HtmlUtils::getTh(self::CST_NBSP, [self::CST_ROWSPAN=>2]);
        $trContent .= HtmlUtils::getTh(self::CST_NBSP);
        $trContent .= HtmlUtils::getTh(self::LABEL_CONDITIONS, [self::CST_COLSPAN=>3]);
        $trContent .= HtmlUtils::getTh(self::LABEL_CONFORT, [self::CST_COLSPAN=>3]);
        $trContent .= HtmlUtils::getTh(self::CST_NBSP, [self::CST_COLSPAN=>2]);
        $trContent .= HtmlUtils::getTh(self::CST_NBSP, [self::CST_ROWSPAN=>2]);
        $strHeader  = $this->getBalise(self::TAG_TR, $trContent);

        $trContent  = '';
        // Heure
        $trContent .= HtmlUtils::getTh(self::LABEL_HEURE);
        $trContent .= HtmlUtils::getTh(self::CST_NBSP);
        // Température
        // Météo + Icone
        // Force et sens du vent
        $trContent .= HtmlUtils::getTh(self::LABEL_TEMP);
        $trContent .= HtmlUtils::getTh(self::LABEL_WEATHER);
        $trContent .= HtmlUtils::getTh(self::LABEL_WIND);
        $trContent .= HtmlUtils::getTh(self::CST_NBSP);
        // Humidité
        // Baromètre
        // Visibilité
        $trContent .= HtmlUtils::getTh(self::LABEL_HUMIDITY);
        $trContent .= HtmlUtils::getTh(self::LABEL_BAROMETER);
        $trContent .= HtmlUtils::getTh(self::LABEL_VISIBILITY);
        $strHeader .= $this->getBalise(self::TAG_TR, $trContent);

        // Récupération des données de la journée
        $sqlAllAttributes = [];
        $sqlAllAttributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_METEO] = $strDate;
        $sqlAllAttributes[self::SQL_ORDER_BY] = self::FIELD_HEURE_METEO;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($sqlAllAttributes);

        // Construction du contenu du body
        $strBody = '';
        while (!empty($objsCopsMeteo)) {
            $objCopsMeteo = array_shift($objsCopsMeteo);
            $strBody .= $objCopsMeteo->getBean()->getAdminRow();
        }

        return $this->getRender(self::WEB_PA_METEO_METEO, [$strNavigation, $strHeader, $strBody]);
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.05.28
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = self::LABEL_TABLE_WEATHER;

        // On initialise le service dont on va avoir besoin.
        $objCopsMeteoServices = new CopsMeteoServices();

        $strLis = '';

        // On récupère la première date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($attributes);
        $objCopsMeteo = array_shift($objsCopsMeteo);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_FIRST_ENTRY, $objCopsMeteo->getDateHeure()));
        $strDate = $objCopsMeteo->getField(self::FIELD_DATE_METEO);
        $m = substr((string) $strDate, 4, 2);
        $d = substr((string) $strDate, 6);
        $y = substr((string) $strDate, 0, 4);
        $prevDate = DateUtils::getDateAjout($y.'-'.$m.'-'.$d, [-1, 0, 0], self::FORMAT_DATE_YMD);

        // On récupère la dernière date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($attributes);
        $objCopsMeteo = array_shift($objsCopsMeteo);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_LAST_ENTRY, $objCopsMeteo->getDateHeure()));
        $strDate = $objCopsMeteo->getField(self::FIELD_DATE_METEO);
        $m = substr((string) $strDate, 4, 2);
        $d = substr((string) $strDate, 6);
        $y = substr((string) $strDate, 0, 4);
        $curDate = $y.'-'.$m.'-'.$d;
        $nextDate = DateUtils::getDateAjout($curDate, [1, 0, 0], self::FORMAT_DATE_YMD);

        $strBody = $this->getBalise(self::TAG_UL, $strLis);

        // On fabrique la liste des boutons pour action.
        $linkClass = self::BTS_BTN_PRIMARY;
        $urlAttributes = [
            self::CST_ONGLET    => self::ONGLET_METEO,
            self::CST_SUBONGLET => self::CST_HOME,
            self::CST_DATE      => $prevDate,
        ];
        $btnGroup  = HtmlUtils::getLink(self::LABEL_PRECEDENTE, UrlUtils::getAdminUrl($urlAttributes), $linkClass);
        $urlAttributes[self::CST_DATE] = $curDate;
        $btnGroup .= HtmlUtils::getLink(self::LABEL_ACTUELLE, UrlUtils::getAdminUrl($urlAttributes), $linkClass);
        $urlAttributes[self::CST_DATE] = $nextDate;
        $btnGroup .= HtmlUtils::getLink(self::LABEL_SUIVANTE, UrlUtils::getAdminUrl($urlAttributes), $linkClass);

        $strBody .= HtmlUtils::getDiv($btnGroup, [self::ATTR_CLASS=>self::BTS_BTN_GROUP_SM]);
    }

}
