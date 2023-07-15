<?php
namespace core\bean;

use core\services\CopsEquipmentServices;
use core\services\CopsIndexServices;
use core\services\CopsSkillServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageEquipmentWeaponBean
 * @author Hugues
 * @since v1.23.07.09
 * @version v1.23.07.15
 */
class AdminPageEquipmentWeaponBean extends AdminPageEquipmentBean
{
    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function getContentOnglet(): string
    {
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination, l'action ou l'id de l'événement concerné
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        $this->action  = $this->initVar(self::CST_ACTION);
        $this->id      = $this->initVar(self::FIELD_ID, 0);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Si writeAction est défini, par formulaire pour Write, par url pour Delete
        $writeAction = static::initVar(self::CST_WRITE_ACTION);
        if ($writeAction==self::CST_WRITE) {
            $this->dealWithWriteAction();
        }
        /////////////////////////////////////////

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        /////////////////////////////////////////
        $this->buildBreadCrumbs();
        
        $strCards = $this->getCard();

        //
        $attributes = [
            $this->pageTitle,
            $this->pageSubTitle,
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_DEFAULT, $attributes);
    }

    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_WEAPONS, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);
    }

    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
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
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function getEditContent(): string
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // Récupération des données
        $objCopsWeapon = $objCopsEquipmentServices->getWeapon($this->id);

        // Gestion du lien d'annulation
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Récupération de la référence
        $objTome = $objCopsWeapon->getTome();
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Récupération du type d'arme
        // On va construire une liste déroulante.
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_TYPE_ARME,
            self::FIELD_ID   => self::FIELD_TYPE_ARME,
        ];
        $arrTypeArme = [
            'contact' => 'Arme de contact',
            'poing' => 'Arme de poing',
            'epaule' => 'Arme d\'épaule',
            'lourde' => 'Arme lourde',
        ];
        $selTypeArmeValue = $objCopsWeapon->getField(self::FIELD_TYPE_ARME);
        $blnIsContact = $selTypeArmeValue=='contact';
        $strContentSel = '';
        foreach($arrTypeArme as $value => $label) {
            $strContentSel .= HtmlUtils::getOption($label, $value, $value==$selTypeArmeValue);
        }
        $selTypeArme = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Récupération de la compétence nécessaire
        // On va construire une liste déroulante.
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_SKILL_USE,
            self::FIELD_ID   => self::FIELD_SKILL_USE,
        ];
        $objSkillServices = new CopsSkillServices();
        $objsSkillSpec = $objSkillServices->getSpecSkills();
        $selSkillUseValue = $objCopsWeapon->getField(self::FIELD_SKILL_USE);
        $strContentSel = HtmlUtils::getOption('', '0', 0==$selSkillUseValue);
        while (!empty($objsSkillSpec)) {
            $objSkillSpec = array_shift($objsSkillSpec);
            $label = $objSkillSpec->getField(self::FIELD_SPEC_NAME);
            $value = $objSkillSpec->getField(self::FIELD_ID);
            $strContentSel .= HtmlUtils::getOption($label, $value, $value==$selSkillUseValue);
        }
        $selSkillUse = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        //////////////////////////////////////////////////////////

        $urlTemplate = self::WEB_PA_EQPT_WEAPON_EDIT;
        $attributes = [
            // Id
            $objCopsWeapon->getField(self::FIELD_ID),
            // Nom
            $objCopsWeapon->getField(self::FIELD_NOM_ARME),
            // Référence
            $objTome->getField(self::FIELD_ABR_IDX_TOME),
            // Type d'arme (liste)
            $selTypeArme,
            // Compétence (liste)
            $selSkillUse,
            // Précision
            $objCopsWeapon->getField(self::FIELD_SCORE_PR),
            // Puissance
            $objCopsWeapon->getField(self::FIELD_SCORE_PU),
            // Force d'arrêt
            $objCopsWeapon->getField(self::FIELD_SCORE_FA),
            // Dissimulation
            $objCopsWeapon->getField(self::FIELD_SCORE_DIS),
            // Portée
            $blnIsContact ? '' : $objCopsWeapon->getField(self::FIELD_PORTEE),
            // Prix
            $objCopsWeapon->getField(self::FIELD_PRIX),
            // Valeur de Rafale Courte
            $blnIsContact ? '' : $objCopsWeapon->getField(self::FIELD_SCORE_VRC),
            // Cadence de Tir
            $blnIsContact ? '' : $objCopsWeapon->getField(self::FIELD_SCORE_CT),
            // Valeur de Couverture
            $blnIsContact ? '' : $objCopsWeapon->getField(self::FIELD_SCORE_VC),
            // Munitions
            $blnIsContact ? '' : $objCopsWeapon->getField(self::FIELD_MUNITIONS),
            ////////////////////////////////////////////////////
            // Url d'annulation de l'édition
            $urlAnnulation,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function getListContent(): string
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // On récupère les données éventuelles sur les filtres et les tris
        // $filterCateg = $this->initVar('filterCateg', self::SQL_JOKER_SEARCH);
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_NOM_ARME);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // TODO : gestion des filtres

        // Récupération des données
        $attributes = [
        //*             self::SQL_WHERE_FILTERS => [self::FIELD_CATEG_ID => $filterCateg],
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objsCopsWeapon = $objCopsEquipmentServices->getWeapons($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
            //'filterCateg' => $filterCateg,
        ];
        $objPagination->setData([
            'objs' => $objsCopsWeapon,
            'curPage' => $this->curPage,
            'queryArg' => $queryArg,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean('Nom', self::TAG_TH, 'col-3');
        $queryArg[self::SQL_ORDER_BY] = self::FIELD_NOM_ARME;
        $objTableauCell->ableSort($queryArg);
        $objRow->addCell($objTableauCell);
        $tag = $this->getBalise('abbr', 'PR', [self::ATTR_TITLE=>'Précision']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'PU', [self::ATTR_TITLE=>'Puissance']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'FA', [self::ATTR_TITLE=>'Force d\'arrêt']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'VRC', [self::ATTR_TITLE=>'Valeur de Rafale Courte']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $objRow->addCell(new TableauCellHtmlBean('Portée', self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'VC', [self::ATTR_TITLE=>'Valeur de Couverture']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'CT', [self::ATTR_TITLE=>'Cadence de Tir']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'Mun', [self::ATTR_TITLE=>'Munitions']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $tag = $this->getBalise('abbr', 'Dis', [self::ATTR_TITLE=>'Dissimulation']);
        $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        $objRow->addCell(new TableauCellHtmlBean('Prix', self::TAG_TH, self::CSS_COL));
        $objHeader = new TableauTHeadHtmlBean();
        $objHeader->addRow($objRow);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody);
        
        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->setSize('sm');
        $objTable->setStripped();
        $objTable->setClass('m-0 sortableTable text-center');
        $objTable->setAria('describedby', 'Liste des armes');
        $objTable->setTHead($objHeader);
        $objTable->setBody($objBody);

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::CST_ACTION => self::CST_WRITE,
        ];

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des armes',
            // La liste des éléments
            $objTable->getBean(),
            // Le lien pour créer un nouvel événement.
            UrlUtils::getAdminUrl($urlElements),
            // Libellé bouton
            'Nouvelle arme',
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function dealWithWriteAction(): void
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        $objIndexServices = new CopsIndexServices();
        // Récupération des données
        $objCopsWeapon = $objCopsEquipmentServices->getWeapon($this->id);

        // Le libellé, on stripslashes pour protéger
        $nomArme = stripslashes(SessionUtils::fromPost(self::FIELD_NOM_ARME, false));
        $objCopsWeapon->setField(self::FIELD_NOM_ARME, $nomArme);
        $abbrTome = SessionUtils::fromPost(self::FIELD_TOME_IDX_ID);
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ABR_IDX_TOME => $abbrTome
            ],
        ];
        $objsIndexTome = $objIndexServices->getIndexTomes($attributes);
        if (empty($objsIndexTome)) {
            $objCopsWeapon->setField(self::FIELD_TOME_IDX_ID, '');
        } else {
            $objIndexTome = array_shift($objsIndexTome);
            $objCopsWeapon->setField(self::FIELD_TOME_IDX_ID, $objIndexTome->getField(self::FIELD_ID_IDX_TOME));
        }

        // Type d'arme et compétence utilisée
        $typeArme = SessionUtils::fromPost(self::FIELD_TYPE_ARME);
        $objCopsWeapon->setField(self::FIELD_TYPE_ARME, $typeArme);
        $objCopsWeapon->setField(self::FIELD_SKILL_USE, SessionUtils::fromPost(self::FIELD_SKILL_USE));

        // Précision, Puissance et Force d'arrêt. 3 données obligatoires
        $objCopsWeapon->setField(self::FIELD_SCORE_PR, SessionUtils::fromPost(self::FIELD_SCORE_PR));
        $objCopsWeapon->setField(self::FIELD_SCORE_PU, SessionUtils::fromPost(self::FIELD_SCORE_PU));
        $objCopsWeapon->setField(self::FIELD_SCORE_FA, SessionUtils::fromPost(self::FIELD_SCORE_FA));

        // Dissimulation et Prix, obligatoires
        $objCopsWeapon->setField(self::FIELD_SCORE_DIS, SessionUtils::fromPost(self::FIELD_SCORE_DIS));
        $objCopsWeapon->setField(self::FIELD_PRIX, SessionUtils::fromPost(self::FIELD_PRIX));

        if ($typeArme!='contact') {
            // Données pour armes à distance : Portée
            $objCopsWeapon->setField(self::FIELD_PORTEE, SessionUtils::fromPost(self::FIELD_PORTEE));
            // Valeur de Rafale Courte et Cadence de Tir
            $objCopsWeapon->setField(self::FIELD_SCORE_VRC, SessionUtils::fromPost(self::FIELD_SCORE_VRC));
            $objCopsWeapon->setField(self::FIELD_SCORE_CT, SessionUtils::fromPost(self::FIELD_SCORE_CT));
            // Valeur de Couverture et Munitions
            $objCopsWeapon->setField(self::FIELD_SCORE_VC, SessionUtils::fromPost(self::FIELD_SCORE_VC));
            $objCopsWeapon->setField(self::FIELD_MUNITIONS, SessionUtils::fromPost(self::FIELD_MUNITIONS));
        }

        if ($objCopsWeapon->checkFields()) {
            if ($this->id==0) {
                $objCopsEquipmentServices->insertWeapon($objCopsWeapon);
                $this->id = $objCopsWeapon->getField(self::FIELD_ID);
            } else {
                $objCopsEquipmentServices->updateWeapon($objCopsWeapon);
            }
        } else {
            // TODO : Gestion de l'erreur ?
        }
    }

}
