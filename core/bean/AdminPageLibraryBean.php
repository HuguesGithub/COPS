<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageLibraryBean
 * @author Hugues
 * @since v1.23.07.29
 * @version v1.23.08.05
 */
class AdminPageLibraryBean extends AdminPageBean
{
    public $curPage;
    public $action;
    public $id;

    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function __construct()
    {
        $this->pageTitle = self::LABEL_LIBRARY;
        $this->pageSubTitle = 'Gestion de la partie administrative de la bibliothèque';
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (SessionUtils::fromGet(self::CST_SUBONGLET)) {
            self::CST_LIB_STAGE => new AdminPageLibraryCourseBean(),
            self::CST_LIB_SKILL => new AdminPageLibrarySkillBean(),
            default => new AdminPageLibraryHomeBean(),
        };
        ///////////////////////////////////////////:
        return $objBean->getContentOnglet();
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function getContentPage(): string
    {
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME      => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_LIB_STAGE => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_COURSES],
            self::CST_LIB_SKILL => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_SKILLS],
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_LIBRARY];
        $strLis = $this->buildTabs();
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return HtmlUtils::getBalise(self::TAG_UL, $strLis, $attributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getContentOnglet(): string
    {
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination, l'action ou l'id de l'élément concerné
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
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_LIBRARY];
        $strLink = HtmlUtils::getLink(self::LABEL_LIBRARY, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

}
