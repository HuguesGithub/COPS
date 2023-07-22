<?php
namespace core\bean;

use core\services\CopsEquipmentServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageEquipmentCarBean
 * @author Hugues
 * @since v1.23.07.19
 * @version v1.23.07.22
 */
class AdminPageEquipmentCarBean extends AdminPageEquipmentBean
{

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_CARS, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getCard(): string
    {
        if ($this->action==self::CST_WRITE) {
            return $this->getEditContent();
        } else {
            return $this->getListContent();
        }
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getEditContent(): string
    {
        $urlTemplate = self::WEB_PA_EQPT_CAR_EDIT;

        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // Récupération des données
        $objCopsCar = $objCopsEquipmentServices->getVehicle($this->id);
        // Récupération des éléments nécessaires à l'affichage de l'écran d'édition
        $attributes = $objCopsCar->getEditInterfaceAttributes();

        // Gestion du lien d'annulation
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $attributes[] = $urlAnnulation;
        //////////////////////////////////////////////////////////

        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getListContent(): string
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $filterCateg = $this->initVar('filterCateg', self::SQL_JOKER_SEARCH);
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_VEH_LABEL);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // TODO : gestion des filtres

        // Récupération des données
        $attributes = [
            self::SQL_WHERE_FILTERS => [self::FIELD_VEH_CATEG => $filterCateg],
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objsCopsCar = $objCopsEquipmentServices->getVehicles($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
            'filterCateg' => $filterCateg,
        ];
        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objsCopsCar,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean('Nom', self::TAG_TH, 'col-3');
        $queryArg[self::SQL_ORDER_BY] = self::FIELD_VEH_LABEL;
        $objTableauCell->ableSort($queryArg);
        $objRow->addCell($objTableauCell);
        $objRow->addCell(new TableauCellHtmlBean('Catégorie', self::TAG_TH, self::CSS_COL));
        $tag = HtmlUtils::getBalise('abbr', 'VM', [self::ATTR_TITLE=>'Vitesse Maximale']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = HtmlUtils::getBalise('abbr', 'Acc', [self::ATTR_TITLE=>'Accélération']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = HtmlUtils::getBalise('abbr', 'Pass', [self::ATTR_TITLE=>'Nombre d\'occupants']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $objRow->addCell(new TableauCellHtmlBean('Prix', self::TAG_TH, self::CSS_COL));
        $tag = HtmlUtils::getBalise('abbr', 'PS', [self::ATTR_TITLE=>'Points de Structure']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $objRow->addCell(new TableauCellHtmlBean('Spécial', self::TAG_TH, self::CSS_COL));
        $objHeader = new TableauTHeadHtmlBean();
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
        $rowContent = $this->getCategorieFilter($filterCateg);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);

        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->setSize('sm');
        $objTable->setStripped();
        $objTable->setClass('m-0 sortableTable text-center');
        $objTable->setAria('describedby', 'Liste des véhicules');
        $objTable->setTHead($objHeader);
        $objTable->setBody($objBody);
        $objTable->setTFoot($objFooter);

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
            self::CST_ACTION => self::CST_WRITE,
        ];

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des véhicules',
            // La liste des éléments
            $objTable->getBean(),
            // Le lien pour créer un nouvel événement.
            UrlUtils::getAdminUrl($urlElements),
            // Libellé bouton
            'Nouveau véhicule',
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function dealWithWriteAction(): void
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // Récupération des données
        $objCopsCar = $objCopsEquipmentServices->getVehicle($this->id);

        // Le libellé, on stripslashes pour protéger
        $nomVehicule = stripslashes(SessionUtils::fromPost(self::FIELD_VEH_LABEL, false));
        $objCopsCar->setField(self::FIELD_VEH_LABEL, $nomVehicule);

        $objCopsCar->setField(self::FIELD_VEH_CATEG, SessionUtils::fromPost(self::FIELD_VEH_CATEG));
        $objCopsCar->setField(self::FIELD_VEH_SS_CATEG, SessionUtils::fromPost(self::FIELD_VEH_SS_CATEG));
        $objCopsCar->setField(self::FIELD_VEH_PLACES, SessionUtils::fromPost(self::FIELD_VEH_PLACES));
        $objCopsCar->setField(self::FIELD_VEH_SPEED, SessionUtils::fromPost(self::FIELD_VEH_SPEED));
        $objCopsCar->setField(self::FIELD_VEH_ACCELERE, SessionUtils::fromPost(self::FIELD_VEH_ACCELERE));
        $objCopsCar->setField(self::FIELD_VEH_PS, SessionUtils::fromPost(self::FIELD_VEH_PS));
        $objCopsCar->setField(self::FIELD_VEH_AUTONOMIE, SessionUtils::fromPost(self::FIELD_VEH_AUTONOMIE, false));
        $objCopsCar->setField(self::FIELD_VEH_FUEL, SessionUtils::fromPost(self::FIELD_VEH_FUEL));
        $objCopsCar->setField(self::FIELD_VEH_OPTIONS, SessionUtils::fromPost(self::FIELD_VEH_OPTIONS));
        $objCopsCar->setField(self::FIELD_VEH_PRICE, SessionUtils::fromPost(self::FIELD_VEH_PRICE));
        $objCopsCar->setField(self::FIELD_VEH_YEAR, SessionUtils::fromPost(self::FIELD_VEH_YEAR));
        $objCopsCar->setField(self::FIELD_VEH_REFERENCE, SessionUtils::fromPost(self::FIELD_VEH_REFERENCE));

        if ($objCopsCar->checkFields()) {
            if ($this->id==0) {
                $objCopsEquipmentServices->insertVehicle($objCopsCar);
                $this->id = $objCopsCar->getField(self::FIELD_ID);
            } else {
                $objCopsEquipmentServices->updateVehicle($objCopsCar);
            }
        } else {
            // TODO : Gestion de l'erreur ?
        }
    }

    /**
     * @since v1.23.07.22
     * @version v1.23.07.22
     */
    public function getCategorieFilter($selectedValue=''): string
    {
        $arrTypeVehicle = [
            'aquatique' => 'Amphibie',
            'moto'      => 'Moto',
            'utilitaire'=> 'Utilitaire',
            'voiture'   => 'Voiture',
        ];

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
        ];
        $strLabel = 'Catégorie';
        $strLis = '';
        foreach ($arrTypeVehicle as $value => $label) {
            $urlElements['filterCateg'] = $value;
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($label, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
            if ($value==$selectedValue) {
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
            self::ATTR_STYLE => 'position: absolute; margin-top: -17px;',
        ];
        return HtmlUtils::getDiv($strButton.$ul, $divAttributes);
    }
}
