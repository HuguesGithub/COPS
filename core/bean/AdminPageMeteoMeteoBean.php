<?php
namespace core\bean;

use core\services\CopsMeteoServices;
use core\utils\DateUtils;

/**
 * AdminPageMeteoMeteoBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.04.30
 */
class AdminPageMeteoMeteoBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_meteo pour une journée donnée.
     * Par défaut, la journée affichée est la journée ingame.
     * @since v1.23.04.26
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
        $trContent .= $this->getBalise(self::TAG_TH, '&nbsp;', [self::CST_ROWSPAN=>2]);
        $trContent .= $this->getBalise(self::TAG_TH, self::CST_NBSP);
        $trContent .= $this->getBalise(self::TAG_TH, 'Conditions', [self::CST_COLSPAN=>3]);
        $trContent .= $this->getBalise(self::TAG_TH, 'Confort', [self::CST_COLSPAN=>3]);
        $trContent .= $this->getBalise(self::TAG_TH, self::CST_NBSP, [self::CST_COLSPAN=>2]);
        $trContent .= $this->getBalise(self::TAG_TH, '&nbsp;', [self::CST_ROWSPAN=>2]);
        $strHeader  = $this->getBalise(self::TAG_TR, $trContent);

        $trContent  = '';
        // Heure
        $trContent .= $this->getBalise(self::TAG_TH, 'Heure');
        $trContent .= $this->getBalise(self::TAG_TH, self::CST_NBSP);
        // Température
        // Météo + Icone
        // Force et sens du vent
        $trContent .= $this->getBalise(self::TAG_TH, 'Temp');
        $trContent .= $this->getBalise(self::TAG_TH, 'Météo');
        $trContent .= $this->getBalise(self::TAG_TH, 'Vent');
        $trContent .= $this->getBalise(self::TAG_TH, self::CST_NBSP);
        // Humidité
        // Baromètre
        // Visibilité
        $trContent .= $this->getBalise(self::TAG_TH, 'Humidité');
        $trContent .= $this->getBalise(self::TAG_TH, 'Baromètre');
        $trContent .= $this->getBalise(self::TAG_TH, 'Visibilité');
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
     * @version v1.23.04.30
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = 'Table Météo';

        // On initialise le service dont on va avoir besoin.
        $objCopsMeteoServices = new CopsMeteoServices();

        $strLis = '';

        // On récupère la première date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($attributes);
        $objCopsMeteo = array_shift($objsCopsMeteo);
        $strLis .= $this->getBalise(self::TAG_LI, 'Première entrée : '.$objCopsMeteo->getDateHeure());
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
        $strLis .= $this->getBalise(self::TAG_LI, 'Dernière entrée : '.$objCopsMeteo->getDateHeure());
        $strDate = $objCopsMeteo->getField(self::FIELD_DATE_METEO);
        $m = substr((string) $strDate, 4, 2);
        $d = substr((string) $strDate, 6);
        $y = substr((string) $strDate, 0, 4);
        $curDate = $y.'-'.$m.'-'.$d;
        $nextDate = DateUtils::getDateAjout($curDate, [1, 0, 0], self::FORMAT_DATE_YMD);

        $strBody = $this->getBalise(self::TAG_UL, $strLis);

        // On fabrique la liste des boutons pour action.
        $urlReference = '/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&subOnglet=home&date=';

        $linkClass = 'btn btn-primary';
        $btnGroup  = $this->getLink('Précédente', $urlReference.$prevDate, $linkClass);
        $btnGroup .= $this->getLink('Actuelle', $urlReference.$curDate, $linkClass);
        $btnGroup .= $this->getLink('Suivante', $urlReference.$nextDate, $linkClass);

        $strBody .= $this->getDiv($btnGroup, [self::ATTR_CLASS=>'btn-group btn-group-sm']);
    }

}
