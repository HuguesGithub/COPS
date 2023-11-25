<?php
namespace core\bean;

use core\services\CopsCalGuyServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalGuyBean
 * @author Hugues
 * @since v1.23.11.25
 */
class AdminPageCalGuyBean extends AdminPageCalBean
{

    /**
     * @since v1.23.11.25
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

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        // Initialisation de la liste des cards qu'on va afficher.
        $strCards = $this->getCard();
        
        // On va afficher la dernière donnée enregistrée
        // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
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
     * @since 1.23.09.16
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_HOME, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.10.14
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
     * @since v1.23.10.14
     */
    public function getEditContent(): string
    {
        // Définition du service
        $objServices = new CopsCalGuyServices();

        // Récupération des données
        $objCalGuy = $objServices->getCalGuy($this->id);

        // Récupération des éléments nécessaires à l'affichage de l'écran d'édition
        $attributes = $objCalGuy->getBean()->getDetailInterface();

        $urlTemplate = self::WEB_PPFD_BDD_CAL_GUY;
        $strInterfaceEdition = $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Gestion du lien d'annulation
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_HOME,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $attributes = [$this->id, $urlAnnulation];
        $urlTemplate = self::WEB_PA_CONFIRM;
        $strInterfaceConfirm = $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////

        $attributes = [
            'method'=>'post',
            self::ATTR_CLASS => 'row p-3 col-12',
        ];
        return HtmlUtils::getBalise(self::TAG_FORM, $strInterfaceEdition.$strInterfaceConfirm, $attributes);
    }

    /**
     * @since 1.23.09.16
     */
    public function getListContent(): string
    {
        // Définition du service
        $objServices = new CopsCalGuyServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $filters = [
            'filterGenre' => $this->initVar('filterGenre', self::SQL_JOKER_SEARCH),
            'filterName' => $this->initVar('filterName', self::SQL_JOKER_SEARCH),
            self::FIELD_NAMESET => $this->initVar(self::FIELD_NAMESET, self::SQL_JOKER_SEARCH),
            self::FIELD_ZIPCODE => $this->initVar(self::FIELD_ZIP, self::SQL_JOKER_SEARCH),
            self::FIELD_PRIMARY_CITY => $this->initVar(self::FIELD_PRIMARY_CITY, self::SQL_JOKER_SEARCH),
        ];
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_ID);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = array_merge($filters, [
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ]);
        $objs = $objServices->getCalGuys($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = array_merge($attributes, [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_HOME,
        ]);

        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objs,
        ]);

        $objBean = new CopsCalGuyBean();
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = $objBean->getTableHeader($queryArg);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody, $objBean->getEmptyRow(), true);
        //////////////////////////////////////////////////////
        // Définition du Footer du tableau
        $objFooter = $objBean->getTableFooter($filters);
        
        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody, $objFooter, 'Liste des anonymes');

        $href = UrlUtils::getAdminUrl(array_merge($queryArg, [self::CST_ACTION=>self::CST_WRITE]));
        $btnNew = HtmlUtils::getLink('Nouvelle personne', $href, 'btn btn-sm btn-info float-start');
        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des anonymes',
            // La liste des éléments
            $objTable->getBean(),
            // L'éventuel bouton de création d'un nouvel élément
            $btnNew,
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    public function dealWithWriteAction(): void
    {
        // Définition du service
        $objServices = new CopsCalGuyServices();
        // Récupération des données
        $objCalGuy = $objServices->getCalGuy($this->id);

        // Le libellé, on stripslashes pour protéger
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_TITLE, false));
        $objCalGuy->setField(self::FIELD_TITLE, $value);
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_FIRSTNAME, false));
        $objCalGuy->setField(self::FIELD_FIRSTNAME, $value);
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_LASTNAME, false));
        $objCalGuy->setField(self::FIELD_LASTNAME, $value);
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_BIRTHDAY, false));
        $objCalGuy->setField(self::FIELD_BIRTHDAY, $value);
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_KILOGRAMS, false));
        $value = str_replace('kg', '', $value);
        $objCalGuy->setField(self::FIELD_KILOGRAMS, $value);
        $value = stripslashes(SessionUtils::fromPost(self::FIELD_CENTIMETERS, false));
        $value = str_replace('m', '', $value);
        $objCalGuy->setField(self::FIELD_CENTIMETERS, $value);

        // On vérifie les données.
        if ($objCalGuy->checkFields()) {
            if ($this->id==0) {
                $objServices->insertCalGuy($objCalGuy);
                $this->id = $objCalGuy->getField(self::FIELD_ID);
            } else {
                $objServices->updateCalGuy($objCalGuy);

            }
        } else {
            // TODO : Gestion de l'erreur ?
        }
    }
}
