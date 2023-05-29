<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminMailTrashBean
 * @author Hugues
 * @since 1.22.11.12
 * @version v1.23.05.28
 */
class WpPageAdminMailTrashBean extends WpPageAdminMailBean
{
    public function __construct()
    {
        parent::__construct();
        
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, self::LABEL_TRASH, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Si le folder du mail n'est pas la Corbeille, on le met dans la Corbeille
        if ($this->objCopsMailJoint->getField(self::FIELD_FOLDER_ID)!=6) {
            $this->objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 6);
        } else {
            // Sinon, on le supprime définitivement
            $this->objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 10);
        }
        $this->CopsMailServices->updateMailJoint($this->objCopsMailJoint);
    }
    
    /**
     * @since 1.22.11.12
     * @version 1.22.11.17
     */
    public function getOngletContent()
    {
        return $this->getOngletContentMutual(self::LABEL_TRASH, 'section-trash');
    }
}
