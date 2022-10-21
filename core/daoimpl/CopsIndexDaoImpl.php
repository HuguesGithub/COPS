<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsIndexDaoImpl
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.22.10.21
 */
class CopsIndexDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_index";
        $this->dbTable_cin  = "wp_7_cops_index_nature";
        ////////////////////////////////////
    
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX
    //////////////////////////////////////////////////
    /**
     * @param array
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndex($prepObject)
    {
        $request  = $this->select."WHERE id = '%s';";
        $prepRequest  = MySQL::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQL::wpdbSelect($prepRequest);
    }
  
    /**
     * @param array $attributes
     * @return array [CopsIndex]
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexes($attributes)
    {
        $request  = $this->select;
        $request .= "WHERE natureId LIKE '%s' ";
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
                $objItems[] = CopsIndex::convertElement($row);
            }
        }
        return $objItems;
    }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function updateIndex($objStd)
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
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function insertIndex(&$objStd)
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
    // WP_7_COPS_INDEX_NATURE
    //////////////////////////////////////////////////
    /**
     * @param array
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexNature($prepObject)
    {
        $request  = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature WHERE idIdxNature = '%s';";
        $prepRequest  = MySQL::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQL::wpdbSelect($prepRequest);
    }
	
    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexNatures()
    {
        $request  = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature ORDER BY nomIdxNature ASC;";
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQL::wpdbSelect($request);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = new CopsIndexNature($row);
            }
        }
        return $objItems;
    }
	
    /**
     * @param array
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
	public function getCopsIndexNatures($prepObject)
	{
		$request = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature WHERE nomIdxNature = '%s';";
        $prepRequest = MySQL::wpdbPrepare($request, $prepObject);
        return MySQL::wpdbSelect($prepRequest);
	}

}
