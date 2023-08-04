<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageLibraryHomeBean
 * @author Hugues
 * @since v1.23.07.08
 * @version v1.23.07.22
 */
class AdminPageLibraryHomeBean extends AdminPageLibraryBean
{
    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function getContentOnglet(): string
    {
        $this->dealWithGetActions();

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

        $strLink = HtmlUtils::getLink(self::LABEL_HOME, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function getCard(): string
    {
        $attributes = [
        ];
        return $this->getRender(self::WEB_PA_LIBRARY_HOME, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function dealWithGetActions(): void
    {
        // TODO : need to be implemented.
    }

}
