<?php
namespace core\bean;

use core\services\CopsSkillServices;
use core\utils\HtmlUtils;

/**
 * CopsSkillJointBean
 * @author Hugues
 * @since v1.23.06.25
 * @version v1.23.08.12
 */
class CopsSkillJointBean extends UtilitiesBean
{
    public $objSkillLangues = null;

    public function __construct($obj=null)
    {
      $this->objCopsSkillJoint = $obj;
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getCartouche(bool $isReadOnly=true): string
    {
        $specSkillId = $this->objCopsSkillJoint->getField(self::FIELD_SPEC_SKILL_ID);
        $objSkill = $this->objCopsSkillJoint->getSkill();
        if ($specSkillId!=0) {
            $objSpecSkill = $this->objCopsSkillJoint->getSpecSkill();
            $strSpecialisation = '('.$objSpecSkill->getField(self::FIELD_SPEC_NAME).')';
        } elseif ($objSkill->getField(self::FIELD_SPEC_LEVEL)!='0') {
            $strSpecialisation = '(Spécialisation à '.$objSkill->getField(self::FIELD_SPEC_LEVEL).')';
        } else {
            $strSpecialisation = self::CST_NBSP;
        }
        $urlTemplate = self::WEB_PPFD_PFL_SKILL;
        $attributes = [
            $objSkill->getField(self::FIELD_SKILL_NAME),
            $strSpecialisation,
            $this->objCopsSkillJoint->getField(self::FIELD_SCORE),
            $isReadOnly ? self::CST_READONLY : '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.12
     */
    public function getCartoucheLangue(bool $isReadOnly=true): string
    {
        if ($this->objSkillLangues==null) {
            $objSkillServices = new CopsSkillServices();
            $this->objSkillLangues = $objSkillServices->getSpecSkills([self::FIELD_SKILL_ID=>34]);
        }

        $objSkill = $this->objCopsSkillJoint->getSkill();
        if ($this->objCopsSkillJoint->getField(self::FIELD_SPEC_SKILL_ID)!=0) {
            $objSpecSkill = $this->objCopsSkillJoint->getSpecSkill();
            $strSpecialisation = '('.$objSpecSkill->getField(self::FIELD_SPEC_NAME).')';
        } else {
            $selAttributes = [
                self::FIELD_ID => 'skill_langue',
                self::ATTR_NAME => 'skill_langue',
                self::ATTR_CLASS => 'form-select p-0 border-0 ajaxAction',
                self::ATTR_DATA => [
                    'objid' => $this->objCopsSkillJoint->getField(self::FIELD_ID),
                    self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CHANGE,
                    self::ATTR_DATA_AJAX    => self::AJAX_SAVE_DATA,
                ]
            ];
            $strContentSpecialisation = '<option>Langues...</option>';
            foreach ($this->objSkillLangues as $objSkillSpec) {
                $label = $objSkillSpec->getField(self::FIELD_SPEC_NAME);
                $value = $objSkillSpec->getField(self::FIELD_ID);
                $strContentSpecialisation .= HtmlUtils::getOption($label, $value);
            }
            $strSpecialisation = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSpecialisation, $selAttributes);
        }

        $urlTemplate = self::WEB_PPFD_PFL_SKILL;
        $attributes = [
            $objSkill->getField(self::FIELD_SKILL_NAME),
            $strSpecialisation,
            $this->objCopsSkillJoint->getField(self::FIELD_SCORE),
            $isReadOnly ? self::CST_READONLY : '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
