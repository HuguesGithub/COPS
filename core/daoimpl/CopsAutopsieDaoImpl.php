<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsAutopsieDaoImpl
 * @author Hugues
 * @since 1.22.10.09
 * @version 1.22.10.09
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
     * @param array
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsie($prepObject)
    {
        $request  = $this->select."WHERE id = '%s';";
        $prepRequest  = MySQL::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQL::wpdbSelect($prepRequest);
    }
    
    /**
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsies($attributes)
    {
        $arrFields  = $this->getFields();
        $request  = "SELECT ".implode(', ', $arrFields)." ";
        $request .= "FROM ".$this->dbTable." ";
        $request .= "WHERE idxEnquete LIKE '%s' ";
        $request .= "ORDER BY dStart DESC;";
        
        $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE_FILTERS]);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQL::wpdbSelect($prepRequest);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsAutopsie::convertElement($row);
            }
        }
        return $objItems;
    }
    
    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function updateAutopsie($objStd)
    {
        $request  = $this->update."WHERE id = '%s';";
        
        $prepObject = array();
        $arrFields  = $this->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field) {
            $prepObject[] = $objStd->getField($field);
        }
        $prepObject[] = $objStd->getField(self::FIELD_ID);
        
        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
    }
    
    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function insertAutopsie(&$objStd)
    {
        $request  = $this->insert;
        
        $prepObject = array();
        $arrFields  = $this->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field) {
            $prepObject[] = $objStd->getField($field);
        }
        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
        $objStd->setField(self::FIELD_ID, MySQL::getLastInsertId());
    }
    
}
