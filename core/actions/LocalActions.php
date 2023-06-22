<?php
namespace core\actions;

use core\interfaceimpl\FieldsInterface;
use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;

/**
 * LocalActions
 * @author Hugues
 * @since v1.23.06.21
 * @version 1.23.06.25
 */
class LocalActions implements ConstantsInterface, LabelsInterface, FieldsInterface
{
  /**
   * @return bool
   * /
  public static function isAdmin()
  { return current_user_can('manage_options'); }

    /**
     * @since v1.23.06.21
     * @version 1.23.06.25
     */
    public function getToastContentJson($type, $title, $msg)
    { return '{"toastContent": '.json_encode($this->getToastContent($type, $title, $msg), JSON_THROW_ON_ERROR).'}'; }

    /**
     * @since v1.23.06.21
     * @version 1.23.06.25
     */
    public function getToastContent($type, $title, $msg)
    {
        $strContent  = '<div class="toast fade show bg-'.$type.'">';
        $strContent .= '  <div class="toast-header">';
        $strContent .= '    <i class="fas fa-exclamation-circle me-2"></i>';
        $strContent .= '    <strong class="me-auto">'.$title.'</strong>';
        $strContent .= '    <small>à l\'instant</small>';
        $strContent .= '  </div>';
        $strContent .= '  <div class="toast-body">';
        $strContent .= $msg;
        $strContent .= '  </div>';
        $strContent .= '</div>';
        return $strContent;
    }
}
