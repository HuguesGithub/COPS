<?php
namespace core\daoimpl;

use core\domain\CopsLuneClass;

/**
 * Classe CopsLuneDaoImpl
 * @author Hugues
 * @since v1.23.04.27
 * @version v1.23.08.12
 */
class CopsLuneDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;

    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.4.27
     * @version v1.23.4.20
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable  = "wp_7_cops_lune";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields  = [
            self::FIELD_ID_LUNE,
            self::FIELD_DATE_LUNE,
            self::FIELD_HEURE_LUNE,
            self::FIELD_TYPE_LUNE,
        ];
        ////////////////////////////////////
        
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // WP_7_COPS_LUNE
    //////////////////////////////////////////////////
    /**
     * @since v1.23.04.27
     * @version v1.23.08.12
     */
    public function getLunes(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE dateLune BETWEEN '%s' AND '%s' AND typeLune LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsLuneClass(), $request, $attributes);
    }
}
