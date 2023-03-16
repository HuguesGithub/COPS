<?php
namespace core\daoimpl;

use core\domain\CopsIndexClass;
use core\domain\CopsIndexReferenceClass;
use core\domain\CopsIndexNatureClass;
use core\domain\CopsIndexTomeClass;

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

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = array(
            self::FIELD_ID,
            self::FIELD_REF_IDX_ID,
            self::FIELD_TOME_IDX_ID,
            self::FIELD_PAGE,
        );
        $this->dbFields_cir  = array(
            self::FIELD_ID_IDX_REF,
            self::FIELD_NOM_IDX,
            self::FIELD_PRENOM_IDX,
            self::FIELD_AKA_IDX,
            self::FIELD_NATURE_IDX_ID,
            self::FIELD_DESCRIPTION_PJ,
            self::FIELD_DESCRIPTION_MJ,
            self::FIELD_CODE,
        );
        $this->dbFields_cin  = array(
            self::FIELD_ID_IDX_NATURE,
            self::FIELD_NOM_IDX_NATURE,
        );
        $this->dbFields_cit  = array(
            self::FIELD_ID_IDX_TOME,
            self::FIELD_NOM_IDX_TOME,
            self::FIELD_ABR_IDX_TOME,
        );
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
        // On récupère les champs
        $fields = array_shift($this->dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($objCopsIndex, $request, self::FIELD_ID);
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
        $fields = 'idIdx, referenceIdxId, tomeIdxId, page';
        $request  = $this->getSelectRequest($fields, $this->dbTable, self::FIELD_ID);
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
        $fields = 'idIdx, referenceIdxId, tomeIdxId, page';
        $request  = $this->getSelectRequest($fields, $this->dbTable);
        $request .= " INNER JOIN ".$this->dbTable_cit." ON tomeIdxId = idIdxTome";
        $request .= " WHERE referenceIdxId LIKE '%s'";
        $request .= " ORDER BY idIdxTome ASC;";
        return $this->selectListDaoImpl(new CopsIndexClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
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
        // On récupère les champs
        $fields = array_shift($this->dbFields_cir);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTable_cir);
        // On insère
        $this->insertDaoImpl($objCopsIndexReference, $request, self::FIELD_ID_IDX_REF);
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
    public function getIndexReference($prepObject)
    {
        $fields  = "idIdxReference, nomIdxReference, prenomIdxReference, akaIdxReference, natureIdxId, ";
        $fields .= "descriptionPJ, descriptionMJ, reference, code";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cir, self::FIELD_ID_IDX_REF);
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
        $fields  = "idIdxReference, nomIdxReference, prenomIdxReference, akaIdxReference, natureIdxId, ";
        $fields .= "descriptionPJ, descriptionMJ, reference, code";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cir);
        $request .= " WHERE natureIdxId LIKE '%s'";
        $request .= " ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER].";";
        return $this->selectListDaoImpl(new CopsIndexReferenceClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
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
        $fields  = "idIdxNature, nomIdxNature";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cin, self::FIELD_ID_IDX_NATURE);
        return $this->selectDaoImpl($request, $prepObject);
    }
    
    /**
     * @param array
     * @return array[CopsIndexNatureClass]
     * @since 1.22.10.21
     * @version 1.23.02.15
     */
    public function getIndexNatures($attributes)
    {
        $fields  = "idIdxNature, nomIdxNature";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cin);
        $request .= " WHERE nomIdxNature LIKE '%s'";
        $request .= " ORDER BY nomIdxNature ASC;";
        return $this->selectListDaoImpl(new CopsIndexNatureClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
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
        $fields  = "idIdxTome, nomIdxTome, abrIdxTome";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cit, self::FIELD_ID_IDX_TOME);
        return $this->selectDaoImpl($request, $prepObject);
    }

    /**
     * @param array
     * @return array[CopsIndexTomeClass]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexTomes($attributes)
    {
        $fields  = "idIdxTome, nomIdxTome, abrIdxTome";
        $request  = $this->getSelectRequest($fields, $this->dbTable_cit);
        $request .= " WHERE abrIdxTome = '%s'";
        $request .= " ORDER BY nomIdxTome ASC;";
        return $this->selectListDaoImpl(new CopsIndexTomeClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
    }

}
