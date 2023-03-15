<?php
namespace core\bean;

/**
 * WpPostBddBean
 * @author Hugues
 * @since 1.22.00.00
 * @version 1.22.09.23
 */
class WpPostBddBean extends WpPostBean
{

    public function getContentDisplay()
    {
        $urlTemplate = self::WEB_PPFA_BDD;
        list($sigle) = explode(':', $this->WpPost->getField(self::WP_POSTTITLE));
        $attributes = array(
            // Le titre
            $this->WpPost->getField(self::WP_POSTTITLE),
            // Le contenu
            $this->WpPost->getField(self::WP_POSTCONTENT),
            // L'ancre
            trim($sigle),
        );
        return $this->getRender($urlTemplate, $attributes);
    }

}
