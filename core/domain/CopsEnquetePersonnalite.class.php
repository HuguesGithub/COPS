<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsEnqueteTemoignage
 * @author Hugues
 * @version 1.22.09.23
 * @since 1.22.09.23
 */
class CopsEnqueteTemoignage extends LocalDomain
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
    protected $nomTemoin;
    protected $idxBddDabis;
    protected $qualiteTemoin;
    protected $lienTemoin;
    protected $temoignageAlibiTemoin;
    protected $notesTemoin;
    protected $verifTemoin;
    protected $relation;
    
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
        $this->stringClass = 'CopsEnqueteTemoignage';
    }
    /**
     * @param array $row
     * @return CopsEnqueteTemoignage
     * @version 1.22.09.23
     * @since 1.22.09.23
     */
    public static function convertElement($row)
    { return parent::convertRootElement(new CopsEnqueteTemoignage(), $row); }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    
}
