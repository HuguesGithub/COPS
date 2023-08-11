<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\domain\CopsSkillClass;
use core\domain\CopsSkillJointClass;
use core\domain\CopsSkillSpecClass;

/**
 * Classe CopsSkillDaoImpl
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.08.12
 */
class CopsSkillDaoImpl extends LocalDaoImpl
{
    private $dbTable;
    private $dbFields;
    private $dbTableCss;
    private $dbFieldsCss;
    private $dbTableCps;
    private $dbFieldsCps;

    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v1.23.06.23
     * @version v1.23.08.12
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable      = "wp_7_cops_skill";
        $this->dbTableCss  = "wp_7_cops_skill_spec";
        $this->dbTableCps  = "wp_7_cops_player_skill";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields      = [
            self::FIELD_ID,
            self::FIELD_SKILL_NAME,
            self::FIELD_SKILL_DESC,
            self::FIELD_SKILL_USES,
            self::FIELD_SPEC_LEVEL,
            self::FIELD_PAD_USABLE,
            self::FIELD_REFERENCE,
            self::FIELD_DEFAULT_ABILITY
        ];
        $this->dbFieldsCss  = [
            self::FIELD_ID,
            self::FIELD_SPEC_NAME,
            self::FIELD_SKILL_ID
        ];
        $this->dbFieldsCps  = [
            self::FIELD_ID,
            self::FIELD_COPS_ID,
            self::FIELD_SKILL_ID,
            self::FIELD_SPEC_SKILL_ID,
            self::FIELD_SCORE,
        ];
        ////////////////////////////////////

        parent::__construct();

    }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // wp_7_cops_skill
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getSkills(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE id LIKE '%s' ";
        /*
            AND skillName LIKE '%s'
            AND skillDescription LIKE '%s' ;
            AND specLevel LIKE '%s'
            AND padUsable LIKE '%s' ;
        */
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // wp_7_cops_player_skill
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.23
     * @version v1.23.08.12
     */
    public function getCopsSkills(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCps), $this->dbTableCps);
        $request .= " WHERE copsId LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillJointClass(), $request, $attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_SKILL_SPEC
    //////////////////////////////////////////////////
    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getSpecSkills(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFieldsCss), $this->dbTableCss);
        $request .= " WHERE id LIKE '%s' AND skillId LIKE '%s' ";
        $request .= $this->defaultOrderByAndLimit;
        return $this->selectListDaoImpl(new CopsSkillSpecClass(), $request, $attributes);
    }

}
