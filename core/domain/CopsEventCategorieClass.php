<?php
namespace core\domain;

use core\services\CopsEventServices;

/**
 * Classe CopsEventCategorieClass
 * @author Hugues
 * @since v1.23.05.03
 * @version v1.23.05.07
 */
class CopsEventCategorieClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;

    protected $categorieLibelle;
    protected $categorieCouleur;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @version 1.22.06.25
     * @since 1.22.06.25
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsEventCategorieClass';
        $this->CopsEventServices = new CopsEventServices();
    }

  /**
   * @version 1.22.06.25
   * @since 1.22.06.25
   */
  public static function convertElement($row): CopsEventCategorieClass
  { return parent::convertRootElement(new CopsEventCategorieClass(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

}
