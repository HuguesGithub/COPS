<?php
namespace core\bean;

use core\services\CopsSoleilServices;
use core\utils\DateUtils;

/**
 * AdminPageMeteoSunBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.04.30
 */
class AdminPageMeteoSunBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_soleil pour une semaine donnée.
     * Par défaut, la semaine affichée est la semaine comprenant la journée ingame.
     * @since v1.23.04.26
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
        // J'ai une date au format YYYY-MM-DD
        // Je veux récupérer les données de la table soleil sur l'intervalle de la semaine.
        // J'ai donc besoin de la date de début de la semaine
        // J'ai aussi besoin de la date de fin de la semaine.
        $strDateStartWeek = DateUtils::getDateStartWeek($strDate, self::FORMAT_DATE_YMD);
        $strDateEndWeek = DateUtils::getDateAjout($strDateStartWeek, [6, 0, 0], self::FORMAT_DATE_YMD);

        // Maintenant je dois récupérer les données de la table wp_7_cops_soleil
        // sur l'intervalle défini juste au-dessus.
        $objCopsSoleilServices = new CopsSoleilServices();

        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS] = ['startDate'=>$strDateStartWeek, 'endDate'=>$strDateEndWeek];
        $objsCopsSoleil = $objCopsSoleilServices->getSoleilsIntervalle($attributes);

        $urlRoot = '/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&subOnglet=sun&date=';
        // Construction du Header et du Body
        $strHeader  = $this->getBalise(self::TAG_TH, self::CST_NBSP);
        // On doit ajouter une 2è colonne pour passer à la semaine précédente
        $label = $this->getButton($this->getIcon(self::I_ANGLE_LEFT, 'feather icon-chevron-left'));
        $strPrevDate = DateUtils::getDateAjout($strDateStartWeek, [-7, 0, 0], self::FORMAT_DATE_YMD);
        $link = $this->getLink($label, $urlRoot.$strPrevDate, '');
        $strHeader .= $this->getBalise(self::TAG_TH, $link);
        $strBody = $this->getFirstColumn();
        // On doit ajouter une 2è colonne vide
        $strBody .= $this->getBalise(self::TAG_TH, self::CST_NBSP);

        while (!empty($objsCopsSoleil)) {
            $objCopsSoleil = array_shift($objsCopsSoleil);
            $strHeader .= $this->getBalise(self::TAG_TH, $objCopsSoleil->getHeaderDate());
            $strBody .= $objCopsSoleil->getBean()->getColumn();
        }

        // On doit ajouter une dernière colonne pour passer à la semaine suivante
        $label = $this->getButton($this->getIcon(self::I_ANGLE_RIGHT, 'feather icon-chevron-right'));
        $strNextDate = DateUtils::getDateAjout($strDateStartWeek, [7, 0, 0], self::FORMAT_DATE_YMD);
        $link = $this->getLink($label, $urlRoot.$strNextDate, '');
        $strHeader .= $this->getBalise(self::TAG_TH, $link);
        // On doit ajouter une dernière colonne vide
        $strBody .= $this->getBalise(self::TAG_TH, self::CST_NBSP);

        $strHeader = $this->getBalise(self::TAG_TR, $strHeader);
        $strBody = $this->getBalise(self::TAG_TR, $strBody, [self::ATTR_STYLE=>'height: 288px;']);

        return $this->getRender(self::WEB_PA_METEO_SUN, [$strNavigation, $strHeader, $strBody]);
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getFirstColumn(): string
    {
        $tdContent  = '<span style="position: absolute; top: 72px; left: 0px;">06:00</span>';
        $tdContent .= '<span style="position: absolute; top: 144px; left: 0px;">12:00</span>';
        $tdContent .= '<span style="position: absolute; top: 216px; left: 0px;">18:00</span>';

        $attributes = [
            self::ATTR_STYLE => 'position: relative; height: 100%; width: 100px;',
        ];

        return $this->getBalise(self::TAG_TH, $tdContent, $attributes);
    }

}
