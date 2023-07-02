<?php
namespace core\bean;

/**
 * CopsSkillJointBean
 * @author Hugues
 * @since v1.23.06.25
 * @version v1.23.07.02
 */
class CopsSkillJointBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
      $this->objCopsSkillJoint = $obj;
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.07.02
     */
    public function getCartouche(): string
    {
        $specSkillId = $this->objCopsSkillJoint->getField(self::FIELD_SPEC_SKILL_ID);
        $objSkill = $this->objCopsSkillJoint->getSkill();
        if ($specSkillId!=0) {
            $objSpecSkill = $this->objCopsSkillJoint->getSpecSkill();
            $strSpecialisation = '('.$objSpecSkill->getField(self::FIELD_SPEC_NAME).')';
        } elseif ($objSkill->getField(self::FIELD_SPEC_LEVEL)!='0') {
            $strSpecialisation = '(Spécialisation à '.$objSkill->getField(self::FIELD_SPEC_LEVEL).')';
        } else {
            $strSpecialisation = '&nbsp;';
        }
        $urlTemplate = self::WEB_PPFD_PFL_SKILL;
        $attributes = [
            $objSkill->getField(self::FIELD_SKILL_NAME),
            $strSpecialisation,
            $this->objCopsSkillJoint->getField(self::FIELD_SCORE),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
