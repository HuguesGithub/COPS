<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe TableauCellHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.07.22
 */
class TableauCellHtmlBean extends UtilitiesBean
{
    private $strType;
    private $strClass;
    private $strContent;
    private $arrAttributes;

    private $isPublic = false;
    private $isSortable = false;
    private $urlElements = [];
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.18
     */
    public function __construct(
        string $strContent,
        string $strType = self::TAG_TD,
        string $strClass = '',
        array $attributes = []
    ) {
        $this->strType = $strType;
        $this->strContent = $strContent;
        $this->strClass = $strClass;
        $this->arrAttributes = $attributes;
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.07.22
     */
    public function getBean(string $defaultSortCol = ''): string
    {
        if ($this->isSortable) {
            $strSort = 'sorting';
            $orderby = $this->initVar(self::SQL_ORDER_BY, $defaultSortCol);

            if ($orderby==$this->urlElements[self::SQL_ORDER_BY]) {
                $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);
                $strSort .= '_'.strtolower($order);
                $newOrder = $order==self::SQL_ORDER_ASC ? self::SQL_ORDER_DESC : self::SQL_ORDER_ASC;
                $this->urlElements[self::SQL_ORDER] = $newOrder;
            } else {
                $this->urlElements[self::SQL_ORDER] = self::SQL_ORDER_ASC;
            }

            if ($this->isPublic) {
                $url = UrlUtils::getPublicUrl($this->urlElements);
                $strClass = self::CST_TEXT_WHITE;
            } else {
                $url = UrlUtils::getAdminUrl($this->urlElements);
                $strClass = '';
            }

            $cellContent = HtmlUtils::getLink($this->strContent, $url, $strClass);
            $this->arrAttributes[self::ATTR_CLASS] = $strSort.' '.$this->strClass;
            return HtmlUtils::getBalise($this->strType, $cellContent, $this->arrAttributes);
        } else {
            $this->arrAttributes[self::ATTR_CLASS] = $this->strClass;
            return HtmlUtils::getBalise($this->strType, $this->strContent, $this->arrAttributes);
        }
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function ableSort(array $urlElements): void
    {
        $this->isSortable = true;
        $this->urlElements = $urlElements;
    }

    public function setPublic(bool $isPublic=false): void
    { $this->isPublic = $isPublic; }
}
