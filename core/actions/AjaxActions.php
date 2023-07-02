<?php
namespace core\actions;

use core\utils\SessionUtils;

/**
 * AjaxActions
 * @author Hugues
 * @since v1.23.06.21
 * @version 1.23.07.02
 */
class AjaxActions extends LocalActions
{
    /**
     * Gère les actions Ajax
     * @since v1.23.06.21
     * @version 1.23.07.02
     */
    public static function dealWithAjax(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        switch ($ajaxAction) {
            case 'saveData' :
                $returned = CopsPlayerActions::dealWithStatic();
            break;
            case 'csvExport':
                // TODO : $returned = CopsIndexActions::dealWithStatic($_POST);
            break;
            default :
                $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction.'<br>';
            break;
        }
        return $returned;
    }

}
