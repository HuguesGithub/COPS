<?php
namespace core\bean;

use core\domain\CopsMeteoClass;

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
        /*
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/opinion">Opinion</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/science">Science</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/health">Health</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/sports">Sports</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/arts">Arts</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/books">Books</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/style">Style</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/food">Food</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/travel">Travel</a></li>
                    <li data-testid="mini-nav-item" class="css-cwdrld"><a class="css-1wjnrbv" href="/section/magazine">Magazine</a></li>
        */
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
