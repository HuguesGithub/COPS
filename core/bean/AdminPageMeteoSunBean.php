<?php
namespace core\bean;

use core\services\CopsSoleilServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoSunBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.06.18
 */
class AdminPageMeteoSunBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_soleil pour une semaine donnée.
     * Par défaut, la semaine affichée est la semaine comprenant la journée ingame.
     * @since v1.23.04.26
     * @version v1.23.06.18
     */
    public function getContentOnglet(): string
    {
        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_METEO,
            self::CST_SUBONGLET => self::CST_SUN,
        ];
        $strLink = HtmlUtils::getLink(self::LABEL_SUN, UrlUtils::getAdminUrl($urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);

        $attributes = [
            $this->strBreadcrumbs,
            $strNavigation,
            $this->getCardOnglet('Horaires journée', $this->getListContent()),
        ];
        return $this->getRender(self::WEB_PA_METEO, $attributes);
    }

    /**
     * @since v1.23.06.18
     * @version v1.23.06.18
     */
    public function getListContent(): string
    {

        // On récupère le paramètre relatif à la date.
        $strDate = $this->initVar(self::CST_DATE, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

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
        $attributes[self::SQL_WHERE_FILTERS] = [
            self::CST_STARTDATE => $strDateStartWeek,
            self::CST_ENDDATE   => $strDateEndWeek
        ];
        $objsCopsSoleil = $objCopsSoleilServices->getSoleilsIntervalle($attributes);

        // Objets Tableau Head et Body
        $objHeader = new TableauTHeadHtmlBean();
        $objBody = new TableauBodyHtmlBean();

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRowHeader = new TableauRowHtmlBean();
        $objRowBody = new TableauRowHtmlBean();
        $objRowBody->addStyle('height: 288px;');

        // Première cellule du Header "<"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_LEFT));
        $strPrevDate = DateUtils::getDateAjout($strDateStartWeek, [-7, 0, 0], self::FORMAT_DATE_YMD);
        $urlAttributes = [
            self::CST_ONGLET    => self::ONGLET_METEO,
            self::CST_SUBONGLET => self::CST_SUN,
            self::CST_DATE      => $strPrevDate,
        ];
        $link = HtmlUtils::getLink($label, UrlUtils::getAdminUrl($urlAttributes));
        $objTableauCell = new TableauCellHtmlBean($link, self::TAG_TH, self::STYLE_TEXT_CENTER);
        $objRowHeader->addCell($objTableauCell);

        // Première cellule du Body

        $objTableauCell = new TableauCellHtmlBean(
            $this->getFirstColumn(),
            self::TAG_TH,
            '',
            [self::ATTR_STYLE => 'position: relative; height: 100%; width: 100px;']
        );
        $objRowBody->addCell($objTableauCell);

        // Construction du Header et du Body
        while (!empty($objsCopsSoleil)) {
            $objCopsSoleil = array_shift($objsCopsSoleil);

            $objTableauCell = new TableauCellHtmlBean(
                $objCopsSoleil->getHeaderDate(),
                self::TAG_TH,
                self::STYLE_TEXT_CENTER
            );
            $objRowHeader->addCell($objTableauCell);

            $objTableauCell = new TableauCellHtmlBean(
                $objCopsSoleil->getBean()->getColumn(),
                self::TAG_TD,
                '',
                [self::ATTR_STYLE => 'position: relative; height: 100%;']
            );
            $objRowBody->addCell($objTableauCell);
        }

        // Dernière cellule du Header ">"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_RIGHT));
        $strNextDate = DateUtils::getDateAjout($strDateStartWeek, [7, 0, 0], self::FORMAT_DATE_YMD);
        $urlAttributes[self::CST_DATE] = $strNextDate;
        $link = HtmlUtils::getLink($label, UrlUtils::getAdminUrl($urlAttributes));
        $objTableauCell = new TableauCellHtmlBean($link, self::TAG_TH, self::STYLE_TEXT_CENTER);
        $objRowHeader->addCell($objTableauCell);

        // Dernière cellule du Body
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP);
        $objRowBody->addCell($objTableauCell);

        // On ajoute les row au Header et au Body
        $objHeader->addRow($objRowHeader);
        $objBody->addRow($objRowBody);

        // On ajoute le Header et le Body à la Table
        $objTable = new TableauHtmlBean();
        $objTable->setSize('sm');
        $objTable->setStripped();
        $objTable->setClass('m-0');
        $objTable->setAria('describedby', 'Cycle lune');
        $objTable->setTHead($objHeader);
        $objTable->setBody($objBody);

        return $objTable->getBean();
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.06.18
     */
    public function getFirstColumn(): string
    {
        // TODO : Améliorer l'affichage de la première colonne.
        $tdContent  = '<span style="position: absolute; top: 60px; right: 0px;">06:00</span>';
        $tdContent .= '<span style="position: absolute; top: 132px; right: 0px;">12:00</span>';
        $tdContent .= '<span style="position: absolute; top: 204px; right: 0px;">18:00</span>';
        return $tdContent;
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = self::LABEL_TABLE_SOLEIL;

        // On initialise le service dont on va avoir besoin.
        $objCopsSoleilServices = new CopsSoleilServices();

        $strLis = '';

        // On récupère la première date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsSoleil = $objCopsSoleilServices->getSoleils($attributes);
        $objCopsSoleil = array_shift($objsCopsSoleil);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_FIRST_ENTRY, $objCopsSoleil->getDateHeure()));

        // On récupère la dernière date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsSoleil = $objCopsSoleilServices->getSoleils($attributes);
        $objCopsSoleil = array_shift($objsCopsSoleil);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_LAST_ENTRY, $objCopsSoleil->getDateHeure()));

        $strBody = $this->getBalise(self::TAG_UL, $strLis);
    }

}
