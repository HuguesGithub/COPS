<?php
namespace core\domain;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsSoleilClass
 * @author Hugues
 * @since 1.23.4.20
 * @version 1.23.4.20
 */
class CopsSoleilClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $dateSoleil;
    protected $heureLever;
    protected $heureCoucher;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsSoleilClass';
    }

    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public static function convertElement(array $row): CopsSoleilClass
    {
        return parent::convertRootElement(new CopsSoleilClass(), $row);
    }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

}
