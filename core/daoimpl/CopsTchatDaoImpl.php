<?php
namespace core\daoimpl;

use core\domain\CopsTchatClass;
use core\domain\CopsTchatStatusClass;

/**
 * Classe CopsTchatDaoImpl
 * @author Hugues
 * @since v1.23.08.05
 */
class CopsTchatDaoImpl extends LocalDaoImpl
{
    public $dbTable;
    public $dbTableSln;
    public $dbTableStt;
    public $dbFields;
    public $dbFieldsSln;
    public $dbFieldsStt;

    /**
     * Class constructor
     * @since v1.23.08.05
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable    = "wp_7_cops_tchat";
        $this->dbTableSln = "wp_7_cops_tchat_salon";
        $this->dbTableStt = "wp_7_cops_tchat_status";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_SALON_ID,
            self::FIELD_TO_PID,
            self::FIELD_FROM_PID,
            self::FIELD_TIMESTAMP,
            self::FIELD_TEXTE,
        ];
        $this->dbFieldsSln = [
            self::FIELD_ID,
            self::FIELD_NOM_SALON,
            self::FIELD_OWNER_ID,
            self::FIELD_STATUS,
            self::FIELD_PASSWORD,
        ];
        $this->dbFieldsStt = [
            self::FIELD_ID,
            self::FIELD_SALON_ID,
            self::FIELD_PLAYER_ID,
            self::FIELD_LAST_REFRESHED,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_tchat
    ////////////////////////////////////

    /**
     * @since v1.23.08.05
     */
    public function getTchats(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND salonId LIKE '%s'";
        $request .= " AND toPlayerId LIKE '%s' AND timestamp > '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsTchatClass(), $request, $attributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function insertTchat(CopsTchatClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTable);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    ////////////////////////////////////
    // wp_7_cops_tchat_salon
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_tchat_status
    ////////////////////////////////////

    /**
     * @since v1.23.08.05
     */
    public function getTchatStatuss(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsStt), $this->dbTableStt);
        $request .= " WHERE id LIKE '%s' AND salonId LIKE '%s' AND playerId LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsTchatStatusClass(), $request, $attributes);
    }

    /**
     * @since v1.23.08.05
     */
    public function insertTchatStatus(CopsTchatStatusClass &$obj): void
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsStt;
        array_shift($dbFields);
        // On défini la requête d'insertion
        $request = $this->getInsertRequest($dbFields, $this->dbTableStt);
        // On insère
        $this->insertDaoImpl($obj, $dbFields, $request, self::FIELD_ID);
    }

    /**
     * @since v1.23.08.05
     */
    public function updateTchatStatus(CopsTchatStatusClass $obj)
    {
        // On récupère les champs
        $dbFields = $this->dbFieldsStt;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTableStt, $fieldId);
        // On met à jour
        $this->updateDaoImpl($obj, $request, $fieldId);
    }

}
