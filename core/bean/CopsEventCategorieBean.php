<?php
namespace core\bean;

/**
 * CopsEventCategorieBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.05.21
 */
class CopsEventCategorieBean extends UtilitiesBean
{
    public function __construct($obj)
    {
        $this->objEventCategorie = $obj;
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getOption(): string
    {
        $strContent = $this->objEventCategorie->getField(self::FIELD_CATEG_LIBELLE);

        $attributes = [
            self::ATTR_VALUE => $this->objEventCategorie->getField(self::FIELD_ID),
        ];

        return $this->getBalise(self::TAG_OPTION, $strContent, $attributes);
    }
}
