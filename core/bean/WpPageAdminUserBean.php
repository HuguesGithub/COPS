<?php
namespace core\bean;

use core\domain\CopsPlayerClass;
use core\enum\SectionEnum;
use core\services\CopsPlayerServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminUserBean
 * @author Hugues
 * @since v1.23.06.26
 * @version v1.23.08.12
 */
class WpPageAdminUserBean extends WpPageAdminBean
{
    
    /**
     * @since v1.23.06.26
     * @version v1.23.07.02
     */
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Ajout du BreadCrumb
        $this->urlAttributes[self::CST_ONGLET] = self::ONGLET_USERS;
        $buttonContent = HtmlUtils::getLink(
            'COPS',
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton(
            $buttonContent,
            [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK_DISABLED]
        );
        /////////////////////////////////////////
    }
     
    /**
     * @since v1.23.06.26
     * @version v1.23.07.09
     */
    public static function getStaticWpPageBean(): WpPageAdminUserBean
    { return new WpPageAdminUserBean(); }

    /**
     * @since v1.23.06.26
     * @version v1.23.08.12
     */
    public function getOngletContent(): string
    {
        $objCopsPlayerServices = new CopsPlayerServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $filterGrade = $this->initVar('filterGrade', self::SQL_JOKER_SEARCH);
        $filterSection = $this->initVar('filterSection', self::SQL_JOKER_SEARCH);
        $curPage = $this->initVar(self::CST_CURPAGE, 1);
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_MATRICULE);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = [
            self::FIELD_GRADE => $filterGrade,
            self::FIELD_SECTION => $filterSection,
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];

        // On récupère les données éventuelles sur les filtres et les tris
        $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = [
            self::WP_PAGE => self::PAGE_ADMIN,
            self::CST_ONGLET => self::ONGLET_USERS,
        ];
        $objPagination->setData([
            'objs' => $objsCopsPlayer,
            'curPage' => $curPage,
            'queryArg' => $queryArg,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = new TableauTHeadHtmlBean();
        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean('Matricule', self::TAG_TH);
        $queryArg[self::SQL_ORDER_BY] = self::FIELD_MATRICULE;
        $objTableauCell->ableSort($queryArg);
        $objTableauCell->setPublic(true);
        $objRow->addCell($objTableauCell);
        $objTableauCell = new TableauCellHtmlBean('Nom', self::TAG_TH);
        $queryArg[self::SQL_ORDER_BY] = self::FIELD_NOM;
        $objTableauCell->ableSort($queryArg);
        $objTableauCell->setPublic(true);
        $objRow->addCell($objTableauCell);
        $objRow->addCell(new TableauCellHtmlBean('Prénom', self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean('Surnom', self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean('Grade', self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean('Section', self::TAG_TH));
        $objHeader->addRow($objRow);
        
        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody);
        
        //////////////////////////////////////////////////////
        // Définition du Footer du tableau
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean($this->getGradeFilter($filterGrade), self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean($this->getSectionFilter($filterSection), self::TAG_TH));
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);

        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->setSize('sm');
        $objTable->setStripped();
        $objTable->setClass('m-0 sortableTable');
        $objTable->setAria('describedby', 'Liste COPS');
        $objTable->setTHead($objHeader);
        $objTable->setBody($objBody);
        $objTable->setTFoot($objFooter);
        
        $urlTemplate = self::WEB_PA_PROFILE_USER_LIST;
        $attributes = [
            // Le titre de la page
            'Liste des COPS',
            // La liste des événements
            $objTable->getBean(),
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.26
     * @version v1.23.07.02
     */
    public function getGradeFilter($selectedValue=''): string
    {
        $urlElements = [
            self::WP_PAGE => self::PAGE_ADMIN,
            self::CST_ONGLET => self::ONGLET_USERS,
        ];
        $arrGrade = [
            'capitaine' => 'Capitaine',
            'lieutenant' => 'Lieutenant',
            'detective' => 'Détective',
        ];
        $strLabel = 'Grade';
        $strLis = '';
        foreach ($arrGrade as $key => $label) {
            $urlElements['filterGrade'] = $key;
            $href = UrlUtils::getPublicUrl($urlElements);
            $liContent = HtmlUtils::getLink($label, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
    
            if ($key==$selectedValue) {
                $strLabel = $label;
            }
        }
        $ulAttributes = [
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'height: 200px; overflow: auto;',
        ];
        $ul = HtmlUtils::getBalise(self::TAG_UL, $strLis, $ulAttributes);

        $btnAttributes = [
            self::ATTR_CLASS => ' btn_outline btn-sm dropdown-toggle',
            'aria-expanded' => false,
            'data-bs-toggle' => 'dropdown',
        ];
        $strButton = HtmlUtils::getButton($strLabel, $btnAttributes);

        $divAttributes = [
            self::ATTR_CLASS => 'dropdown dropup',
            self::ATTR_STYLE => 'position: absolute;',
        ];
        return HtmlUtils::getDiv($strButton.$ul, $divAttributes);
    }

    /**
     * @since v1.23.06.26
     * @version v1.23.07.02
     */
    public function getSectionFilter($selectedValue=''): string
    {
        $urlElements = [
            self::WP_PAGE => self::PAGE_ADMIN,
            self::CST_ONGLET => self::ONGLET_USERS,
        ];
        $strLabel = 'Section';
        $strLis = $this->getSectionMenu($urlElements, $strLabel, $selectedValue);

        $ulAttributes = [
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'height: 200px; overflow: auto;',
        ];
        $ul = HtmlUtils::getBalise(self::TAG_UL, $strLis, $ulAttributes);

        $btnAttributes = [
            self::ATTR_CLASS => ' btn_outline btn-sm dropdown-toggle',
            'aria-expanded' => false,
            'data-bs-toggle' => 'dropdown',
        ];
        $strButton = HtmlUtils::getButton($strLabel, $btnAttributes);

        $divAttributes = [
            self::ATTR_CLASS => 'dropdown dropup',
            self::ATTR_STYLE => 'position: absolute;',
        ];
        return HtmlUtils::getDiv($strButton.$ul, $divAttributes);
    }


    public function getSectionMenu(array $urlElements, string &$selectedLabel, string $selectedValue=''): string
    {
        $strLis = '';

        foreach (SectionEnum::cases() as $case) {
            $urlElements['filterSection'] = $case->value;
            $href = UrlUtils::getPublicUrl($urlElements);
            $liContent = HtmlUtils::getLink($case->label(), $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);

            if ($case->value==$selectedValue) {
                $selectedLabel = $case->label();
            }
        }

        return $strLis;
    }

}
