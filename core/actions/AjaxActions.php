<?php
namespace core\actions;

use core\utils\SessionUtils;

/**
 * AjaxActions
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.05
 */
class AjaxActions extends LocalActions
{
    /**
     * Gère les actions Ajax
     * @since v1.23.06.21
     * @version v1.23.08.05
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
            case 'tchat':
            case 'refresh':
            case 'checkNotif':
                    $returned = CopsTchatActions::dealWithStatic();
            break;
            default :
                $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction.'<br>';
            break;
        }
        return $returned;
    }

}
