<?php
namespace core\utils;

use core\interfaceimpl\ConstantsInterface;

/**
 * HtmlUtils
 * @author Hugues
 * @since v1.23.05.23
 * @version v1.23.08.05
 */
class HtmlUtils implements ConstantsInterface
{
    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getBalise(string $balise, string $label='', array $attributes=[]): string
    { return '<'.$balise.static::getExtraAttributesString($attributes).'>'.$label.'</'.$balise.'>'; }

    /**
     * Permet de construire une chaîne d'attributs pour une balise.
     * On attend un tableau :
     *  - [key] = value => key="value"
     *  - [key][sub1] = v1 => key-sub1="v1"
     *    [key][sub2] = v2 => key-sub2="v2"
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getExtraAttributesString(array $attributes): string
    {
        $extraAttributes = '';
        // Si la liste des attributs n'est pas vide
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                // Si l'attribut est un tableau
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        // On construit sur le modèle key-subkey="value"
                        $extraAttributes .= ' '.$key.'-'.$subkey.'="'.$subvalue.'"';
                    }
                } else {
                    // On construit sur le modèle key="value"
                    $extraAttributes .= ' '.$key.'="'.$value.'"';
                }
            }
        }
        return $extraAttributes;
    }

    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getButton(string $label, array $extraAttributes=[]): string
    {
        // Les attributs par défaut d'un bouton.
        $defaultAttributes = [
            self::ATTR_TYPE => self::TAG_BUTTON,
            self::ATTR_CLASS => 'btn btn-default btn-sm'
        ];

        if (isset($extraAttributes[self::ATTR_CLASS])) {
            $defaultAttributes[self::ATTR_CLASS] .= $extraAttributes[self::ATTR_CLASS];
            unset($extraAttributes[self::ATTR_CLASS]);
        }

        $attributes = array_merge($defaultAttributes, $extraAttributes);
        return static::getBalise(self::TAG_BUTTON, $label, $attributes);
    }
    
    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getOption(string $label='', string $value='', bool $blnChecked=false): string
    {
        $attributes = [self::ATTR_VALUE => $value];
        if ($blnChecked) {
            $attributes[self::CST_SELECTED] = self::CST_SELECTED;
        }
        return static::getBalise('option', $label, $attributes);
    }
    
    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getTh(string $label, array $extraAttributes=[]): string
    {
        $attributes = array_merge(['scope' => 'col'], $extraAttributes);
        return static::getBalise('th', $label, $attributes);
    }
    
    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getLink(string $label, string $href, string $classe='', array $extraAttributes=[]): string
    {
        $attributes = array_merge([self::ATTR_HREF => $href, self::ATTR_CLASS => $classe], $extraAttributes);
        return static::getBalise('a', $label, $attributes);
    }
    
    /**
     * @since v1.23.05.23
     * @version v1.23.05.28
     */
    public static function getDiv(string $label, array $extraAttributes=[]): string
    { return static::getBalise('div', $label, $extraAttributes); }

    /**
     * @since v1.23.05.23
     * @version v1.23.08.05
     */
    public static function getIcon(string $tag, string $prefix='', string $label=''): string
    {
        $allowedTags = [
            self::I_ANGLE_LEFT,
            self::I_ANGLE_RIGHT,
            self::I_ANGLES_LEFT,
            self::I_ARROWS_ROTATE,
            self::I_BACKWARD,
            self::I_CARET_LEFT,
            self::I_CARET_RIGHT,
            self::I_CIRCLE,
            self::I_DATABASE,
            self::I_DELETE,
            self::I_DESKTOP,
            self::I_DOWNLOAD,
            self::I_EDIT,
            self::I_FILE_CATEGORY,
            self::I_FILE_CIRCLE_PLUS,
            self::I_FILE_CIRCLE_CHECK,
            self::I_FILE_CIRCLE_XMARK,
            self::I_FILE_OPENED,
            self::I_FILE_CLOSED,
            self::I_FILE_COLDED,
            self::I_FILTER_CIRCLE_XMARK,
            self::I_GEAR,
            self::I_HOUSE,
            self::I_REFRESH,
            self::I_SQUARE_CHECK,
            self::I_SQUARE_XMARK,
            self::I_USERS,
            'book',
            'box-archive',
            'calendar',
            'calendar-week',
            'calendar-days',
            'comment',
            'envelope',
            'inbox',
            'right-from-bracket',
            'square-pen',
            'square-plus',
            'trash-alt',
            'user',
        ];
        if ($prefix!='') {
            $prefix .= ' ';
        }
        $prefix .= 'fa-solid fa-'.(in_array($tag, $allowedTags) ? $tag : 'biohazard');

        return static::getBalise('i', $label, [self::ATTR_CLASS=>$prefix]);
    }
}
