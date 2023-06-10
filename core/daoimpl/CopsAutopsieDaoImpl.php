<?php
namespace core\domain;

use core\domain\CopsAutopsieClass;

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
     * @param CopsAutopsieClass [E|S]
     * @since 1.22.10.10
     * @version 1.23.03.16
     */
    public function insertAutopsie(&$objAutopsie)
    {
        // On récupère les champs
        $fields = $this->dbFields;
        array_shift($fields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($fields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($objAutopsie, $fields, $request, self::FIELD_ID);
    }
    
    /**
     * @param CopsAutopsieClass
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function updateAutopsie($objAutopsie)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_pop($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($objAutopsie, $request, $fieldId);
    }

    /**
     * @param array
     * @since 1.22.10.09
     * @version 1.23.03.15
     */
    public function getAutopsie($prepObject)
    {
        // On récupère les champs
        $fields = implode(', ', array_shift($this->dbFields));
        // On défini la requête de sélection
        $request  = $this->getSelectRequest($fields, $this->dbTable, self::FIELD_ID);
        return $this->selectDaoImpl($request, $prepObject);
    }
    
    /**
     * @since 1.22.10.09
     * @version 1.22.10.09
     */
    public function getAutopsies($attributes)
    {
        // On récupère les champs
        $fields = implode(', ', $this->dbFields);
        // On défini la requête de sélection
        $request  = $this->getSelectRequest($fields, $this->dbTable);
        $request .= " WHERE idxEnquete LIKE '%s'";
        $request .= " ORDER BY dStart DESC;";
        return $this->selectListDaoImpl(new CopsAutopsieClass(), $request, $attributes[self::SQL_WHERE_FILTERS]);
    }
    
}
