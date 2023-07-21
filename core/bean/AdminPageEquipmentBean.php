<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageEquipmentBean
 * @author Hugues
 * @since v1.23.07.08
 * @version v1.23.07.22
 */
class AdminPageEquipmentBean extends AdminPageBean
{
    /**
     * @since v1.23.07.09
     * @version v1.23.07.15
     */
    public function __construct()
    {
        $this->pageTitle = self::LABEL_EQUIPMENT;
        $this->pageSubTitle = 'Gestion de la partie administrative de l\'équipement';
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (SessionUtils::fromGet(self::CST_SUBONGLET)) {
            self::CST_EQPT_WEAPON => new AdminPageEquipmentWeaponBean(),
            self::CST_EQPT_CAR => new AdminPageEquipmentCarBean(),
            //self::CST_EQPT_OTHER => new AdminPageEquipmentOtherBean(),
            default => new AdminPageEquipmentHomeBean(),
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
            self::CST_HOME        => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_EQPT_WEAPON => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_WEAPONS],
            self::CST_EQPT_CAR    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_CARS],
            self::CST_EQPT_OTHER  => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_OTHERS],
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction des onglets
        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_EQUIPMENT];
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

        $this->urlAttributes = [self::CST_ONGLET=>self::ONGLET_EQUIPMENT];
        $strLink = HtmlUtils::getLink(self::LABEL_EQUIPMENT, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.05.01
     * @version v1.23.05.07
     */
    public function getContentOnglet(): string
    {
        return 'Default. Specific getContentOnglet() to be defined.';
    }

}
