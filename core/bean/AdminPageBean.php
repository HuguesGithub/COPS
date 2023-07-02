<?php
namespace core\bean;

use core\utils\SessionUtils;

/**
 * Classe AdminPageBean
 * @author Hugues
 * @since 1.23.04.20
 * @version v1.23.07.02
 */
class AdminPageBean extends UtilitiesBean
{
    /**
     * Class Constructor
     * @since 1.23.04.20
     * @version v1.23.07.02
     */
    public function __construct()
    {
        // TODO : $this->analyzeUri();
    }

    /**
     * @since 1.23.04.20
     * @version v1.23.06.25
     */
    public static function isAdmin(): bool
    {
        return false;
    }

    /**
     * @since 1.23.04.20
     * @version v1.23.06.25
     */
    public function getContentPage(): string
    {
        if (current_user_can('manage_options') || current_user_can('editor')) {
            $slugOnglet = SessionUtils::fromGet(self::CST_ONGLET);
            $returned = match ($slugOnglet) {
                self::ONGLET_METEO => AdminPageMeteoBean::getStaticContentPage(),
                self::ONGLET_INDEX => AdminPageIndexBean::getStaticContentPage(),
                self::ONGLET_CALENDAR => AdminPageCalendarBean::getStaticContentPage(),
                default => 'Error. Unexpected Onglet Term [<strong>'.$slugOnglet.'</strong>].',
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

}
