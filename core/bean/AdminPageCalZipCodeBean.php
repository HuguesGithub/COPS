<?php
namespace core\bean;

use core\services\CopsCalZipcodeServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalZipCodeBean
 * Gère l'affichage de l'onglet "Code Postal" de l'interface "Random Californian Guy"
 * @author Hugues
 * @since 1.23.10.14
 * @version v1.23.12.02
 */
class AdminPageCalZipCodeBean extends AdminPageCalBean
{

    /**
     * @since 1.23.10.14
     */
    public function getContentOnglet(): string
    {
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination, l'action ou l'id de l'événement concerné
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        /////////////////////////////////////////

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        // Initialisation de la liste des cards qu'on va afficher.
        $strCards = $this->getListContent();
        
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
     * @since 1.23.10.14
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_ZIPCODE, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
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
     * @since 1.23.10.14
     * @version v1.23.12.02
     */
    public function getListContent(): string
    {
        // Définition du service
        $objServices = new CopsCalZipcodeServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $filters = [
            self::FIELD_PRIMARY_CITY => $this->initVar(self::FIELD_PRIMARY_CITY, self::SQL_JOKER_SEARCH),
        ];
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_ZIP);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = array_merge($filters, [
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ]);
        $objs = $objServices->getCalZipcodes($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = array_merge($attributes, [
            self::CST_ONGLET => self::ONGLET_RND_GUY,
            self::CST_SUBONGLET => self::CST_ZIPCODE,
        ]);

        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objs,
        ]);

        $objBean = new CopsCalZipCodeBean();
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = $objBean->getTableHeader($queryArg);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody, $objBean->getEmptyRow());
        //////////////////////////////////////////////////////
        // Définition du Footer du tableau
        $objFooter = $objBean->getTableFooter($filters);
        
        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody, $objFooter, 'Liste des codes postaux');

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des codes postaux',
            // La liste des éléments
            $objTable->getBean(),
            // L'éventuel bouton de création d'un nouvel élément
            '',
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

}
