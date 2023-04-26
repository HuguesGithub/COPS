<?php
namespace core\bean;

use core\domain\CopsMeteoClass;

/**
 * Classe WpPageHomeBean
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.30
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
        $str = '';
        $miniNav = [
            'world'=>'World',
            'us'=>'U.S.',
            'politics'=>'Politics',
            'nyregion'=>'N.Y.',
            'business'=>'Business',
        ];
        foreach ($miniNav as $key => $value) {
            $strLink = $this->getLink($value, '/section/'.$key, '');
            $str .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>'mini-nav-item']);
        }

        /////////////////////////////////////////
        // Récupération de la météo
        $objCopsMeteo = new CopsMeteoClass();
        $objCopsMeteo->init();

        $args = [
            // La date Cops du jour, format : Dimanche 12 Mars 2023
            // static::getCopsDate(self::FORMAT_STRJOUR)
            $objCopsMeteo->getStrDate(),
            // Le type de météo
            $objCopsMeteo->getField(self::FIELD_WEATHER_ID),
            // Le titre
            $objCopsMeteo->getAltStr(),
            // La température
            $objCopsMeteo->getField(self::FIELD_TEMPERATURE),
            // La température max
            $objCopsMeteo->getMaxTemp(),
            // La température min
            $objCopsMeteo->getMinTemp(),
            //
            $str,
            // L'heure
            $objCopsMeteo->getStrHour(),
        ];
        return $this->getRender($urlTemplate, $args);
    }

}
