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
 * @version v1.23.07.29
 */
class AdminPageEquipmentWeaponBean extends AdminPageEquipmentBean
{

    /**
     * @since v1.23.07.09
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_WEAPONS, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
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
     * @version v1.23.07.29
     */
    public function getEditContent(): string
    {
        // Définition du service
        $objCopsEquipmentServices = new CopsEquipmentServices();
        // Récupération des données
        $objCopsWeapon = $objCopsEquipmentServices->getWeapon($this->id);

        $urlTemplate = self::WEB_PA_EQPT_WEAPON_EDIT;

        // Récupération des éléments nécessaires à l'affichage de l'écran d'édition
        $attributes = $objCopsWeapon->getBean()->getEditInterfaceAttributes();

        // Gestion du lien d'annulation
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $attributes[] = $urlAnnulation;
        //////////////////////////////////////////////////////////

        return $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////
    }

    /**
     * @since v1.23.07.09
     * @version v1.23.07.29
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
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objsCopsWeapon,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = CopsEquipmentWeaponBean::getTableHeader($queryArg);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody);
        
        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody, null, 'Liste des armes');

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
