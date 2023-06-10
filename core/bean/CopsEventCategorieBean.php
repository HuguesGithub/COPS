<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * CopsEventCategorieBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.05.28
 */
class CopsEventCategorieBean extends UtilitiesBean
{
    public function __construct($obj)
    {
        $this->objEventCategorie = $obj;
    }

    public function getOption(string $selValue=''): string
    {
        $label = $this->objEventCategorie->getField(self::FIELD_CATEG_LIBELLE);
        $value = $this->objEventCategorie->getField(self::FIELD_ID);
        return HtmlUtils::getOption($label, $value, $selValue==$value);
    }

}
