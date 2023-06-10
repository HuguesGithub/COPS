<?php
namespace core\bean;

use core\bean\TableauRowHtmlBean;

/**
 * Classe TableauContentHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.06.11
 */
class TableauContentHtmlBean extends UtilitiesBean
{
    protected $strType;
    protected $strClass;
    protected $arrRows;
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function __construct()
    {
        $this->strClass = '';
        $this->arrRows = [];
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function addRow(TableauRowHtmlBean $objRow): void
    { $this->arrRows[] = $objRow; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function getBean(): string
    {
        // On défini le contenu
        $strContent = '';
        foreach ($this->arrRows as $objRow) {
            $strContent .= $objRow->getBean();
        }
        // On initialise le tableau des attributs
        $attributes = $this->strClass=='' ? [] : [self::ATTR_CLASS => $this->strClass];
        // On retourne la balise
        return $this->getBalise($this->strType, $strContent, $attributes);
    }
    
}

