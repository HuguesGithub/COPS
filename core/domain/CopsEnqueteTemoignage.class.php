<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsEnquetePersonnalite
 * @author Hugues
 * @version 1.22.09.23
 * @since 1.22.09.23
 */
class CopsEnquetePersonnalite extends LocalDomain
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
    protected $idxBddFcid;
    protected $idxBddDabis;
    protected $fcfaDetail;
    protected $lheDetail;
    protected $hqvDetail;
    protected $toaDetail;
    protected $cdndvDetail;
    protected $tDetail;
    protected $aDetail;

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
        $this->stringClass = 'CopsEnquetePersonnalite';
    }
    /**
     * @param array $row
     * @return CopsEnquetePersonnalite
     * @version 1.22.09.23
     * @since 1.22.09.23
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsEnquetePersonnalite(), $row); }

    /**
     * @return CopsEnquetePersonnaliteBean
     * @version 1.22.09.23
     * @since 1.22.09.23
     */
    public function getBean()
    { return new CopsEnquetePersonnaliteBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    
}
