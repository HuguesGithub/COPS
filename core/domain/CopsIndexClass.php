<?php
namespace core\domain;

use core\services\CopsIndexServices;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndex
 * @author Hugues
 * @version 1.22.09.06
 * @since 1.23.02.15
 */
class CopsIndexClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $idIdx;
    protected $referenceIdxId;
    protected $tomeIdxId;
    protected $page;
  
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.09.06
     * @since 1.22.09.06
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsIndexClass';

        $this->objCopsIndexServices = new CopsIndexServices();
    }

    /**
     * @param array $row
     * @return CopsIndexClass
     * @version 1.22.09.06
     * @since 1.22.09.06
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsIndexClass(), $row); }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getBean()
    { return new CopsIndexBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * Retourne le Tome associé à l'entité
     * @return CopsIndexTomeClass
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getTome()
    { return $this->objCopsIndexServices->getCopsIndexTome($this->tomeIdxId); }

    /**
     * Retourne la Référence associée à l'entité
     * @return CopsIndexReferenceClass
     * @since 1.23.02.18
     * @version 1.23.02.18
     */
    public function getReference()
    { return $this->objCopsIndexServices->getCopsIndexReference($this->referenceIdxId); }

    /**
     * Retourne l'Abréviation du Tome et la Page de la référence
     * @return string
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getTomeAndPage()
    { return $this->getTome()->getField(self::FIELD_ABR_IDX_TOME).$this->getField(self::FIELD_PAGE); }
}
