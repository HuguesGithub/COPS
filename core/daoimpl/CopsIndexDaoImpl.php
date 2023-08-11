<?php
namespace core\daoimpl;

use core\domain\CopsIndexClass;
use core\domain\CopsIndexReferenceClass;
use core\domain\CopsIndexNatureClass;
use core\domain\CopsIndexTomeClass;

/**
 * Classe CopsIndexDaoImpl
 * @author Hugues
 * @since 1.22.10.21
 * @version v1.23.08.12
 */
class CopsIndexDaoImpl extends LocalDaoImpl
{
    protected $dbTable;
    protected $dbTableCir;
    protected $dbTableCin;
    protected $dbTableCit;
    protected $dbFields;
    protected $dbFieldsCir;
    protected $dbFieldsCin;
    protected $dbFieldsCit;

    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.10.21
     * @version v1.23.08.12
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_index";
        $this->dbTableCir  = "wp_7_cops_index_reference";
        $this->dbTableCin  = "wp_7_cops_index_nature";
        $this->dbTableCit  = "wp_7_cops_index_tome";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [self::FIELD_ID, self::FIELD_REF_IDX_ID, self::FIELD_TOME_IDX_ID, self::FIELD_PAGE];
        $this->dbFieldsCir  = [
            self::FIELD_ID_IDX_REF,
            self::FIELD_NOM_IDX,
            self::FIELD_PRENOM_IDX,
            self::FIELD_AKA_IDX,
            self::FIELD_NATURE_IDX_ID,
            self::FIELD_DESCRIPTION_PJ,
            self::FIELD_DESCRIPTION_MJ,
            self::FIELD_CODE
        ];
        $this->dbFieldsCin  = [self::FIELD_ID_IDX_NATURE, self::FIELD_NOM_IDX_NATURE];
        $this->dbFieldsCit  = [self::FIELD_ID_IDX_TOME, self::FIELD_NOM_IDX_TOME, self::FIELD_ABR_IDX_TOME];
        ////////////////////////////////////
        
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX
    //////////////////////////////////////////////////
  
    /**
     * @since 1.22.10.21
     * @version v1.23.08.12
     */
    public function getIndexes(array $attributes): array
    {
        $fields = 'idIdx, referenceIdxId, tomeIdxId, page';
        $request  = $this->getSelectRequest($fields, $this->dbTable);
        $request .= " INNER JOIN ".$this->dbTable_cit." ON tomeIdxId = idIdxTome";
        $request .= " WHERE referenceIdxId LIKE '%s'";
        $request .= " ORDER BY idIdxTome ASC;";
        return $this->selectListDaoImpl(new CopsIndexClass(), $request, $attributes);
    }
    
    /**
     * @since 1.23.02.20
     * @version 1.23.03.15
     */
    public function insertIndex(CopsIndexClass &$obj): void
    {
        // On récupère les champs
        $fields = $this->dbFields;
        array_shift($fields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($obj, $fields, $request, self::FIELD_ID);
    }

    /**
     * @since 1.22.10.21
     * @version v1.23.08.12
     */
    public function updateIndex(CopsIndexClass $obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_REFERENCE
    //////////////////////////////////////////////////

    /**
     * @param array $attributes
     * @return array [CopsIndexReference]
     * @since 1.23.02.15
     * @version v1.23.08.12
     */
    public function getIndexReferences(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCir), $this->dbTableCir);
        $request .= " WHERE natureIdxId LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsIndexReferenceClass(), $request, $attributes);
    }

    /**
     * @since 1.23.02.20
     * @version v1.23.08.12
     */
    public function insertIndexReference(CopsIndexReferenceClass &$obj)
    {
        // On récupère les champs
        $fields = $this->dbFieldsCir;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTableCir);
        // On insère
        $this->insertDaoImpl($obj, $fields, $request, self::FIELD_ID_IDX_REF);
    }

    /**
     * @since 1.23.02.20
     * @version v1.23.08.12
     */
    public function updateIndexReference(CopsIndexReferenceClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsCir;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTableCir, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_NATURE
    //////////////////////////////////////////////////
    
    /**
     * @since 1.22.10.21
     * @version v1.23.08.12
     */
    public function getIndexNatures(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCin), $this->dbTableCin);
        $request .= " WHERE nomIdxNature LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsIndexNatureClass(), $request, $attributes);
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_TOME
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.13
     * @version v1.23.08.12
     */
    public function getIndexTomes(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCit), $this->dbTableCit);
        $request .= " WHERE idIdxTome LIKE '%s' AND abrIdxTome = '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsIndexTomeClass(), $request, $attributes);
    }

}
