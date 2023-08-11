<?php
namespace core\daoimpl;

use core\domain\CopsSoleilClass;

/**
 * Classe CopsSoleilDaoImpl
 * @author Hugues
 * @since v1.23.04.26
 * @version v1.23.08.12
 */
class CopsSoleilDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;

    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.4.26
     * @version v1.23.4.20
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable  = "wp_7_cops_soleil";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields  = [
            self::FIELD_DATE_SOLEIL,
            self::FIELD_HEURE_LEVER,
            self::FIELD_HEURE_COUCHER,
            self::FIELD_HEURE_CULMINE,
            self::FIELD_DUREE_JOUR,
            self::FIELD_HEURE_CIVIL_AM,
            self::FIELD_HEURE_CIVIL_PM,
            self::FIELD_HEURE_NAUTIK_AM,
            self::FIELD_HEURE_NAUTIK_PM,
            self::FIELD_HEURE_ASTRO_AM,
            self::FIELD_HEURE_ASTRO_PM,
        ];
        ////////////////////////////////////
        
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // WP_7_COPS_SOLEIL
    //////////////////////////////////////////////////

    /**
     * @since v1.23.04.28
     * @version v1.23.08.12
     */
    public function getSoleils(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE dateSoleil LIKE '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSoleilClass(), $request, $attributes);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.08.12
     */
    public function getSoleilsIntervalle(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE dateSoleil BETWEEN '%s' AND '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSoleilClass(), $request, $attributes);
    }
}
