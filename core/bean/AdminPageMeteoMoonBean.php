<?php
namespace core\bean;

use core\domain\CopsLuneClass;
use core\services\CopsLuneServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoMoonBean
 * @author Hugues
 * @since 1.23.04.27
 * @version v1.23.07.15
 */
class AdminPageMeteoMoonBean extends AdminPageMeteoBean
{
    /**
     * Affichage des données de la table wp_7_cops_lune pour une semaine donnée.
     * Par défaut, on affiche de la précente nouvelle lune jusqu'au thirdquarter correspondant.
     * @since v1.23.04.27
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
            $this->getCardOnglet('Données cycle lunaire', $this->getListContent()),
        ];
        return $this->getRender(self::WEB_PA_DEFAULT, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes[self::CST_SUBONGLET]  = self::CST_MOON;
        $strLink = HtmlUtils::getLink(self::LABEL_MOON, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);
    }

    /**
     * @since v1.23.06.18
     * @version v1.23.06.18
     */
    public function getListContent(): string
    {
        // On récupère le paramètre relatif à la date.
        $strDate = $this->initVar(self::CST_DATE, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        // On initialise le service dont on va avoir besoin.
        $objCopsLuneServices = new CopsLuneServices();
        // Objets Tableau Head et Body
        $objHeader = new TableauTHeadHtmlBean();
        $objBody = new TableauBodyHtmlBean();

        // On récupère en base la newmoon précédente
        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS][self::CST_ENDDATE] = $strDate;
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_TYPE_LUNE] = 'newmoon';
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsLune = $objCopsLuneServices->getLunes($attributes);
        $objCopsLune = !empty($objsCopsLune) ? array_shift($objsCopsLune) : new CopsLuneClass();
        $prevDate = $objCopsLune->getField(self::FIELD_DATE_LUNE);

        // On récupère les 3 dates suivantes
        $attributes = [];
        $attributes[self::SQL_WHERE_FILTERS][self::CST_STARTDATE] = $prevDate;
        // La lune précédente
        // 4 entrées par cycle
        // 5 cycles
        $attributes[self::SQL_LIMIT] = 1+4*5;
        $objsCopsLune = $objCopsLuneServices->getLunes($attributes);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRowHeader = new TableauRowHtmlBean();
        $objRowBody = new TableauRowHtmlBean();

        // Première cellule du Header "<"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_LEFT));
        $strPrevDate = DateUtils::getDateAjout($prevDate, [-1, 0, 0], self::FORMAT_DATE_YMD);
        $urlAttributes = [
            self::CST_ONGLET    => self::ONGLET_METEO,
            self::CST_SUBONGLET => self::CST_MOON,
            self::CST_DATE      => $strPrevDate,
        ];
        $link = HtmlUtils::getLink($label, UrlUtils::getAdminUrl($urlAttributes), '');
        $objTableauCell = new TableauCellHtmlBean($link, self::TAG_TH, self::STYLE_TEXT_CENTER);
        $objRowHeader->addCell($objTableauCell);

        // Première cellule du Body
        $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP);
        $objRowBody->addCell($objTableauCell);

        // On purge la première dont on ne veut pas visiblement.
        $objCopsLuneLast = array_pop($objsCopsLune);

        // Construction du Header et du Body
        $cpt = 1;
        while (!empty($objsCopsLune)) {
            $objCopsLune = array_shift($objsCopsLune);
            if ($cpt<=4) {
                $objTableauCell = new TableauCellHtmlBean(
                    $objCopsLune->getMoonStatus(),
                    self::TAG_TH,
                    self::STYLE_TEXT_CENTER
                );
                $objRowHeader->addCell($objTableauCell);
            }

            $objTableauCell = new TableauCellHtmlBean($objCopsLune->getDateHeure(), self::TAG_TD, self::CSS_TEXT_END);
            $objRowBody->addCell($objTableauCell);

            if ($cpt%4==0 && !empty($objsCopsLune)) {
                $objTableauCell = new TableauCellHtmlBean(self::CST_NBSP);
                $objRowBody->addCell($objTableauCell);
                $objBody->addRow($objRowBody);
                $objRowBody = new TableauRowHtmlBean();
                $objRowBody->addCell($objTableauCell);
            }
            $cpt++;
        }

        // Dernière cellule du Header ">"
        $label = HtmlUtils::getButton(HtmlUtils::getIcon(self::I_ANGLE_RIGHT));
        $nextDate = $objCopsLuneLast->getField(self::FIELD_DATE_LUNE);
        $strNextDate = DateUtils::getDateAjout($nextDate, [2, 0, 0], self::FORMAT_DATE_YMD);
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
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = self::LABEL_TABLE_LUNE;

        // On initialise le service dont on va avoir besoin.
        $objCopsLuneServices = new CopsLuneServices();

        $strLis = '';

        // On récupère la première date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsLune = $objCopsLuneServices->getLunes($attributes);
        $objCopsLune = array_shift($objsCopsLune);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_FIRST_ENTRY, $objCopsLune->getDateHeure()));

        // On récupère la dernière date
        $attributes = [];
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $attributes[self::SQL_LIMIT] = 1;
        $objsCopsLune = $objCopsLuneServices->getLunes($attributes);
        $objCopsLune = array_shift($objsCopsLune);
        $strLis .= $this->getBalise(self::TAG_LI, sprintf(self::DYN_LAST_ENTRY, $objCopsLune->getDateHeure()));

        $strBody = $this->getBalise(self::TAG_UL, $strLis);
    }

}
