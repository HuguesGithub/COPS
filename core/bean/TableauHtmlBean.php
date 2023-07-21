<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe TableauHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.07.22
 */
class TableauHtmlBean extends UtilitiesBean
{
    private $arrAttributes;
    private $objTHead = null;
    private $objBody = null;
    private $objTFoot = null;
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function __construct()
    {
        $this->arrAttributes = [
            self::ATTR_CLASS => self::TAG_TABLE,
            self::ATTR_ARIA => [
                self::ATTR_DESCRIBEDBY => '',
            ],
        ];
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setTHead($objTHead): void
    { $this->objTHead = $objTHead; }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setBody($objBody): void
    { $this->objBody = $objBody; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setTFoot($objTFoot): void
    { $this->objTFoot = $objTFoot; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setSize(string $size): void
    { $this->setClass('table-'.$size); }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setStripped(): void
    { $this->setClass('table-striped'); }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setClass(string $extraClass): void
    { $this->arrAttributes[self::ATTR_CLASS] .= ' '.$extraClass; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setAria(string $field, $value): void
    { $this->arrAttributes[self::ATTR_ARIA][$field] = $value; }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.07.22
     */
    public function getBean(): string
    {
        // On défini le contenu du TABLE
        $strTableContent = '';
        $strTableContent .= $this->objTHead!=null ? $this->objTHead->getBean() : '';
        $strTableContent .= $this->objBody!=null ? $this->objBody->getBean() : '';
        $strTableContent .= $this->objTFoot!=null ? $this->objTFoot->getBean() : '';
        // On retourne la balise
        return HtmlUtils::getBalise(self::TAG_TABLE, $strTableContent, $this->arrAttributes);
    }
}
