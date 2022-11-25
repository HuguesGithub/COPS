<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsEnqueteChronologie
 * @author Hugues
 * @version 1.22.09.23
 * @since 1.22.09.23
 */
class CopsEnqueteChronologie extends LocalDomain
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    
    protected $idxEnquete;
    protected $dateHeure;
    protected $faits;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @param array $attributes
     * @version 1.22.09.23
     * @since 1.22.09.23
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->stringClass = 'CopsEnqueteChronologie';
    }
    /**
     * @param array $row
     * @return CopsEnqueteChronologie
     * @version 1.22.09.23
     * @since 1.22.09.23
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsEnqueteChronologie(), $row); }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    
}
