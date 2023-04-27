<?php
namespace core\bean;

use core\utils\DateUtils;
use core\services\CopsLuneServices;

/**
 * AdminPageMeteoMoonBean
 * @author Hugues
 * @since 1.23.04.27
 * @version v1.23.04.30
 */
class AdminPageMeteoMoonBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_lune pour une semaine donnée.
     * Par défaut, on affiche de la précente nouvelle lune jusqu'au thirdquarter correspondant.
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getContentOnglet(): string
    {

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // On récupère le paramètre relatif à la date.
        $strDate = static::fromGet(self::CST_DATE);
        if ($strDate=='') {
            $strDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        }

        // On initialise le service dont on va avoir besoin.
        $objCopsLuneServices = new CopsLuneServices();

        // On récupère en base la newmoon précédente
        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS]['endDate'] = $strDate;
        $attributes[self::SQL_WHERE_FILTERS]['typeLune'] = 'newmoon';
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsLune = $objCopsLuneServices->getMoons($attributes);
        $objCopsLune = array_shift($objsCopsLune);
        $prevDate = $objCopsLune->getField(self::FIELD_DATE_LUNE);

        // On récupère les 3 dates suivantes
        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS]['startDate'] = $prevDate;
        $attributes[self::SQL_LIMIT] = 5;
        $objsCopsLune = $objCopsLuneServices->getMoons($attributes);

        $urlRoot = '/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&subOnglet=moon&date=';
        // Construction du Header et du Body
        $label = $this->getButton($this->getIcon(self::I_ANGLE_LEFT, 'feather icon-chevron-left'));
        $strPrevDate = DateUtils::getDateAjout($prevDate, [-1, 0, 0], self::FORMAT_DATE_YMD);
        $link = $this->getLink($label, $urlRoot.$strPrevDate, '');
        $strHeader = $this->getBalise(self::TAG_TH, $link, [self::ATTR_CLASS=>self::STYLE_TEXT_CENTER]);
        $strBody = $this->getBalise(self::TAG_TH, self::CST_NBSP);

        $objCopsLuneLast = array_pop($objsCopsLune);

        while (!empty($objsCopsLune)) {
            $objCopsLune = array_shift($objsCopsLune);
            $strHeader .= $this->getBalise(self::TAG_TH, $objCopsLune->getMoonStatus());
            $strBody .= $this->getBalise(self::TAG_TD, $objCopsLune->getDateHeure());
        }

        // On doit ajouter une dernière colonne pour passer à la semaine suivante
        $label = $this->getButton($this->getIcon(self::I_ANGLE_RIGHT, 'feather icon-chevron-right'));
        $nextDate = $objCopsLuneLast->getField(self::FIELD_DATE_LUNE);
        $strNextDate = DateUtils::getDateAjout($nextDate, [2, 0, 0], self::FORMAT_DATE_YMD);
        $link = $this->getLink($label, $urlRoot.$strNextDate, '');
        $strHeader .= $this->getBalise(self::TAG_TH, $link, [self::ATTR_CLASS=>self::STYLE_TEXT_CENTER]);
        // On doit ajouter une dernière colonne vide
        $strBody .= $this->getBalise(self::TAG_TH, self::CST_NBSP);

        $strHeader = $this->getBalise(self::TAG_TR, $strHeader);
        $strBody = $this->getBalise(self::TAG_TR, $strBody);

        // On retourne le tout.
        return $this->getRender(self::WEB_PA_METEO_MOON, [$strNavigation, $strHeader, $strBody]);
    }
}
