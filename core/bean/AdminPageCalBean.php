<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalBean
 * @author Hugues
 * @since 1.23.09.16
 */
class AdminPageCalBean extends AdminPageBean
{
    public $curPage;

    /**
     * @since 1.23.09.16
     */
    public function __construct()
    {
        $this->pageTitle = 'Random Californian Guy';
        $this->pageSubTitle = 'Gestion individus';
    }

    /**
     * @since v1.23.09.16
     * @version v1.23.11.25
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (SessionUtils::fromGet(self::CST_SUBONGLET)) {
            self::CST_ZIPCODE => new AdminPageCalZipCodeBean(),
            self::CST_PHONE => new AdminPageCalPhoneBean(),
            default => new AdminPageCalGuyBean(),
        };
        ///////////////////////////////////////////:
        return $objBean->getContentOnglet();
    }

    /**
     * @since 1.23.09.16
     */
    public function getContentPage(): string
    {
        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_PHONE   => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_PHONE],
            self::CST_ZIPCODE => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_ZIPCODE],
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_RND_GUY];
        $strLis = $this->buildTabs();
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return HtmlUtils::getBalise(self::TAG_UL, $strLis, $attributes);
    }

    /**
     * @since 1.23.09.16
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_RND_GUY];
        $strLink = HtmlUtils::getLink(self::LABEL_WEATHER, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since 1.23.09.16
     */
    public function getCardOnglet(string $strHeader, string $strContent): string
    {
        $strCardHeader = HtmlUtils::getDiv($strHeader, [self::ATTR_CLASS=>'card-header']);
        $strCardBody = HtmlUtils::getDiv($strContent, [self::ATTR_CLASS=>'card-body']);
        return HtmlUtils::getDiv($strCardHeader.$strCardBody, [self::ATTR_CLASS=>'card col mx-1 p-0 mb-1']);
    }
    
    /**
     * @since 1.23.09.16
     */
    public function getContentOnglet(): string
    {
        return 'Default. Specific getContentOnglet() to be defined.';
    }

    /**
     * @since 1.23.09.16
     *
    public function getCard(string $strCompteRendu=''): string
    {
        $titre='';
        $strBody='';
        $this->getCardContent($titre, $strBody);

        if ($strCompteRendu!='') {
            $attributes = [self::ATTR_CLASS=>'alert alert-primary', self::ATTR_ROLE=>'alert'];
            $strBody .= HtmlUtils::getDiv($strCompteRendu, $attributes);
        }

        $strCardHeader = HtmlUtils::getDiv($titre, [self::ATTR_CLASS=>'card-header']);
        $strCardBody = HtmlUtils::getDiv($strBody, [self::ATTR_CLASS=>'card-body']);

        return HtmlUtils::getDiv($strCardHeader.$strCardBody, [self::ATTR_CLASS=>'card col mx-1 p-0 mb-1']);
    }
    */
}
