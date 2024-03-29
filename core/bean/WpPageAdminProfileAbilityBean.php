<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileAbilityBean
 * @author Hugues
 * @since v1.23.06.20
 * @version v1.23.08.12
 */
class WpPageAdminProfileAbilityBean extends WpPageAdminProfileBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $this->urlAttributes[self::CST_SUBONGLET] = self::CST_PFL_ABILITIES;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_ABILITIES,
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
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getOngletContent(): string
    {
        $isFirstCreationStep = $this->objCopsPlayer->getField(self::FIELD_STATUS)==self::PS_CREATE_1ST_STEP;

        // Première colonne.
        $colCarac1  = $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_CARRURE,
            !$this->isCreate1stStep
        );
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_CHARME,
            !$this->isCreate1stStep
        );
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_COORDINATION,
            !$this->isCreate1stStep
        );
        $colCarac1 .= $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_EDUCATION,
            !$this->isCreate1stStep
        );

        // Deuxième colonne.
        $colCarac2  = $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_PERCEPTION,
            !$this->isCreate1stStep
        );
        $colCarac2 .= $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_REFLEXES,
            !$this->isCreate1stStep
        );

        $strSpan = HtmlUtils::getBalise(self::TAG_SPAN, 'Init min.', [self::ATTR_CLASS=>'input-group-text col-9']);
        $inputAttributes = [
            self::ATTR_TYPE => 'text',
            self::ATTR_CLASS => 'form-control text-center col-3',
            self::ATTR_VALUE => $isFirstCreationStep ? '' : $this->objCopsPlayer->getInitMin(),
            self::CST_READONLY => self::CST_READONLY,
        ];
        $strInput = HtmlUtils::getBalise(self::TAG_INPUT, '', $inputAttributes);
        $colCarac2 .= HtmlUtils::getDiv($strSpan.$strInput, [self::ATTR_CLASS=>'col-12 input-group mb-3']);
        $colCarac2 .= $this->objCopsPlayer->getBean()->getProfileAbility(
            self::FIELD_CARAC_SANGFROID,
            !$this->isCreate1stStep
        );

        $colCarac3 = '';

        // Colonnes de langues :
        $colLng1 = '';
        $colLng2 = '';
        $colLng3 = '';

        if ($isFirstCreationStep) {
            $colLng2 = "Terminez la première étape de création d'abord.";
        } else {
            // Troisième colonne
            $colCarac3  = $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PV_MAX, true);
            $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PAD_MAX, true);
            $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PAN_MAX, true);
            $colCarac3 .= $this->objCopsPlayer->getBean()->getProfileAbility(self::FIELD_PX_CUMUL, true);

            $cpt = 1;
            $objsSkillJoint = $this->objCopsPlayer->getCopsSkills();
            while (!empty($objsSkillJoint)) {
                $objSkillJoint = array_shift($objsSkillJoint);
                if ($objSkillJoint->getField(self::FIELD_SKILL_ID)!=34) {
                    continue;
                }
                ${'colLng'.$cpt} .= $objSkillJoint->getBean()->getCartoucheLangue();
                $cpt = $cpt==3 ? 1 : $cpt+1;
            }
        }

        $urlTemplate = self::WEB_PPFS_PFL_ABILITIES;
        $attributes = [
            // Url du formulaire
            UrlUtils::getPublicUrl($this->urlAttributes),
            // Première colonne des caractéristiques
            $colCarac1,
            // Deuxième colonne des caractéristiques
            $colCarac2,
            // Troisième colonne des caractéristiques
            $colCarac3,
            // Colonnes de langues
            $colLng1,
            $colLng2,
            $colLng3,
        ];
        return $this->getTabsBar().$this->getRender($urlTemplate, $attributes);
    }
}
