<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * CopsBean
 * @author Hugues
 * @since 1.22.09.23
 * @version v1.23.07.29
 */
class CopsBean extends UtilitiesBean
{
    public function __construct()
    {
        $this->urlOnglet = '/admin/?'.self::CST_ONGLET.'=';
    }

    /**
     * @since v1.23.07.29
     * @version v1.23.07.29
     */
    public static function getBuiltTableHeader(array $headerElement, array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        foreach ($headerElement as $element) {
            $classe = $element['classe'] ?? self::CSS_COL;
            if (isset($element['abbr'])) {
                $tag = HtmlUtils::getBalise('abbr', $element['label'], [self::ATTR_TITLE=>$element['abbr']]);
            } else {
                $tag = $element['label'];
            }
            $objTableauCell = new TableauCellHtmlBean($tag, self::TAG_TH, $classe);
            if (isset($element['sortable'])) {
                $queryArg[self::SQL_ORDER_BY] = $element['sortable'];
                $objTableauCell->ableSort($queryArg);
            }
            $objRow->addCell($objTableauCell);
        }

        $objHeader = new TableauTHeadHtmlBean();
        $objHeader->addRow($objRow);

        return $objHeader;
    }
}
