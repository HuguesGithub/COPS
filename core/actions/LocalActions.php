<?php
namespace core\actions;

use core\interfaceimpl\FieldsInterface;
use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;

date_default_timezone_set('Europe/Paris');

/**
 * LocalActions
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.05
 */
class LocalActions implements ConstantsInterface, LabelsInterface, FieldsInterface
{

    /**
     * @since v1.23.06.21
     * @version v1.23.08.05
     */
    public function getToastContentJson($type, $title, $msg): string
    { return '{"toastContent": '.json_encode($this->getToastContent($type, $title, $msg), JSON_THROW_ON_ERROR).'}'; }

    /**
     * @since v1.23.06.21
     * @version v1.23.08.05
     */
    public function getToastContent($type, $title, $msg)
    {
        $strContent  = '<div class="toast fade show bg-'.$type.'">';
        $strContent .= '  <div class="toast-header">';
        $strContent .= '    <i class="fas fa-exclamation-circle mx-2"></i>';
        $strContent .= '    <strong class="mx-auto">'.$title.'</strong>';
        $strContent .= '    <small>à l\'instant</small>';
        $strContent .= '  </div>';
        $strContent .= '  <div class="toast-body">'.$msg.'</div>';
        $strContent .= '</div>';
        return $strContent;
    }
}
