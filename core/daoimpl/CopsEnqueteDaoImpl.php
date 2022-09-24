<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsEnqueteDaoImpl
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.23
 */
class CopsEnqueteDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.09.16
     * @version 1.22.09.23
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_enquete";
        $this->dbTable_cec  = "wp_7_cops_enquete_chronologie";
        $this->dbTable_cep  = "wp_7_cops_enquete_personnalite";
        $this->dbTable_cet  = "wp_7_cops_enquete_temoignage";
        ////////////////////////////////////
    
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_ENQUETE
    //////////////////////////////////////////////////
    /**
     * @param array
     * @since 1.22.09.21
     * @version 1.22.09.23
     */
    public function getEnquete($prepObject)
    {
        $request  = $this->select."WHERE id = '%s';";
        $prepRequest  = MySQL::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQL::wpdbSelect($prepRequest);
    }
  
    /**
     * @param array $attributes
     * @return array [CopsEnquete]
     * @since 1.22.09.20
     * @version 1.22.09.20
     */
    public function getEnquetes($attributes)
    {
        $request  = $this->select;
        $request .= "WHERE statutEnquete LIKE '%s' ";
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
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
                $objItems[] = CopsEnquete::convertElement($row);
            }
        }
        return $objItems;
    }

    /**
     * @since 1.22.09.16
     * @version 1.22.09.23
     */
    public function updateEnquete($objStd)
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
     * @since 1.22.09.16
     * @version 1.22.09.23
     */
    public function insertEnquete(&$objStd)
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

    //////////////////////////////////////////////////
    // WP_7_COPS_ENQUETE_CHRONOLOGIE
    //////////////////////////////////////////////////
    /**
     * @since 1.22.09.23
     * @version 1.22.09.23
     */
    public function getEnqueteChronologies($attributes)
    {
        $request = $this->buildSelectRequest($this->dbTable_cec);
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
                $objItems[] = CopsEnqueteChronologie::convertElement($row);
            }
        }
        return $objItems;
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_ENQUETE_PERSONNALITE
    //////////////////////////////////////////////////
    /**
     * @since 1.22.09.23
     * @version 1.22.09.23
     */
    public function getEnquetePersonnalites($attributes)
    {
        $request = $this->buildSelectRequest($this->dbTable_cep);
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
                $objItems[] = CopsEnquetePersonnalite::convertElement($row);
            }
        }
        return $objItems;
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_ENQUETE_TEMOIGNAGE
    //////////////////////////////////////////////////
    /**
     * @since 1.22.09.23
     * @version 1.22.09.23
     */
    public function getEnqueteTemoignages($attributes)
    {
        $request = $this->buildSelectRequest($this->dbTable_cet);
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
                $objItems[] = CopsEnqueteTemoignage::convertElement($row);
            }
        }
        return $objItems;
    }
    
    private function buildSelectRequest($dbTable)
    {
        $arrFields = array();
        $rows = MySQL::wpdbSelect("DESCRIBE ".$dbTable.";");
        foreach ($rows as $row) {
            $arrFields[] = $row->Field;
        }
        $request  = "SELECT ".implode(', ', $arrFields)." ";
        $request .= "FROM ".$dbTable." ";
        $request .= "WHERE idxEnquete = %s;";
        return $request;
    }
}
