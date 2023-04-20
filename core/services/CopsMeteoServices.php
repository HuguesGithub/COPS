<?php
namespace core\services;

use core\domain\CopsSoleilClass;
use core\daoimpl\CopsMeteoDaoImpl;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsMeteoServices
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.10.17
 */
class CopsMeteoServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version 1.22.04.29
     * @since 1.22.04.29
     */
    public function __construct()
    {
        $this->Dao = new CopsMeteoDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_METEO
    ////////////////////////////////////
    public function getMeteos(string $dateMeteo): array
    {
        $prepAttributes = [$dateMeteo];
        return $this->Dao->getMeteos($prepAttributes);
    }

    ////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_SOLEIL
    ////////////////////////////////////
    public function getSoleil(string $strJour): CopsSoleilClass
    {
        $prepAttributes = [$strJour];
        $rows = $this->Dao->getSoleil($prepAttributes);
        return new CopsSoleilClass($rows[0]);
    }
    ////////////////////////////////////


}
