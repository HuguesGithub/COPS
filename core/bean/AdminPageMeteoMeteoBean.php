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
        // Pour le moment, les données sont sur 2022 et 2023.
        // On applique donc les données de 2022 à 2030. Et 2023 à 2031.
        // Dde plus le format actuel est YYYYmmdd en base. Il devra être transformé en YYYY-mm-dd
        $strDate = DateUtils::getDateAjout($strDate, [0, 0, -8], self::FORMAT_DATE_YMD);
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


}
