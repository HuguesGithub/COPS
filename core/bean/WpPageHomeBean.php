<?php
namespace core\bean;

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
        $urlTemplate = self::WEB_PP_HOME_CONTENT;

        $args = [
            // La date Cops du jour, format : Dimanche 12 Mars 2023
            static::getCopsDate(self::FORMAT_STRJOUR),
            // Le type de météo
            // La température
            // La température max
            // La température min
        ];
        return $this->getRender($urlTemplate, $args);
    }

}
