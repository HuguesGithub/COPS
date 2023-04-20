<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdminCopsPlayerPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class AdminCopsPlayerPageBean extends WpPageAdminBean
{
    /**
     * @return string
     * @version 1.22.04.27
     * @since 1.22.04.27
     */
    public function getBoard()
    {
        $isCreation = true;
        $strContent = match ($this->urlParams[self::CST_SUBONGLET]) {
            'player-carac' => $this->CopsPlayer->getBean()->getCopsPlayerCarac($isCreation),
            'player-comps' => $this->CopsPlayer->getBean()->getCopsPlayerComps(),
            default => '',
        };

        // Soit on est loggué et on affiche le contenu du bureau du cops
        $urlTemplate = self::WEB_PP_BOARD;
        $attributes = [
            // La sidebar
            $this->getSidebar(),
            // Le contenu de la page
            $strContent,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

}
