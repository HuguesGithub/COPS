<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe TableauRowHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.07.22
 */
class TableauRowHtmlBean extends UtilitiesBean
{
    private $strClass;
    private $arrCells;
    private $attributes;
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.18
     */
    public function __construct()
    {
        $this->strClass = '';
        $this->arrCells = [];
        $this->attributes = [];
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function addCell($objCell): void
    { $this->arrCells[] = $objCell; }

    /**
     * @since v1.23.06.18
     * @version v1.23.06.18
     */
    public function addStyle(string $strStyle): void
    {
        if (isset($this->attributes[self::ATTR_STYLE])) {
            $this->attributes[self::ATTR_STYLE] .= ' '.$strStyle;
        } else {
            $this->attributes[self::ATTR_STYLE] = $strStyle;
        }
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.07.22
     */
    public function getBean(): string
    {
        // On défini le contenu du TR
        $strRowContent = '';
        foreach ($this->arrCells as $objCell) {
            $strRowContent .= $objCell->getBean();
        }
        // On initialise le tableau des attributs
        $this->attributes[self::ATTR_CLASS] = $this->strClass;
        $attributes = $this->attributes;
        // On retourne la balise
        return HtmlUtils::getBalise(self::TAG_TR, $strRowContent, $attributes);
    }
}
