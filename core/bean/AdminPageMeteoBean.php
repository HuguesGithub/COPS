<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoBean
 * @author Hugues
 * @since 1.23.04.20
 * @version v1.23.05.28
 */
class AdminPageMeteoBean extends AdminPageBean
{
    /*
    Les données relatives aux heures du soleil sont issues du site suivant :
    dateandtime.info/fr/citysunrisesunset.php?id=5368361
    */

    /**
     * @since 1.23.04.20
     */
    public static function getStaticContentPage(): string
    {
        $objBean = match (static::fromGet(self::CST_SUBONGLET)) {
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
     * @version v1.23.05.28
     */
    public function getContentPage(): string
    {
        $curSubOnglet = static::fromGet(self::CST_SUBONGLET);

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_WEATHER => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_WEATHER],
            self::CST_SUN     => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_SUN],
            self::CST_MOON    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_MOON],
        ];
        /////////////////////////////////////////

        $urlAttributes = [self::CST_ONGLET=>self::CST_METEO];
        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $urlAttributes[self::CST_SUBONGLET] = $slugSubOnglet;

            $blnActive = ($curSubOnglet==$slugSubOnglet || $curSubOnglet=='' && $slugSubOnglet==self::CST_HOME);
            $strLink = HtmlUtils::getLink(
                $arrData[self::FIELD_LABEL],
                UrlUtils::getAdminUrl($urlAttributes),
                self::NAV_LINK.($blnActive ? ' '.self::CST_ACTIVE : '')
            );
            $strLis .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>self::NAV_ITEM]);
        }
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        return $this->getBalise(self::TAG_UL, $strLis, $attributes);
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
     * @version v1.23.05.28
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

        return HtmlUtils::getDiv($strCardHeader.$strCardBody, [self::ATTR_CLASS=>'card col mx-1 p-0']);
    }
}
