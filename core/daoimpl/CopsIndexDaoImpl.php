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
     * @param array
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndex($prepObject)
    {
        $request  = $this->select."WHERE id = '%s';";
        $prepRequest  = MySQLClass::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQLClass::wpdbSelect($prepRequest);
    }
  
    /**
     * @param array $attributes
     * @return array [CopsIndex]
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexes($attributes)
    {
        $request  = "SELECT idIdx, referenceIdxId, tomeIdxId, page FROM ".$this->dbTable;
        $request .= " INNER JOIN ".$this->dbTable_cit." ON tomeIdxId = idIdxTome ";
        $request .= " WHERE referenceIdxId LIKE '%s' ";
        $request .= "ORDER BY idIdxTome ASC;";
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

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     *
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
    */

    /**
     * @param CopsIndex [E|S]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function insertIndex(&$obCopsIndex)
    {
        // On défini la requête d'insertion
        $request  = "INSERT INTO ".$this->dbTable." (referenceIdxId, tomeIdxId, page) VALUES ('%s', '%s', '%s');";
        
        // On prépare les paramètres, en excluant le premier (l'id)
        $prepObject = array();
        $arrFields  = $obCopsIndex->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field => $value) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $obCopsIndex->getField($field);
        }

        // On prépare la requête, l'exécute et met à jour l'id de l'objet créé.
        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        MySQLClass::wpdbQuery($sql);
        $obCopsIndex->setField(self::FIELD_ID, MySQLClass::getLastInsertId());
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_REFERENCE
    //////////////////////////////////////////////////

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
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQLClass::wpdbSelect($prepRequest);
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

    /**
     * @param array $objCopsIndexReference
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function updateIndexReference($objCopsIndexReference)
    {
        $request  = "UPDATE ".$this->dbTable_cir." SET ";
        $request .= " nomIdxReference = '%s', prenomIdxReference = '%s', akaIdxReference = '%s',";
        $request .= " natureIdxId = '%s', descriptionPJ = '%s', descriptionMJ = '%s',";
        $request .= " reference = '%s', code = '%s' ";
        $request .= " WHERE idIdxReference = '%s';";

        $prepObject = array();
        $arrFields  = $objCopsIndexReference->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field => $value) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $objCopsIndexReference->getField($field);
        }
        $prepObject[] = $objCopsIndexReference->getField(self::FIELD_ID_IDX_REF);

        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        MySQLClass::wpdbQuery($sql);
    }

    /**
     * @param array $objCopsIndexReference
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function insertIndexReference($objCopsIndexReference)
    {
        $request  = "INSERT INTO ".$this->dbTable_cir." (nomIdxReference, prenomIdxReference, akaIdxReference ";
        $request .= ", natureIdxId, descriptionPJ, descriptionMJ, code) VALUES ('%s', '%s', '%s', '%s', ";
        $request .= "'%s', '%s', '%s');";

        $prepObject = array();
        $arrFields  = $objCopsIndexReference->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field => $value) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $objCopsIndexReference->getField($field);
        }
        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        MySQLClass::wpdbQuery($sql);
        $objCopsIndexReference->setField(self::FIELD_ID_IDX_REF, MySQLClass::getLastInsertId());
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
