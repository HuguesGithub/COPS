<?php
namespace core\daoimpl;

use core\domain\CopsIndexClass;
use core\domain\CopsIndexReferenceClass;
use core\domain\CopsIndexNatureClass;
use core\domain\CopsIndexTomeClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsIndexDaoImpl
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.23.2.15
 */
class CopsIndexDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.10.21
     * @version 1.23.2.15
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_index";
        $this->dbTable_cir  = "wp_7_cops_index_reference";
        $this->dbTable_cin  = "wp_7_cops_index_nature";
        $this->dbTable_cit  = "wp_7_cops_index_tome";
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
     * @param CopsIndexClass [E|S]
     * @since 1.23.02.20
     * @version 1.23.03.15
     */
    public function insertIndex(&$objCopsIndex)
    {
        // On défini la requête d'insertion
        $request  = "INSERT INTO ".$this->dbTable;
        $request .= " (referenceIdxId, tomeIdxId, page) ";
        $request .= "VALUES ('%s', '%s', '%s');";
        $this->insertDaoImpl($objCopsIndex, $request);
        $objCopsIndex->setField(self::FIELD_ID, MySQLClass::getLastInsertId());
    }

    /**
     * @param CopsIndexClass
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function updateIndex($objCopsIndex)
    {
        // On défini la requête de mise à jour
        $request  = "UPDATE ".$this->dbTable;
        $request .= " SET referenceIdxId='%s', tomeIdxId='%s', page='%s'";
        $request .= " WHERE id = '%s';";
        $this->updateDaoImpl($objCopsIndex, $request, self::FIELD_ID);
    }

    /**
     * @param array
     * @return array
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndex($prepObject)
    {
        $request  = "SELECT idIdx, referenceIdxId, tomeIdxId, page";
        $request .= " FROM ".$this->dbTable;
        $request .= " WHERE id = '%s';";
        return $this->selectDaoImpl($request, $prepObject);
    }
  
    /**
     * @param array $attributes
     * @return array [CopsIndex]
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexes($attributes)
    {
        $request  = "SELECT idIdx, referenceIdxId, tomeIdxId, page";
        $request .= " FROM ".$this->dbTable;
        $request .= " INNER JOIN ".$this->dbTable_cit." ON tomeIdxId = idIdxTome ";
        $request .= " WHERE referenceIdxId LIKE '%s'";
        $request .= " ORDER BY idIdxTome ASC;";
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsIndexClass::convertElement($row);
            }
        }
        return $objItems;
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_REFERENCE
    //////////////////////////////////////////////////

    /**
     * @param CopsIndexReferenceClass $objCopsIndexReference [E|S]
     * @since 1.23.02.20
     * @version 1.23.03.16
     */
    public function insertIndexReference(&$objCopsIndexReference)
    {
        // On défini la requête d'insertion
        $request  = "INSERT INTO ".$this->dbTable_cir;
        $request .= " (nomIdxReference, prenomIdxReference, akaIdxReference, natureIdxId, descriptionPJ";
        $request .= ", descriptionMJ, code) ";
        $request .= "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');";
        $this->insertDaoImpl($objCopsIndexReference, $request);
        $objCopsIndexReference->setField(self::FIELD_ID_IDX_REF, MySQLClass::getLastInsertId());
    }

    /**
     * @param CopsIndexReferenceClass
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function updateIndexReference($objCopsIndexReference)
    {
        // On défini la requête de mise à jour
        $request  = "UPDATE ".$this->dbTable_cir;
        $request .= " SET nomIdxReference = '%s', prenomIdxReference = '%s', akaIdxReference = '%s',";
        $request .= " natureIdxId = '%s', descriptionPJ = '%s', descriptionMJ = '%s', reference = '%s', code = '%s'";
        $request .= " WHERE idIdxReference = '%s';";
        $this->updateDaoImpl($objCopsIndexReference, $request, self::FIELD_ID_IDX_REF);
    }

    /**
     * @param array $attributes
     * @return CopsIndexReference
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexReference($attributes)
    {
        $request  = "SELECT idIdxReference, nomIdxReference, prenomIdxReference, akaIdxReference, natureIdxId, ";
        $request .= "descriptionPJ, descriptionMJ, reference, code ";
        $request .= "FROM ".$this->dbTable_cir." ";
        $request .= "WHERE idIdxReference LIKE '%s';";
        return $this->selectDaoImpl($request, $prepObject);
    }

    /**
     * @param array $attributes
     * @return array [CopsIndexReference]
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getIndexReferences($attributes)
    {
        $request  = "SELECT idIdxReference, nomIdxReference, prenomIdxReference, akaIdxReference, natureIdxId, ";
        $request .= "descriptionPJ, descriptionMJ, reference, code ";
        $request .= "FROM ".$this->dbTable_cir." ";
        $request .= "WHERE natureIdxId LIKE '%s' ";
        $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
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
                $objItems[] = CopsIndexReferenceClass::convertElement($row);
            }
        }
        return $objItems;
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
        $request  = "SELECT idIdxNature, nomIdxNature FROM ".$this->dbTable_cin;
        $request .= " WHERE idIdxNature = '%s';";
        $prepRequest  = MySQLClass::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQLClass::wpdbSelect($prepRequest);
    }
    
    /**
     * @param array
     * @return array[CopsIndexNatureClass]
     * @since 1.22.10.21
     * @version 1.23.02.15
     */
    public function getCopsIndexNatures($attributes)
    {
        $request  = "SELECT idIdxNature, nomIdxNature FROM ".$this->dbTable_cin;
        $request .= " WHERE nomIdxNature LIKE '%s'";
        $request .= " ORDER BY nomIdxNature ASC;";
        $prepRequest = vsprintf($request, $attributes);

        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsIndexNatureClass::convertElement($row);
            }
        }
        return $objItems;
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_TOME
    //////////////////////////////////////////////////
    /**
     * @param array
     * @return array[CopsIndexTomeClass]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexTomes($attributes)
    {
        $request  = "SELECT idIdxTome, nomIdxTome, abrIdxTome FROM ".$this->dbTable_cit;
        $request .= " WHERE abrIdxTome = '%s'";
        $request .= " ORDER BY nomIdxTome ASC;";
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////

        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = CopsIndexTomeClass::convertElement($row);
            }
        }
        return $objItems;
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_TOME
    //////////////////////////////////////////////////
    /**
     * @param array
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexTome($prepObject)
    {
        $request  = "SELECT idIdxTome, nomIdxTome, abrIdxTome FROM ".$this->dbTable_cit;
        $request .= " WHERE idIdxTome = '%s';";
        $prepRequest  = MySQLClass::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQLClass::wpdbSelect($prepRequest);
    }

}
