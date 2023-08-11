<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsStageClass;
use core\domain\CopsStageCapaciteSpecialeClass;
use core\domain\CopsStageCategorieClass;

/**
 * Classe CopsStageDaoImpl
 * @author Hugues
 * @since 1.22.06.02
 * @version v1.23.08.12
 */
class CopsStageDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;
    private $dbTableCsc;
    private $dbFieldsCsc;
    private $dbTableCss;
    private $dbFieldsCss;

    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.22.06.02
     * @version v1.23.08.05
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable     = "wp_7_cops_stage";
        $this->dbTableCsc  = "wp_7_cops_stage_categorie";
        $this->dbTableCss  = "wp_7_cops_stage_spec";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [
            self::FIELD_ID,
            self::FIELD_STAGE_CAT_ID,
            self::FIELD_STAGE_LIBELLE,
            self::FIELD_STAGE_LEVEL,
            self::FIELD_STAGE_REFERENCE,
            self::FIELD_STAGE_REQUIS,
            self::FIELD_STAGE_REQUIS_NEW,
            self::FIELD_STAGE_CUMUL,
            self::FIELD_STAGE_DESC,
            self::FIELD_STAGE_BONUS
        ];
        $this->dbFieldsCsc   = [self::FIELD_ID, self::FIELD_STAGE_CAT_NAME];
        $this->dbFieldsCss   = [self::FIELD_ID, self::FIELD_SPEC_NAME, self::FIELD_SPEC_DESC, self::FIELD_STAGE_ID];
        ////////////////////////////////////

        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_STAGE
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.29
     * @version v1.23.08.05
     */
    public function getStages(array $attributes=[]): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' AND stageCategorieId LIKE '%s' AND stageNiveau LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsStageClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_STAGE_CATEGORIE
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.29
     * @version v1.23.08.05
     */
    public function getStageCategories(array $attributes=[]): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCsc), $this->dbTableCsc);
        $request .= " WHERE id LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsStageCategorieClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_STAGE_SPEC
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.29
     * @version v1.23.08.05
     */
    public function getStageSpecialites(array $attributes=[]): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCsc), $this->dbTableCsc);
        $request .= " WHERE id LIKE '%s' AND stageId = '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsStageCapaciteSpecialeClass(), $request, $attributes);
    }

}
