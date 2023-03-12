<?php
namespace core\domain;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;
use core\interfaceimpl\UrlsInterface;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe GlobalDomain
 * @author Hugues.
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class GlobalDomainClass implements ConstantsInterface, LabelsInterface, UrlsInterface
{
  protected $stringClass;

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public function __construct($attributes=array())
  {
    if (!empty($attributes)) {
      foreach ($attributes as $key => $value) {
        $this->setField($key, $value);
      }
    }
  }

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public function setField($key, $value)
  { if (property_exists($this, $key)) { $this->{$key} = $value; } }
  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public function getField($key)
  { return (property_exists($this, $key) ? $this->{$key} : null); }

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public static function convertRootElement($Obj, $row)
  {
    $vars = $Obj->getClassVars();
    if (!empty($vars)) {
      foreach ($vars as $key => $value) {
        if ($key=='stringClass') {
          continue;
        }
        $Obj->setField($key, $row->{$key});
      }
    }
    return $Obj;
  }

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  protected function isAdmin()
  { return current_user_can('manage_options'); }

  /**
   * @return array
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function getClassVars()
  { return get_class_vars($this->stringClass); }

}
