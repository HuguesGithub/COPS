<?php
namespace core\bean;

use core\services\CopsSkillServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageLibrarySkillBean
 * @author Hugues
 * @since v1.23.08.12
 */
class AdminPageLibrarySkillBean extends AdminPageLibraryBean
{

    /**
     * @since v1.23.08.12
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_SKILLS, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
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
     * @since v1.23.08.12
     */
    public function getEditContent(): string
    {
        return '';
        // TODO
    }

    /**
     * @since v1.23.08.12
     */
    public function getListContent(): string
    {
        // Définition du service
        $objServices = new CopsSkillServices();
        // On récupère les données éventuelles sur les filtres et les tris
        $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_SKILL_NAME);
        $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);

        // Récupération des données
        $attributes = [
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objsSkill = $objServices->getSkills($attributes);

        //////////////////////////////////////////////////////
        // Définition de l'objet Pagination
        $objPagination = new PaginationHtmlBean();
        $queryArg = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_SKILL,
            self::SQL_ORDER_BY => $orderby,
            self::SQL_ORDER => $order,
        ];
        $objPagination->setData([
            self::CST_CURPAGE => $this->curPage,
            self::CST_URL => UrlUtils::getAdminUrl(),
            self::PAGE_QUERY_ARG => $queryArg,
            self::PAGE_OBJS => $objsSkill,
        ]);

        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objHeader = CopsSkillBean::getTableHeader($queryArg);
        //////////////////////////////////////////////////////
        // Définition du Body du tableau
        $objBody = new TableauBodyHtmlBean();
        // On ajoute les lignes du tableau ici.
        $objPagination->getDisplayedRows($objBody);

        //////////////////////////////////////////////////////
        $objTable = new TableauHtmlBean();
        $objTable->defaultInit($objHeader, $objBody, null, 'Liste des compétences');

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_SKILL,
            self::CST_ACTION => self::CST_WRITE,
        ];
        $href = UrlUtils::getAdminUrl($urlElements);
        $btnLink = HtmlUtils::getLink('Nouvelle compétence', $href, 'btn btn-sm btn-info float-start');

        $urlTemplate = self::WEB_PAF_DEFAULT_LIST;
        $attributes = [
            // Titre du card
            'Liste des compétences',
            // La liste des éléments
            $objTable->getBean(),
            // L'éventuel bouton de création d'un nouvel élément
            $btnLink,
            // La pagination éventuelle
            $objPagination->getPaginationBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.12
     */
    public function dealWithWriteAction(): void
    {
        // TODO
    }
}
