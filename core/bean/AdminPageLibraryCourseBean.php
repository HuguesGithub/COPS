<?php
namespace core\bean;

use core\services\CopsStageServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageLibraryCourseBean
 * @author Hugues
 * @since v1.23.08.05
 */
class AdminPageLibraryCourseBean extends AdminPageLibraryBean
{

    /**
     * @since v1.23.08.05
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_COURSES, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.08.05
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
     * @since v1.23.08.05
     */
    public function getEditContent(): string
    {
        // Définition du service
        $objCopsStageServices = new CopsStageServices();
        // Récupération des données
        $objStage = $objCopsStageServices->getStage($this->id);

        $urlTemplate = self::WEB_PA_LIB_COURSE_EDIT;

        // Récupération des éléments nécessaires à l'affichage de l'écran d'édition
        $attributes = $objStage->getBean()->getEditInterfaceAttributes();

        // Gestion du lien d'annulation
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_COURSE,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $attributes[] = $urlAnnulation;
        //////////////////////////////////////////////////////////

        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function getListContent(): string
    {
        // Définition du service
        $objCopsStageServices = new CopsStageServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_STAGE_LIBELLE);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = [
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objsStage = $objCopsStageServices->getStages($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_STAGE,
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objsStage,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = CopsStageBean::getTableHeader($queryArg);
        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody);

        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody, null, 'Liste des stages');

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_STAGE,
            self::CST_ACTION => self::CST_WRITE,
        ];

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des stages',
            // La liste des éléments
            $objTable->getBean(),
            // Le lien pour créer un nouvel événement.
            UrlUtils::getAdminUrl($urlElements),
            // Libellé bouton
            'Nouveau stage',
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function dealWithWriteAction(): void
    {
        // Définition du service
        $objCopsStageServices = new CopsStageServices();
        // Récupération des données
        $objStage = $objCopsStageServices->getStage($this->id);

        // Le libellé, on stripslashes pour protéger
        /*
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
        $objCopsCar->setField(self::FIELD_VEH_OPTIONS, SessionUtils::fromPost(self::FIELD_VEH_OPTIONS, false));
        $objCopsCar->setField(self::FIELD_VEH_PRICE, SessionUtils::fromPost(self::FIELD_VEH_PRICE));
        $objCopsCar->setField(self::FIELD_VEH_YEAR, SessionUtils::fromPost(self::FIELD_VEH_YEAR));
        $objCopsCar->setField(self::FIELD_VEH_REFERENCE, SessionUtils::fromPost(self::FIELD_VEH_REFERENCE));
        $objCopsCar->setField(self::FIELD_VEH_LGN_ROUGE, SessionUtils::fromPost(self::FIELD_VEH_LGN_ROUGE));

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
        */
    }
}
