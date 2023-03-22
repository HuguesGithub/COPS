<?php
namespace core\domain;

use core\bean\CopsIndexReferenceBean;
use core\services\CopsIndexServices;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndexReference
 * @author Hugues
 * @version 1.23.02.15
 * @since 1.23.02.15
 */
class CopsIndexReferenceClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $idIdxReference;
    protected $nomIdxReference;
    protected $prenomIdxReference;
    protected $akaIdxReference;
    protected $natureIdxId;
    protected $descriptionPJ;
    protected $descriptionMJ;
    protected $reference; // deprecated
    protected $code;
  
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.23.02.15
     * @since 1.23.02.15
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsIndexReferenceClass';

        $this->objCopsIndexServices = new CopsIndexServices();
    }

    /**
     * @param array $row
     * @return CopsIndexReferenceClass
     * @version 1.23.02.15
     * @since 1.23.02.15
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsIndexReferenceClass(), $row); }

    /**
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getBean()
    { return new CopsIndexReferenceBean($this); }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * Applique les contrôles attendus sur certains champs.
     * @return boolean
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function isFieldsValid()
    {
        $blnOk = true;
        if ($this->nomIdxReference==''
            || $this->code==''
            || $this->getNature()->getField(self::FIELD_ID_IDX_NATURE)==''
        ) {
            $blnOk = false;
        } else {
            // TODO : les Références associées existent-elles ?
        }
        return $blnOk;
    }
    
    /**
     * Retourne la Nature associée à l'entité
     * @return CopsIndexNatureClass
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getNature()
    { return $this->objCopsIndexServices->getCopsIndexNature($this->natureIdxId); }

    /**
     * Retourne la chaîne de caractère associée au code de l'enttié
     * @return string
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getStrCode()
    {
        switch ($this->code) {
            case 2 :
                $strCode = 'Rouge';
                break;
            case 1 :
                $strCode = 'Bleu';
                break;
            case -1 :
                $strCode = 'Hors Storyline';
                break;
            default :
                $strCode = 'Standard';
                break;
        }
        return $strCode;
    }

}
