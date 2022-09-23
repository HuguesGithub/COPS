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
		$this->dbTable_cep  = "wp_7_cops_enquete_chronologie";
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
		$request .= "WHERE 1=1 AND statutEnquete LIKE '%s' ";
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
	
	public function getFields()
	{
		$arrFields = array();
		$rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable.";");
		foreach ($rows as $row) {
		  $arrFields[] = $row->Field;
		}
		return $arrFields;
	}
	
}
