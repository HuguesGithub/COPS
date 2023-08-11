<?php
namespace core\bean;

use core\services\CopsMeteoServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoMeteoBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.08.12
 */
class AdminPageMeteoMeteoBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_meteo pour une journée donnée.
     * Par défaut, la journée affichée est la journée ingame.
     * @since v1.23.04.26
     * @version v1.23.07.15
     */
    public function getContentOnglet(): string
    {
        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        $attributes = [
            $this->pageTitle,
            $this->pageSubTitle,
            $this->strBreadcrumbs,
            $strNavigation,
            $this->getCardOnglet('Données météo', $this->getListContent()),
        ];
        return $this->getRender(self::WEB_PA_DEFAULT, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes[self::CST_SUBONGLET]  = self::CST_WEATHER;
        $strLink = HtmlUtils::getLink(self::LABEL_WEATHER, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.06.18
     * @version v1.23.08.12
     */
    public function getListContent(): string
    {
        // On récupère le paramètre relatif à la date.
        $strDate = $this->initVar(self::CST_DATE, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        // Première cellule du Header "<"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_LEFT));
        $strPrevDate = DateUtils::getDateAjout($strDate, [-1, 0, 0], self::FORMAT_DATE_YMD);
        $urlAttributes = [
            self::CST_ONGLET    => self::ONGLET_METEO,
            self::CST_SUBONGLET => self::CST_WEATHER,
            self::CST_DATE      => $strPrevDate,
        ];
        $linkPrev = HtmlUtils::getLink($label, UrlUtils::getAdminUrl($urlAttributes), '');

        // Dernière cellule du Header ">"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_RIGHT));
        $strNextDate = DateUtils::getDateAjout($strDate, [1, 0, 0], self::FORMAT_DATE_YMD);
        $urlAttributes[self::CST_DATE] = $strNextDate;
        $linkNext = HtmlUtils::getLink($label, UrlUtils::getAdminUrl($urlAttributes));

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean(
            $linkPrev,
            self::TAG_TH,
            '',
            [self::CST_ROWSPAN=>2, self::ATTR_STYLE=>'width:40px;']
        );
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(
            self::LABEL_CONDITIONS,
            self::TAG_TH,
            self::STYLE_TEXT_CENTER,
            [self::CST_COLSPAN=>3]
        );
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(
            self::LABEL_CONFORT,
            self::TAG_TH,
            self::STYLE_TEXT_CENTER,
            [self::CST_COLSPAN=>3]
        );
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH, '', [self::CST_COLSPAN=>2]);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(
            $linkNext,
            self::TAG_TH,
            '',
            [self::CST_ROWSPAN=>2, self::ATTR_STYLE=>'width:40px;']
        );
        $objRow->addCell($objTableauCell);
        $objHeader = new TableauTHeadHtmlBean();
        $objHeader->addRow($objRow);

        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_HEURE, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_TEMP, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_WEATHER, self::TAG_TH);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_WIND, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_HUMIDITY, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_BAROMETER, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean(self::LABEL_VISIBILITY, self::TAG_TH, self::CSS_COL_1);
        $objRow->addCell($objTableauCell);
        $objHeader->addRow($objRow);

        $objCopsMeteoServices = new CopsMeteoServices();

        // On récupère le paramètre relatif à la date.
        $strDate = SessionUtils::fromGet(self::CST_DATE);
        if ($strDate=='') {
            $strDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        }
        // TODO : A supprimer. Mis en place pour les besoins du dèv le temps de tester.
        [$y, $m, $d] = explode('-', $strDate);
        $strDate = ($y-8).$m.$d;
        // TODO : Fin suppression.

        // Récupération des données de la journée
        $sqlAllAttributes = [
            self::FIELD_DATE_METEO => $strDate,
            self::SQL_ORDER_BY => self::FIELD_HEURE_METEO
        ];
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($sqlAllAttributes);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        while (!empty($objsCopsMeteo)) {
            $objCopsMeteo = array_shift($objsCopsMeteo);
            $objBody->addRow($objCopsMeteo->getBean()->getTableRow());
        }

        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->setSize('sm');
        $objTable->setStripped();
        $objTable->setClass('m-0');
        $objTable->setAria('describedby', 'Météo du jour');
        $objTable->setTHead($objHeader);
        $objTable->setBody($objBody);

        return $objTable->getBean();
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.07.22
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
        $strLis .= HtmlUtils::getBalise(self::TAG_LI, sprintf(self::DYN_FIRST_ENTRY, $objCopsMeteo->getDateHeure()));
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
        $strLis .= HtmlUtils::getBalise(self::TAG_LI, sprintf(self::DYN_LAST_ENTRY, $objCopsMeteo->getDateHeure()));
        $strDate = $objCopsMeteo->getField(self::FIELD_DATE_METEO);
        $m = substr((string) $strDate, 4, 2);
        $d = substr((string) $strDate, 6);
        $y = substr((string) $strDate, 0, 4);
        $curDate = $y.'-'.$m.'-'.$d;
        $nextDate = DateUtils::getDateAjout($curDate, [1, 0, 0], self::FORMAT_DATE_YMD);

        $strBody = HtmlUtils::getBalise(self::TAG_UL, $strLis);

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
