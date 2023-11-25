<?php
namespace core\bean;

use core\domain\CopsCalGuyClass;
use core\enum\SectionEnum;
use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminDatabaseSearchBean
 * @author Hugues
 * @since v1.23.11.23
 */
class WpPageAdminDatabaseSearchBean extends WpPageAdminDatabaseBean
{
    public $filters=[];
    public $curPage;

    /**
     * @since v1.23.11.23
     */
    public function __construct()
    {
        parent::__construct();

        $this->initServices();

        // On récupère les données éventuelles sur les filtres et les tris
        $this->filters = [
            self::FIELD_TITLE => $this->initVar(self::FIELD_TITLE, self::SQL_JOKER_SEARCH),
            self::FIELD_FIRSTNAME => $this->initVar(self::FIELD_FIRSTNAME, self::SQL_JOKER_SEARCH),
            self::FIELD_LASTNAME => $this->initVar(self::FIELD_LASTNAME, self::SQL_JOKER_SEARCH),
        ];
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);

        /////////////////////////////////////////
        $this->urlAttributes[self::CST_SUBONGLET] = self::CST_BDD_SEARCH;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_SEARCH,
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
     * @since v1.23.11.25
     */
    public function initServices()
    {
        $this->objServices = new CopsCalGuyServices();
    }

    /**
     * @since v1.23.11.25
     */
    public function getOngletContent(): string
    {
        $searchAction = $this->initVar('searchAction', 0);
        if ($searchAction==0) {
            $listContent = '';
            $style = 'd-none';
        } else {
            $listContent = $this->getListBlock();
            $style = '';
        }

        $urlTemplate = self::WEB_PPFF_BDD_SEARCH;
        $attributes = [
            // Url du formulaire
            UrlUtils::getPublicUrl($this->urlAttributes),
            // Premier Block
            $this->getFirstBlock(),
            // Bloc affiché ?
            $style,
            // Deuxième Block
            $listContent,
            // Pagination éventuelle
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }


    /**
     * @since v1.23.11.25
     */
    public function getFirstBlock(): string
    {
        $obj = new CopsCalGuyClass();
        $obj->setField(self::FIELD_TITLE, $this->filters[self::FIELD_TITLE] ?? self::SQL_JOKER_SEARCH);
        $obj->setField(self::FIELD_FIRSTNAME, $this->filters[self::FIELD_FIRSTNAME] ?? self::SQL_JOKER_SEARCH);
        $obj->setField(self::FIELD_LASTNAME, $this->filters[self::FIELD_LASTNAME] ?? self::SQL_JOKER_SEARCH);

        $urlTemplate = self::WEB_PPFD_BDD_CAL_GUY;
        $attributes = $obj->getBean()->getDetailInterface();
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function getListBlock(): string
    {
        $objBean = new CopsCalGuyBean();

        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_LASTNAME);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = array_merge($this->filters, [
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ]);
        $objs = $this->objServices->getCalGuys($attributes);
        if (empty($objs)) {
            $strReplaced = $objBean->getEmptyRow();
        } elseif (count($objs)>99) {
            $strReplaced = $objBean->getTooMuchRows();
            $objs = [];
        } else {
            $strReplaced = null;
        }

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = array_merge($attributes, [
            self::CST_ONGLET => self::ONGLET_BDD,
            self::CST_SUBONGLET => self::CST_BDD_SEARCH,
            'searchAction' => 1,
        ]);

        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getPublicUrl([self::WP_PAGE=>self::PAGE_ADMIN]),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objs,
            self::CSS_PAGE_LINK => self::CSS_BG_WHITE,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = $objBean->getTableHeader($queryArg);

        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody, $strReplaced);
        
        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody);

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Résultat de la recherche',
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
