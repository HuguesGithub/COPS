<?php
namespace core\domain;

use core\domain\CopsAutopsieClass;

/**
 * Classe CopsAutopsieDaoImpl
 * @author Hugues
 * @since 1.22.10.09
 * @version v1.23.08.12
 */
class CopsAutopsieDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;

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
    
        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [self::FIELD_ID, self::FIELD_IDX_ENQUETE, self::FIELD_DATA, self::FIELD_DSTART];
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
     * @since 1.22.10.09
     * @version v1.23.08.12
     */
    public function getAutopsies(array $attributes=[]): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND idxEnquete LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
//        $request .= " ORDER BY dStart DESC;";
        return $this->selectListDaoImpl(new CopsAutopsieClass(), $request, $attributes);
    }
    
    /**
     * @since 1.22.10.10
     * @version v1.23.08.12
     */
    public function insertAutopsie(CopsAutopsieClass &$obj): void
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
     * @since 1.22.10.10
     * @version v1.23.08.12
     */
    public function updateAutopsie(CopsAutopsieClass $obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }
    
}
