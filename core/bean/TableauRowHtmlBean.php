<?php
namespace core\bean;

/**
 * Classe TableauRowHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.06.11
 */
class TableauRowHtmlBean extends UtilitiesBean
{
    private $strClass;
    private $arrCells;
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function __construct()
    {
        $this->strClass = '';
        $this->arrCells = [];
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function addCell($objCell): void
    { $this->arrCells[] = $objCell; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function getBean(): string
    {
        // On défini le contenu du TR
        $strRowContent = '';
        foreach ($this->arrCells as $objCell) {
            $strRowContent .= $objCell->getBean();
        }
        // On initialise le tableau des attributs
        $attributes = $this->strClass=='' ? [] : [self::ATTR_CLASS => $this->strClass];
        // On retourne la balise
        return $this->getBalise(self::TAG_TR, $strRowContent, $attributes);
    }
}
