<?php
namespace core\domain;

use core\domain\CopsAutopsieClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsAutopsieDaoImpl
 * @author Hugues
 * @since 1.22.10.09
 * @version 1.23.03.15
 */
class CopsAutopsieDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable = "wp_7_cops_autopsie";
        ////////////////////////////////////
    
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_AUTOPSIE
    //////////////////////////////////////////////////
    
    /**
     * @param CopsAutopsieClass [E|S]
     * @since 1.22.10.10
     * @version 1.23.03.16
     */
    public function insertAutopsie(&$objAutopsie)
    {
        // On défini la requête d'insertion
        $request  = "INSERT INTO ".$this->dbTable;
        $request .= " (idxEnquete, data, dStart) ";
        $request .= "VALUES ('%s', '%s', '%s');";
        $this->insertDaoImpl($objAutopsie, $request);
        $objAutopsie->setField(self::FIELD_ID, MySQLClass::getLastInsertId());
    }
    
    /**
     * @param CopsAutopsieClass
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function updateAutopsie($objAutopsie)
    {
        // On défini la requête de mise à jour
        $request  = "UPDATE ".$this->dbTable;
        $request .= " SET idxEnquete='%s', data='%s', dStart='%s'";
        $request .= " WHERE id = '%s';";
        $this->updateDaoImpl($objAutopsie, $request, self::FIELD_ID);
    }

    /**
     * @param array
     * @since 1.22.10.09
     * @version 1.23.03.15
     */
    public function getAutopsie($prepObject)
    {
        $request  = "SELECT idxEnquete, data, dStart";
        $request .= " FROM ".$this->dbTable;
        $request .= " WHERE id = '%s';";
        return $this->selectDaoImpl($request, $prepObject);
    }
    
    /**
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsies($attributes)
    {
        $request  = "SELECT id, idxEnquete, data, dStart";
        $request .= " FROM ".$this->dbTable;
        $request .= " WHERE idxEnquete LIKE '%s'";
        $request .= " ORDER BY dStart DESC;";
        $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsAutopsieClass::convertElement($row);
            }
        }
        return $objItems;
    }
    
}
