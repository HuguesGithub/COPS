<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoBean
 * @author Hugues
 * @since 1.23.04.20
 * @version v1.23.07.22
 */
class AdminPageMeteoBean extends AdminPageBean
{
    /*
    Les données relatives aux heures du soleil sont issues du site suivant :
    dateandtime.info/fr/citysunrisesunset.php?id=5368361
    */

    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function __construct()
    {
        $this->pageTitle = self::LABEL_WEATHER;
        $this->pageSubTitle = 'Gestion de la partie administrative de la météo';
    }

    /**
     * @since v1.23.04.20
     * @version v1.23.06.25
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (SessionUtils::fromGet(self::CST_SUBONGLET)) {
            self::CST_WEATHER => new AdminPageMeteoMeteoBean(),
            self::CST_SUN => new AdminPageMeteoSunBean(),
            self::CST_MOON => new AdminPageMeteoMoonBean(),
            default => new AdminPageMeteoHomeBean(),
        };
        ///////////////////////////////////////////:
        return $objBean->getContentOnglet();
    }

    /**
     * @since 1.23.04.20
     * @version v1.23.07.22
     */
    public function getContentPage(): string
    {
        // On récupère la date du jour.
        $this->curStrDate = $this->initVar(self::CST_DATE, DateUtils::getCopsDate(self::FORMAT_DATE_YMD));

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_WEATHER => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_WEATHER],
            self::CST_SUN     => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_SUN],
            self::CST_MOON    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_MOON],
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_METEO];
        $strLis = $this->buildTabs();
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return HtmlUtils::getBalise(self::TAG_UL, $strLis, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_METEO];
        $strLink = HtmlUtils::getLink(self::LABEL_WEATHER, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.06.18
     * @version v1.23.06.18
     */
    public function getCardOnglet(string $strHeader, string $strContent): string
    {
        $strCardHeader = HtmlUtils::getDiv($strHeader, [self::ATTR_CLASS=>'card-header']);
        $strCardBody = HtmlUtils::getDiv($strContent, [self::ATTR_CLASS=>'card-body']);
        return HtmlUtils::getDiv($strCardHeader.$strCardBody, [self::ATTR_CLASS=>'card col mx-1 p-0 mb-1']);
    }
    
    /**
     * @since v1.23.04.26
     */
    public function getContentOnglet(): string
    {
        return 'Default. Specific getContentOnglet() to be defined.';
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.06.18
     */
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
}
