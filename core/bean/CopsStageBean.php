<?php
namespace core\bean;

use core\domain\CopsStageClass;
use core\services\CopsStageServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsStageBean
 * @author Hugues
 * @since 1.22.06.03
 * @version v1.23.08.05
 */
class CopsStageBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }


    /**
     * @since v1.23.08.05
     */
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        $headerElement = [
            ['label' => 'Nom', 'sortable' => self::FIELD_STAGE_LIBELLE, 'classe' => 'col-3'],
            ['label' => 'Catégorie'],
            ['label' => 'Niveau'],
            ['label' => 'Pré-requis'],
            ['label' => 'Référence'],
        ];
        return static::getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.08.05
     */
    public static function getTableFooter(string $filterCateg): TableauTFootHtmlBean
    { return new TableauTFootHtmlBean(); }

    /**
     * @since v1.23.08.05
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_LIBRARY,
            self::CST_SUBONGLET => self::CST_LIB_STAGE,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_STAGE_LIBELLE),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // Catégorie
        $objCateg = $this->obj->getCategorie();
        $objRow->addCell(
            new TableauCellHtmlBean($objCateg->getField(self::FIELD_STAGE_CAT_NAME), self::TAG_TD, 'text-start')
        );
        // Niveau
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_STAGE_LEVEL)));
        // Pré-Requis
        $preRequis  = $this->obj->getField(self::FIELD_STAGE_REQUIS);
        $preRequis .= ' // ';
        $preRequis .= $this->obj->getField(self::FIELD_STAGE_REQUIS_NEW, self::TAG_TD, 'text-start');
        $objRow->addCell(new TableauCellHtmlBean($preRequis));
        // Référence
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_STAGE_REFERENCE)));

        return $objRow;
    }

    /**
     * @since v1.23.08.05
     */
    public function getEditInterfaceAttributes(): array
    {
        return [];
        //////////////////////////////////////////////////////////
        // Récupération du type de véhicule
        // On va construire une liste déroulante.
        $selTypeCategValue = $this->obj->getField(self::FIELD_VEH_CATEG);
        $selTypeSsCategValue = $this->obj->getField(self::FIELD_VEH_SS_CATEG);
        $strContentSel = '';
        $strContentSelSsCateg = '';
        foreach(static::$arrTypeVehicle as $value => $arr) {
            $strContentSel .= HtmlUtils::getOption($arr[self::TAG_LABEL], $value, $value==$selTypeCategValue);
            foreach($arr[self::FIELD_VEH_SS_CATEG] as $valueSsCateg => $labelSsCateg) {
                $strContentSelSsCateg .= HtmlUtils::getOption(
                    $labelSsCateg,
                    $valueSsCateg,
                    $valueSsCateg==$selTypeSsCategValue
                );
            }
        }
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_VEH_CATEG,
            self::FIELD_ID   => self::FIELD_VEH_CATEG,
        ];
        $selTypeCateg = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, $attributes);
        $attributes = [
            self::ATTR_CLASS => 'custom-select col-3',
            self::ATTR_NAME  => self::FIELD_VEH_SS_CATEG,
            self::FIELD_ID   => self::FIELD_VEH_SS_CATEG,
        ];
        $selTypeSsCateg = HtmlUtils::getBalise(self::TAG_SELECT, $strContentSelSsCateg, $attributes);
        //////////////////////////////////////////////////////////

        return [
            // Id
            $this->obj->getField(self::FIELD_ID),
            // Nom
            $this->obj->getField(self::FIELD_VEH_LABEL),
            // Référence
            $this->obj->getField(self::FIELD_VEH_REFERENCE),
            // Type de véhicule (liste)
            $selTypeCateg,
            // Sous Catégorie de véhicule (liste)
            $selTypeSsCateg,
            // Occupants
            $this->obj->getField(self::FIELD_VEH_PLACES),
            // Vitesse
            $this->obj->getField(self::FIELD_VEH_SPEED),
            // Accélération
            $this->obj->getField(self::FIELD_VEH_ACCELERE),
            // Autonomie
            $this->obj->getField(self::FIELD_VEH_AUTONOMIE),
            // Carburant
            $this->obj->getField(self::FIELD_VEH_FUEL),
            // Prix
            $this->obj->getField(self::FIELD_VEH_PRICE),
            // Points de Structure
            $this->obj->getField(self::FIELD_VEH_PS),
            // Année
            $this->obj->getField(self::FIELD_VEH_YEAR),
            // Options
            $this->obj->getField(self::FIELD_VEH_OPTIONS),
            // Ligne Rouge
            $this->obj->getField(self::FIELD_VEH_LGN_ROUGE),
        ];

    }


    /**
     * @since 1.22.06.03
     * @version 1.22.06.03
     */
    public function getStageDisplay()
    {
        $urlTemplate = self::WEB_PPFA_LIB_COURSE;

        $strCapacitesSpeciales = '';
        $objsCapaSpec = $this->objCopsStageServices->getStageSpecs($this->objStage->getField(self::FIELD_ID));
        while (!empty($objsCapaSpec)) {
            $objCapaSpec = array_shift($objsCapaSpec);
            $strCapacitesSpeciales .= '<dt>'.$objCapaSpec->getField(self::FIELD_SPEC_NAME).'</dt>';
            $strCapacitesSpeciales .= '<dd>'.$objCapaSpec->getField(self::FIELD_SPEC_DESC).'</dd>';
        }

        $attributes = [
            // Le nom du stage
            $this->objStage->getField(self::FIELD_STAGE_LIBELLE),
            // Le niveau du stage
            'lvl'.$this->objStage->getField(self::FIELD_STAGE_LEVEL),
            // Les Pré Requis
            $this->objStage->getField(self::FIELD_STAGE_REQUIS),
            // Le Cumul éventuel
            $this->objStage->getField(self::FIELD_STAGE_CUMUL),
            // La Description
            $this->objStage->getField(self::FIELD_STAGE_DESC),
            // Le Bonus éventuel
            $this->objStage->getField(self::FIELD_STAGE_BONUS),
            // La liste des capacités spéciales
            '<dl>'.$strCapacitesSpeciales.'</dl>',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
