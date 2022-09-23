<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageHomeBean
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.09.23
 */
class WpPageHomeBean extends WpPageBean
{

    /**
     * @since 1.22.04.28
     * @version 1.22.09.23
     */
    public function getContentPage()
    {
        $urlTemplate = 'web/pages/public/public-home-content.php';

        $args = array(
            // La date Cops du jour
            $this->getCopsDate('strJour'),
        );
        return $this->getRender($urlTemplate, $args);
    }

}
