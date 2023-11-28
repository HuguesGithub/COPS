<?php
namespace core\actions;

use core\utils\SessionUtils;

/**
 * AjaxActions
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.12.02
 */
class AjaxActions extends LocalActions
{
    /**
     * Gère les actions Ajax
     * @since v1.23.06.21
     * @version v1.23.12.02
     */
    public static function dealWithAjax(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        switch ($ajaxAction) {
            case self::AJAX_SAVE_DATA :
                $returned = CopsPlayerActions::dealWithStatic();
            break;
            case self::AJAX_FIND_ADDRESS :
            case self::AJAX_DEL_GUY_ADDRESS :
            case self::AJAX_INS_GUY_ADDRESS :
            case self::AJAX_FIND_PHONE :
            case self::AJAX_DEL_GUY_PHONE :
            case self::AJAX_INS_GUY_PHONE :
                $returned = CopsCalActions::dealWithStatic();
            break;
            case self::AJAX_CSV_EXPORT :
                $returned = static::todoActions($ajaxAction, 'CopsIndexActions');
            break;
            case self::AJAX_TCHAT :
            case self::AJAX_REFRESH :
            case self::AJAX_CHECK_NOTIF :
                $returned = CopsTchatActions::dealWithStatic();
            break;
            default :
                $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction.'<br>';
            break;
        }
        return $returned;
    }

    /**
     * @since v1.23.12.02
     */
    public static function todoActions(string $ajaxAction, string $classAction): string
    {
        $returned  = 'WIP dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction;
        $returned .= ' est en attente de développement dans '.$classAction.'.<br>';
        return $returned;
    }
}
