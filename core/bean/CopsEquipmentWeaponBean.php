<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEquipmentWeaponBean
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.07.29
 */
class CopsEquipmentWeaponBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.07.15
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_WEAPON,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_NOM_ARME),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // Le score de Précision
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_PR)));
        // Le score de Puissance
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_PU)));
        // Le score de Force d'Arrêt
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_FA)));
        // La valeur éventuelle de Rafale Courte
        $value = $this->obj->getField(self::FIELD_SCORE_VRC);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Portée
        $value = $this->obj->getField(self::FIELD_PORTEE);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value.'m' : '/'));
        // Valeur de Couverture
        $value = $this->obj->getField(self::FIELD_SCORE_VC);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Cadence de Tir
        $value = $this->obj->getField(self::FIELD_SCORE_CT);
        $objRow->addCell(new TableauCellHtmlBean($value!=0 ? $value : '/'));
        // Munitions
        $value = $this->obj->getField(self::FIELD_MUNITIONS);
        $objRow->addCell(new TableauCellHtmlBean($value!='' ? $value : '/'));
        // Le score de Dissimulation
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_SCORE_DIS)));
        // Le Prix
        $objRow->addCell(new TableauCellHtmlBean('$'.$this->obj->getField(self::FIELD_PRIX), self::TAG_TD, 'text-end'));
        return $objRow;
    }

    /**
     * @since v1.23.07.22
     * @version v1.23.07.29
     */
    public function getEditInterfaceAttributes(): array
    {
        //////////////////////////////////////////////////////////
        // Récupération de la référence
        $objTome = $this->obj->getTome();
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Récupération du type d'arme
        // On va construire une liste déroulante.
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_TYPE_ARME,
            self::FIELD_ID   => self::FIELD_TYPE_ARME,
        ];
        $arrTypeArme = [
            'contact' => 'Arme de contact',
            'poing' => 'Arme de poing',
            'epaule' => 'Arme d\'épaule',
            'lourde' => 'Arme lourde',
        ];
        $selTypeArmeValue = $this->obj->getField(self::FIELD_TYPE_ARME);
        $blnIsContact = $selTypeArmeValue=='contact';
        $strContentSel = '';
        foreach($arrTypeArme as $value => $label) {
            $strContentSel .= HtmlUtils::getOption($label, $value, $value==$selTypeArmeValue);
        }
        $selTypeArme = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        //////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////
        // Récupération de la compétence nécessaire
        // On va construire une liste déroulante.
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_SKILL_USE,
            self::FIELD_ID   => self::FIELD_SKILL_USE,
        ];
        $objSkillServices = new CopsSkillServices();
        $objsSkillSpec = $objSkillServices->getSpecSkills();
        $selSkillUseValue = $this->obj->getField(self::FIELD_SKILL_USE);
        $strContentSel = HtmlUtils::getOption('', '0', 0==$selSkillUseValue);
        while (!empty($objsSkillSpec)) {
            $objSkillSpec = array_shift($objsSkillSpec);
            $label = $objSkillSpec->getField(self::FIELD_SPEC_NAME);
            $value = $objSkillSpec->getField(self::FIELD_ID);
            $strContentSel .= HtmlUtils::getOption($label, $value, $value==$selSkillUseValue);
        }
        $selSkillUse = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        //////////////////////////////////////////////////////////

        return [
            // Id
            $this->obj->getField(self::FIELD_ID),
            // Nom
            $this->obj->getField(self::FIELD_NOM_ARME),
            // Référence
            $objTome->getField(self::FIELD_ABR_IDX_TOME),
            // Type d'arme (liste)
            $selTypeArme,
            // Compétence (liste)
            $selSkillUse,
            // Précision
            $this->obj->getField(self::FIELD_SCORE_PR),
            // Puissance
            $this->obj->getField(self::FIELD_SCORE_PU),
            // Force d'arrêt
            $this->obj->getField(self::FIELD_SCORE_FA),
            // Dissimulation
            $this->obj->getField(self::FIELD_SCORE_DIS),
            // Portée
            $blnIsContact ? '' : $this->obj->getField(self::FIELD_PORTEE),
            // Prix
            $this->obj->getField(self::FIELD_PRIX),
            // Valeur de Rafale Courte
            $blnIsContact ? '' : $this->obj->getField(self::FIELD_SCORE_VRC),
            // Cadence de Tir
            $blnIsContact ? '' : $this->obj->getField(self::FIELD_SCORE_CT),
            // Valeur de Couverture
            $blnIsContact ? '' : $this->obj->getField(self::FIELD_SCORE_VC),
            // Munitions
            $blnIsContact ? '' : $this->obj->getField(self::FIELD_MUNITIONS),
        ];
    }

    /**
     * @since v1.23.07.25
     * @version v1.23.07.29
     */
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $objRow = new TableauRowHtmlBean();
        $objTableauCell = new TableauCellHtmlBean('Nom', self::TAG_TH, 'col-3');
        $queryArg[self::SQL_ORDER_BY] = self::FIELD_NOM_ARME;
        $objTableauCell->ableSort($queryArg);
        $objRow->addCell($objTableauCell);
        $headerElement = [
            ['label' => 'PR', 'abbr' => 'Précision'],
            ['label' => 'PU', 'abbr' => 'Puissance'],
            ['label' => 'FA', 'abbr' => 'Force d\'arrêt'],
            ['label' => 'VRC', 'abbr' => 'Valeur de Rafale Courte'],
            ['label' => 'Portée'],
            ['label' => 'VC', 'abbr' => 'Valeur de Couverture'],
            ['label' => 'CT', 'abbr' => 'Cadence de Tir'],
            ['label' => 'Mun', 'abbr' => 'Munitions'],
            ['label' => 'Dis', 'abbr' => 'Dissimulation'],
            ['label' => 'Prix'],
        ];
        foreach ($headerElement as $element) {
            if (isset($element['abbr'])) {
                $tag = HtmlUtils::getBalise('abbr', $element['label'], [self::ATTR_TITLE=>$element['abbr']]);
            } else {
                $tag = $element['label'];
            }
            $objRow->addCell(new TableauCellHtmlBean($tag, self::TAG_TH, self::CSS_COL));
        }

        $objHeader = new TableauTHeadHtmlBean();
        $objHeader->addRow($objRow);

        return $objHeader;
    }
}
