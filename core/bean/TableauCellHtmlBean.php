<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe TableauCellHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.06.11
 */
class TableauCellHtmlBean extends UtilitiesBean
{
    private $strType;
    private $strClass;
    private $strContent;

    private $isSortable = false;
    private $urlElements = [];
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function __construct(string $strContent, string $strType=self::TAG_TD, string $strClass='')
    {
        $this->strType = $strType;
        $this->strContent = $strContent;
        $this->strClass = $strClass;
    }
    
    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function getBean(): string
    {
        if ($this->isSortable) {
            $strSort = 'sorting';
            $orderby = $this->initVar(self::SQL_ORDER_BY, self::FIELD_DATE_DEBUT);

            if ($orderby==$this->urlElements[self::SQL_ORDER_BY]) {
                $order = $this->initVar(self::SQL_ORDER, self::SQL_ORDER_ASC);
                $strSort .= '_'.strtolower($order);
                $newOrder = $order==self::SQL_ORDER_ASC ? self::SQL_ORDER_DESC : self::SQL_ORDER_ASC;
                $this->urlElements[self::SQL_ORDER] = $newOrder;
            } else {
                $this->urlElements[self::SQL_ORDER] = self::SQL_ORDER_ASC;
            }
            $cellContent = HtmlUtils::getLink($this->strContent, UrlUtils::getAdminUrl($this->urlElements));
            return $this->getBalise($this->strType, $cellContent, [self::ATTR_CLASS => $strSort]);
        } else {
            $attributes = $this->strClass=='' ? [] : [self::ATTR_CLASS => $this->strClass];
            return $this->getBalise($this->strType, $this->strContent, $attributes);
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
}
