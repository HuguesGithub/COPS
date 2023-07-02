<?php
namespace core\bean;

use core\domain\CopsPlayerClass;
use core\services\CopsPlayerServices;
use core\services\CopsSkillServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileSkillsBean
 * @author Hugues
 * @since v1.23.06.23
 * @version v1.23.07.02
 */
class WpPageAdminProfileSkillsBean extends WpPageAdminProfileBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $this->urlAttributes[self::CST_SUBONGLET] = self::CST_PFL_SKILLS;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_SKILLS,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton(
            $buttonContent,
            [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK_DISABLED]
        );
        /////////////////////////////////////////
    }

    /**
     * @since v1.23.07.02
     * @version v1.23.07.02
     */
    public function getOngletContent(): string
    {
        $colSkill1 = '';
        $colSkill2 = '';
        $colSkill3 = '';

        $objCopsPlayerServices = new CopsPlayerServices();
        $attributes[self::SQL_WHERE_FILTERS] = [
            self::FIELD_MATRICULE => $_SESSION[self::FIELD_MATRICULE],
        ];
        $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
        if (!empty($objsCopsPlayer)) {
            $this->objCopsPlayer = array_shift(($objsCopsPlayer));
        } else {
            $this->objCopsPlayer = new CopsPlayerClass();
        }
        $objsSkillJoint = $this->objCopsPlayer->getCopsSkillJoints();

        $arrSkillJointWithName = [];
        while (!empty($objsSkillJoint)) {
            $objSkillJoint = array_shift($objsSkillJoint);
            $strSkillName = $objSkillJoint->getSkill()->getField(self::FIELD_SKILL_NAME);
            $strSkillName = str_replace('É', 'E', $strSkillName);
            $arrSkillJointWithName[] = [
                self::ATTR_NAME => $strSkillName.'-'.$objSkillJoint->getField(self::FIELD_SPEC_SKILL_ID),
                'obj' => $objSkillJoint,
            ];
        }

        usort($arrSkillJointWithName, fn($a, $b) => strcmp($a[self::ATTR_NAME], $b[self::ATTR_NAME]));

        $cpt = 0;
        $nbSkills = count($arrSkillJointWithName);
        $prevSkillName = '';
        $prevColId = 0;
        while (!empty($arrSkillJointWithName)) {
            $arrData = array_shift($arrSkillJointWithName);
            $objSkillJoint = $arrData['obj'];
            $newSkillName = $objSkillJoint->getSkill()->getField(self::FIELD_SKILL_NAME);
            $colId = ceil(3*$cpt/$nbSkills);
            if ($newSkillName==$prevSkillName && $prevColId!=$colId) {
                --$colId;
            }
            ${'colSkill'.$colId} .= $objSkillJoint->getBean()->getCartouche();
            ++$cpt;
            $prevSkillName = $newSkillName;
            $prevColId = $colId;
        }

        $urlTemplate = self::WEB_PPFS_PFL_SKILLS;
        $attributes = [
            // Url du formulaire
            UrlUtils::getPublicUrl($this->urlAttributes),
            // Première colonne des compétences
            $colSkill1,
            // Deuxième colonne des compétences
            $colSkill2,
            // Troisième colonne des compétences
            $colSkill3,
        ];
        return $this->getTabsBar().$this->getRender($urlTemplate, $attributes);
    }
}
