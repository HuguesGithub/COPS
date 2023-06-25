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
 * @version v1.23.06.25
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
            $arrSkillJointWithName[] = [
                self::ATTR_NAME => $objSkillJoint->getSkill()->getField(self::FIELD_SKILL_NAME),
                'obj' => $objSkillJoint,
            ];
        }

        $cpt = 0;
        while (!empty($arrSkillJointWithName)) {
            ++$cpt;
            $arrData = array_shift($arrSkillJointWithName);
            $objSkillJoint = $arrData['obj'];
            ${'colSkill'.$cpt} .= $objSkillJoint->getBean()->getCartouche();
            $cpt %= 3;
        }

    /*

        // Première colonne.
        $colCarac1  = $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_CARRURE);
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_CHARME);
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_COORDINATION);
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_EDUCATION);

        // Deuxième colonne.
        $colCarac2  = $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_PERCEPTION);
        $colCarac2 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_REFLEXES);

        $strSpan = HtmlUtils::getBalise(self::TAG_SPAN, 'Init min.', [self::ATTR_CLASS=>'input-group-text col-9']);
        $inputAttributes = [
            self::ATTR_TYPE => 'number',
            self::ATTR_CLASS => 'form-control text-center col-3',
            self::ATTR_VALUE => $this->objCopsPlayer->getInitMin(),
            self::CST_READONLY => '',
        ];
        $strInput = HtmlUtils::getBalise(self::TAG_INPUT, '', $inputAttributes);
        $colCarac2 .= HtmlUtils::getDiv($strSpan.$strInput, [self::ATTR_CLASS=>'col-12 input-group mb-3']);
        $colCarac2 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_CARAC_SANGFROID);

        // Troisième colonne
        $colCarac3  = $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PV_MAX);
        $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PAD_MAX);
        $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PAN_MAX);
        $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PX_CUMUL);
        */

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
