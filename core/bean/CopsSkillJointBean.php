<?php
namespace core\bean;

/**
 * CopsSkillJointBean
 * @author Hugues
 * @since v1.23.06.25
 * @version v1.23.06.25
 */
class CopsSkillJointBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
      $this->objCopsSkillJoint = $obj;
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.06.25
     */
    public function getCartouche(): string
    {
        $strSpecialisation = '&nbsp;';
        $specSkillId = $this->objCopsSkillJoint->getField(self::FIELD_SPEC_SKILL_ID);
        if ($specSkillId!=0) {
            $objSpecSkill = $this->objCopsSkillJoint->getSpecSkill();
            $strSpecialisation = '('.$objSpecSkill->getField(self::FIELD_SPEC_NAME).')';
        }
        $urlTemplate = self::WEB_PPFD_PFL_SKILL;
        $attributes = [
            $this->objCopsSkillJoint->getSkill()->getField(self::FIELD_SKILL_NAME),
            $strSpecialisation,
            $this->objCopsSkillJoint->getField(self::FIELD_SCORE),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
