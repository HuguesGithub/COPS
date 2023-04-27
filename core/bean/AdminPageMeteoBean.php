<?php
namespace core\bean;

/**
 * AdminPageMeteoBean
 * @author Hugues
 * @since 1.23.04.20
 * @version 1.23.04.30
 */
class AdminPageMeteoBean extends AdminPageBean
{
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
     */
    public function getContentPage(): string
    {
        $curSubOnglet = static::fromPost(self::CST_SUBONGLET);

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_HOME    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_HOME],
            self::CST_WEATHER => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_WEATHER],
            self::CST_SUN     => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_SUN],
            self::CST_MOON    => [self::FIELD_ICON => '', self::FIELD_LABEL => self::LABEL_MOON],
        ];
        /////////////////////////////////////////

        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $urlSubOnglet  = 'https://cops.jhugues.fr/wp-admin/admin.php?page=hj-cops%2Fadmin_manage.php';
            $urlSubOnglet .= '&onglet=meteo&subOnglet='.$slugSubOnglet;

            $strLink = $this->getLink(
                $arrData[self::FIELD_LABEL],
                $urlSubOnglet,
                self::NAV_LINK.($curSubOnglet==$slugSubOnglet ? ' '.self::CST_ACTIVE : '')
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
}
