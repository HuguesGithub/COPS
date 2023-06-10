<?php
namespace core\bean;

/**
 * Classe TableauTFootHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.06.11
 */
class TableauTFootHtmlBean extends TableauContentHtmlBean
{

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function __construct()
    {
        parent::__construct();
        $this->strType = self::TAG_TFOOT;
    }
    
}
