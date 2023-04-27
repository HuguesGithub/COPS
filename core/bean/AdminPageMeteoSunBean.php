<?php
namespace core\bean;

use core\services\CopsSoleilServices;

/**
 * AdminPageMeteoSunBean
 * @author Hugues
 * @since 1.23.04.26
 * @version 1.23.04.30
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
            $strDate = static::getCopsDate(self::FORMAT_DATE_YMD);
        }
        // J'ai une date au format YYYY-MM-DD
        // Je veux récupérer les données de la table soleil sur l'intervalle de la semaine.
        // J'ai donc besoin de la date de début de la semaine
        // J'ai aussi besoin de la date de fin de la semaine.
        $strDateStartWeek = $this->getDateStartWeek($strDate, self::FORMAT_DATE_YMD);
        $strDateEndWeek = $this->getDateAjout($strDateStartWeek, 6, self::FORMAT_DATE_YMD);

        // Maintenant je dois récupérer les données de la table wp_7_cops_soleil
        // sur l'intervalle défini juste au-dessus.
        $objCopsSoleilServices = new CopsSoleilServices();

        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS] = ['startDate'=>$strDateStartWeek, 'endDate'=>$strDateEndWeek];
        $objsCopsSoleil = $objCopsSoleilServices->getSoleilsIntervalle($attributes);

        // Construction du Header et du Body
        $strHeader = $this->getBalise(self::TAG_TH, self::CST_NBSP);
        $strBody = $this->getFirstColumn();

        while (!empty($objsCopsSoleil)) {
            $objCopsSoleil = array_shift($objsCopsSoleil);
            $strHeader .= $this->getBalise(self::TAG_TH, $objCopsSoleil->getField(self::FIELD_DATE_SOLEIL));
            $strBody .= $objCopsSoleil->getBean()->getColumn();
        }

        $strHeader = $this->getBalise(self::TAG_TR, $strHeader);
        $strBody = $this->getBalise(self::TAG_TR, $strBody, [self::ATTR_STYLE=>'height: 288px;']);

        return $this->getRender(self::WEB_PA_METEO_SUN, [$strNavigation, $strHeader, $strBody]);
    }

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

    /**
     * Retourne au format donné le premier jour de la semaine de la date passée.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getDateStartWeek(string $strDate, string $dateFormat): string
    {
        [$d, $m, $y] = $this->parseDate($strDate);
        $n = date('N', mktime(0, 0, 0, $m, $d, $y));
        return $this->getDateAjout($strDate, $n-1, $dateFormat);
    }

    /**
     * Retourne au format donné le date obtenue en ajoutant $nbJours à la date passée.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getDateAjout(string $strDate, int $nbJours, string $dateFormat): string
    {
        [$d, $m, $y] = $this->parseDate($strDate);
        return date($dateFormat, mktime(0, 0, 0, $m, $d+$nbJours, $y));
    }

    /**
     * Retourne un tableau [$d, $m, $y] de la date passée.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function parseDate(string $strDate): array
    {
        // On part du principe qu'on ne connait pas le format passé.

        if (strpos($strDate, '-')) {
            // Y a-t-il un - dans la chaine ?
            // YYYY-mm-dd
            // YY-mm-dd
            [$y, $m, $d] = explode('-', $strDate);
        } elseif (strpos($strDate, '/')) {
            // Y a-t-il un / dans la chaine ?
            // jj/mm/AAAA
            // jj/mm/AA
            [$d, $m, $y] = explode('/', $strDate);
        } elseif (strlen($strDate)==8) {
            // TODO : prendre les 4 premiers caractères et vérifier que c'est une année valide.
            // YYYYmmdd
            // jjmmAAAA
        } elseif (strlen($strDate)==6) {
            // TODO : prendre les 2 premiers caractères et vérifier que c'est une année valide.
            // YYmmdd
            // jjmmAA
        } else {
            // Si on ne connait pas le format, on utilise la date ingame
            $strDate = static::getCopsDate(self::FORMAT_DATE_YMD);
            [$y, $m, $d] = explode('-', $strDate);
        }

        return [$d, $m, $y];
    }
}
