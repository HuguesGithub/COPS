<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEventCategorieBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.06.18
 */
class CopsEventCategorieBean extends UtilitiesBean
{
    public function __construct($obj)
    {
        $this->objEventCategorie = $obj;
    }

    /**
     * @since v1.23.06.18
     * @version v1.23.06.18
     */
    public function getLi(array $urlElements): string
    {
        $label = $this->objEventCategorie->getField(self::FIELD_CATEG_LIBELLE);
        $urlElements['filterCateg'] = $this->objEventCategorie->getField(self::FIELD_ID);
        $href = UrlUtils::getAdminUrl($urlElements);
        $liContent = HtmlUtils::getLink($label, $href, 'dropdown-item');
        return HtmlUtils::getBalise(self::TAG_LI, $liContent);
    }

}
