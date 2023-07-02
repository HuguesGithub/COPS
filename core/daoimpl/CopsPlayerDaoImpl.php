<?php
namespace core\daoimpl;

use core\domain\CopsPlayerClass;

/**
 * Classe CopsPlayerDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version v1.23.07.02
 */
class CopsPlayerDaoImpl extends LocalDaoImpl
{
    /**
     * Class constructor
     * @since v1.23.06.19
     * @version v1.23.06.25
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable  = "wp_7_cops_player";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields = [
            self::FIELD_ID,
            self::FIELD_MATRICULE,
            self::FIELD_PASSWORD,
            self::FIELD_NOM,
            self::FIELD_PRENOM,
            self::FIELD_SURNOM,
            self::FIELD_CARAC_CARRURE,
            self::FIELD_CARAC_CHARME,
            self::FIELD_CARAC_COORDINATION,
            self::FIELD_CARAC_EDUCATION,
            self::FIELD_CARAC_PERCEPTION,
            self::FIELD_CARAC_REFLEXES,
            self::FIELD_CARAC_SANGFROID,
            self::FIELD_PV_MAX,
            self::FIELD_PV_CUR,
            self::FIELD_PAD_MAX,
            self::FIELD_PAD_CUR,
            self::FIELD_PAN_MAX,
            self::FIELD_PAN_CUR,
            self::FIELD_BIRTH_DATE,
            self::FIELD_TAILLE,
            self::FIELD_POIDS,
            self::FIELD_SEXE,
            self::FIELD_ETHNIE,
            self::FIELD_CHEVEUX,
            self::FIELD_YEUX,
            self::FIELD_ETUDES,
            self::FIELD_ORIGINE_SOCIALE,
            self::FIELD_GRADE,
            self::FIELD_GRADE_RANG,
            self::FIELD_GRADE_ECHELON,
            self::FIELD_INTEGRATION_DATE,
            self::FIELD_SECTION,
            self::FIELD_BACKGROUND,
            self::FIELD_STATUS,
            self::FIELD_PX_CUMUL,
            self::FIELD_PX_CUR,
        ];
        ////////////////////////////////////

        parent::__construct();
    }

    ////////////////////////////////////
    // METHODES
    ////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_player
    ////////////////////////////////////

    /**
     * @since v1.23.06.19
     * @version v1.23.07.02
     */
    public function getCopsPlayers(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND matricule LIKE '%s' AND password LIKE '%s'";
        $request .= " AND grade LIKE '%s' AND section LIKE '%s'";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsPlayerClass(), $request, $attributes);
    }

    /**
     * @since v1.23.06.21
     * @version v1.23.06.25
     */
    public function updatePlayer(CopsPlayerClass $objPlayer)
    {
        // On récupère les champs
        $dbFields = $this->dbFields;
        $fieldId = array_shift($dbFields);
        // On défini la requête de mise à jour
        $request = $this->getUpdateRequest($dbFields, $this->dbTable, $fieldId);
        // On met à jour
        $this->updateDaoImpl($objPlayer, $request, $fieldId);
    }


}
