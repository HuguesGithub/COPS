<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * Classe AdminPageBean
 * @author Hugues
 * @since 1.23.04.20
 * @version v1.23.08.05
 */
class AdminPageBean extends UtilitiesBean
{
    public $breadCrumbsContent = '';
    public $styleBreadCrumbs = '';
    public $strBreadcrumbs = '';

    public $slugOnglet;
    public $slugSubOnglet;
    
    public $arrSubOnglets = [];
    public $urlAttributes = [];

    public $pageTitle = '';
    public $pageSubTitle = '';

    /**
     * @since 1.23.04.20
     * @version v1.23.08.05
     */
    public function getContentPage(): string
    {
        if (current_user_can('manage_options') || current_user_can('editor')) {
            $this->slugOnglet = SessionUtils::fromGet(self::CST_ONGLET);
            $returned = match ($this->slugOnglet) {
                self::ONGLET_METEO => AdminPageMeteoBean::getStaticContentPage(),
                self::ONGLET_INDEX => AdminPageIndexBean::getStaticContentPage(),
                self::ONGLET_CALENDAR => AdminPageCalendarBean::getStaticContentPage(),
                self::ONGLET_EQUIPMENT => AdminPageEquipmentBean::getStaticContentPage(),
                self::ONGLET_LIBRARY => AdminPageLibraryBean::getStaticContentPage(),
                self::ONGLET_RND_GUY => AdminPageCalBean::getStaticContentPage(),
                default => 'Error. Unexpected Onglet Term [<strong>'.$this->slugOnglet.'</strong>].',
            };
        }
        return $returned;
    }

    /**
     * @since 1.23.04.20
     */
    public function getBoard(): string
    {
        return '';
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildTabs(): string
    {
        $this->slugSubOnglet = SessionUtils::fromGet(self::CST_SUBONGLET);
        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $this->urlAttributes[self::CST_SUBONGLET] = $slugSubOnglet;
            $strIcon = '';

            if (!empty($arrData[self::FIELD_ICON])) {
                $strIcon = HtmlUtils::getIcon($arrData[self::FIELD_ICON]).self::CST_NBSP;
            }
            
            if ($slugSubOnglet!=self::CST_HOME && isset($this->urlAttributes[self::CST_DATE])) {
                $this->urlAttributes[self::CST_DATE] = $this->curStrDate;
            } else {
                unset($this->urlAttributes[self::CST_DATE]);
            }

            $blnActive  = $this->slugSubOnglet=='' && $slugSubOnglet==self::CST_HOME;
            $blnActive  = $blnActive || $this->slugSubOnglet==$slugSubOnglet;
            $strLink = HtmlUtils::getLink(
                $strIcon.$arrData[self::FIELD_LABEL],
                UrlUtils::getAdminUrl($this->urlAttributes),
                self::NAV_LINK.($blnActive ? ' '.self::CST_ACTIVE : '')
            );
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>self::NAV_ITEM]);
        }
        return $strLis;
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        $this->styleBreadCrumbs = 'breadcrumb-item '.self::CSS_FLOAT_LEFT;
        $strLink = HtmlUtils::getLink(HtmlUtils::getIcon(self::I_HOUSE), UrlUtils::getAdminUrl(), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

}
