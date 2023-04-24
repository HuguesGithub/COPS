<?php
namespace core\bean;

/**
 * Classe AdminPageBean
 * @author Hugues
 * @since 1.23.04.20
 */
class AdminPageBean extends UtilitiesBean
{
    /**
     * Class Constructor
     * @since 1.23.04.20
     */
    public function __construct()
    {
        $this->analyzeUri();
    }

    /**
     * @since 1.23.04.20
     */
    public static function isAdmin(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * @since 1.23.04.20
     */
    public function getContentPage(): string
    {
        if (static::isAdmin() || current_user_can('editor')) {
            try {
                $returned = match ($this->urlParams[self::CST_ONGLET]) {
                    self::ONGLET_METEO => AdminPageMeteoBean::getStaticContentPage(),
                    self::ONGLET_INDEX => AdminPageIndexBean::getStaticContentPage(),
                    self::ONGLET_CALENDAR => AdminPageCalendrierBean::getStaticContentPage($this->urlParams),
                    default => 'WIP',
                };
            } catch (\Exception $e) {
                throw($e);
            }
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
