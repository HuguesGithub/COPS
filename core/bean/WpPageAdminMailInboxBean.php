<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminMailInboxBean
 * @author Hugues
 * @since 1.22.11.11
 * @version v1.23.05.28
 */
class WpPageAdminMailInboxBean extends WpPageAdminMailBean
{
    public function __construct()
    {
        parent::__construct();

        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, self::LABEL_INBOX, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * @since 1.22.11.11
     * @version 1.22.11.17
     */
    public function getOngletContent()
    {
        return $this->getOngletContentMutual(self::LABEL_INBOX, 'section-inbox');
    }
}
