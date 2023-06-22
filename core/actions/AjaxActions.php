<?php
namespace core\actions;

/**
 * AjaxActions
 * @author Hugues
 * @since v1.23.06.21
 * @version 1.23.06.25
 */
class AjaxActions extends LocalActions
{
    /**
     * Gère les actions Ajax
     * @since v1.23.06.21
     * @version 1.23.06.25
     */
    public static function dealWithAjax(): string
    {
        $ajaxAction = $_POST[self::AJAX_ACTION];
        switch ($ajaxAction) {
            case 'saveData' :
                $returned = CopsPlayerActions::dealWithStatic();
            break;
            case 'csvExport':
                //$returned = CopsIndexActions::dealWithStatic($_POST);
            break;
            default :
                //$saisie = stripslashes((string) $_POST[self::AJAX_ACTION]);
                //$returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
            break;
        }
        return $returned;
    }

}
