<?php
declare(strict_types=1);

namespace core\domain;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\FieldsInterface;
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
class GlobalDomainClass implements ConstantsInterface, LabelsInterface, UrlsInterface, FieldsInterface
{
  protected $stringClass;

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    public function __construct($attributes=[])
    {
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $this->setField($key, $value);
            }
        }
    }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    public function setField(string $key, mixed $value): void
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
    }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    public function getField(string $key): mixed
    { return (property_exists($this, $key) ? $this->{$key} : null); }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    public static function convertRootElement(LocalDomainClass $objLocalDomain, array $row): LocalDomainClass
    {
        $vars = $objLocalDomain->getClassVars();
        if (!empty($vars)) {
            foreach ($vars as $key => $value) {
                if ($key=='stringClass') {
                    continue;
                }
                $objLocalDomain->setField($key, $row->{$key});
            }
        }
        return $objLocalDomain;
    }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    protected function isAdmin(): bool
    { return current_user_can('manage_options'); }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
    public function getClassVars(): array
    { return get_class_vars($this->stringClass); }

}
