<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsEquipmentCarBean
 * @author Hugues
 * @since v1.23.07.19
 * @version v1.23.07.29
 */
class CopsEquipmentCarBean extends CopsBean
{
    public static $arrTypeVehicle = [
        'scooter'   => [
            self::TAG_LABEL => 'Scooter',
            self::FIELD_VEH_SS_CATEG => [
                '50cc'  => '50cc',
                '125cc' => '125cc',
                '250cc' => '250cc',
                'elec'  => 'Electrique',
            ]
        ],
        'moto'      => [
            self::TAG_LABEL => 'Moto',
            self::FIELD_VEH_SS_CATEG => [
                'cross'    => 'Cross',
                'custom'   => 'Custom',
                'elec'     => 'Electrique',
                'intercep' => 'Interceptor',
                'roadster' => 'Roadster',
                'routiere' => 'Routière',
                'sportive' => 'Sportive',
                'tricycle' => 'Tricycle',
            ],
        ],
        'voiture'   => [
            self::TAG_LABEL => 'Voiture',
            self::FIELD_VEH_SS_CATEG => [
                '4x4'      => '4x4',
                'berline'  => 'Berline',
                'break'    => 'Break',
                'buggy'    => 'Buggy',
                'city'     => 'Citadine',
                'compact'  => 'Compact',
                'coupe'    => 'Coupé',
                'coupecab' => 'Coupé Cabriolet',
                'coupespo' => 'Coupé Sportive',
                'limo'     => 'Limousine',
                'ludospce' => 'Ludospace',
                'monospce' => 'Monospace',
                'roadster' => 'Roadster',
                'sedan'    => 'Sedan',
                'sport'    => 'Sportive',
                'targa'    => 'Targa',
                'tracto'   => 'Tractosaure',
                'suv'      => 'SUV',
            ],
        ],
        'utilitaire'   => [
            self::TAG_LABEL => 'Utilitaire',
            self::FIELD_VEH_SS_CATEG => [
                'camion'   => 'Camion',
                'pickup'   => 'Pick-up',
                'tracteur' => 'Tracteur',
                'van'      => 'Van',
            ],
        ],
        'aquatique'   => [
            self::TAG_LABEL => 'Aquatique',
            self::FIELD_VEH_SS_CATEG => [
                'barge'    => 'Barge',
                'divers'   => 'Divers',
                'horsbord' => 'Hors-bord',
                'hydro'    => 'Hydroglisseur',
                'jetski'   => 'Jet Ski',
                'multicoq' => 'Multicoque',
                'platefrm' => 'Plate-forme',
                'ssmarin'  => 'Sous-marin',
                'voilier'  => 'Voilier',
                'yacht'    => 'Yacht',
                'zodiac'   => 'Zodiac',
            ],
        ],
    ];

    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
    }

    /**
     * @since v1.23.07.24
     * @version v1.23.07.29
     */
    public static function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        $headerElement = [
            ['label' => 'Nom', 'sortable' => self::FIELD_VEH_LABEL, 'classe' => 'col-3'],
            ['label' => 'Catégorie'],
            ['label' => 'Date'],
            ['label' => 'Place'],
            ['label' => 'LR', 'abbr' => 'Ligne Rouge'],
            ['label' => 'VM', 'abbr' => 'Vitesse Maximale (km/h)'],
            ['label' => 'Acc', 'abbr' => 'Accélération (0 à 100)'],
            ['label' => 'PS', 'abbr' => 'Points de Structure'],
            ['label' => 'Autonomie'],
            ['label' => 'Car', 'abbr' => 'Carburant'],
            ['label' => 'Prix'],
            ['label' => 'Spécial'],
            ['label' => 'Réf', 'abbr' => 'Référence'],
        ];
        return static::getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.07.25
     * @version v1.23.07.29
     */
    public static function getTableFooter(string $filterCateg): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle('line-height:30px;');
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        $rowContent = static::getCategorieFilter($filterCateg);
        $objRow->addCell(new TableauCellHtmlBean($rowContent, self::TAG_TH));
        for ($i=0; $i<11; $i++) {
            $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        }
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }

    /**
     * @since v1.23.07.22
     * @version v1.23.07.22
     */
    public static function getCategorieFilter($selectedValue=''): string
    {
        $arrTypeVehicle = CopsEquipmentCarBean::$arrTypeVehicle;

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
        ];
        $strLabel = 'Catégorie';
        $strLis = '';
        if ($selectedValue!='') {
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($strLabel, $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
        }
        foreach ($arrTypeVehicle as $value => $arr) {
            $urlElements['filterCateg'] = $value;
            $href = UrlUtils::getAdminUrl($urlElements);
            $liContent = HtmlUtils::getLink($arr[self::TAG_LABEL], $href, 'dropdown-item');
            $strLis .= HtmlUtils::getBalise(self::TAG_LI, $liContent);
            if ($value==$selectedValue) {
                $strLabel = $arr[self::TAG_LABEL];
            }
        }
        $ulAttributes = [
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'height: 200px; overflow: auto;',
        ];
        $ul = HtmlUtils::getBalise(self::TAG_UL, $strLis, $ulAttributes);

        $btnAttributes = [
            self::ATTR_CLASS => ' btn_outline btn-sm dropdown-toggle',
            'aria-expanded' => false,
            'data-bs-toggle' => 'dropdown',
        ];
        $strButton = HtmlUtils::getButton($strLabel, $btnAttributes);

        $divAttributes = [
            self::ATTR_CLASS => 'dropdown dropup',
            self::ATTR_STYLE => 'position: absolute; margin-top: -17px;',
        ];
        return HtmlUtils::getDiv($strButton.$ul, $divAttributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.22
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_EQUIPMENT,
            self::CST_SUBONGLET => self::CST_EQPT_CAR,
            self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
            self::CST_ACTION => self::CST_WRITE,
        ];
        $strLink = HtmlUtils::getLink(
            $this->obj->getField(self::FIELD_VEH_LABEL),
            UrlUtils::getAdminUrl($urlElements),
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, 'text-start'));
        // La Catégorie
        $valueCateg = $this->obj->getField(self::FIELD_VEH_CATEG);
        $labelCategorie = static::$arrTypeVehicle[$valueCateg][self::TAG_LABEL];
        $valueSsCateg = $this->obj->getField(self::FIELD_VEH_SS_CATEG);
        $labelSsCategorie = static::$arrTypeVehicle[$valueCateg][self::FIELD_VEH_SS_CATEG][$valueSsCateg];
        $objRow->addCell(new TableauCellHtmlBean(
            $labelCategorie.' ('.$labelSsCategorie.')',
            self::TAG_TD,
            'text-start'
        ));
        // Date
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_YEAR)));
        // Nombre d'occupants
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_PLACES)));
        // Ligne Rouge
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_LGN_ROUGE)));
        // La Vitesse Maximale
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_SPEED)));
        // L'accélération
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_ACCELERE)));
        // Les Points de Structure
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_PS)));
        // Autonomie
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_AUTONOMIE)));
        // Carburant
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_FUEL)));
        // Le Prix
        $objRow->addCell(
            new TableauCellHtmlBean('$'.$this->obj->getField(self::FIELD_VEH_PRICE), self::TAG_TD, 'text-end')
        );
        // Spécial
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_OPTIONS)));
        // Référence
        $objRow->addCell(new TableauCellHtmlBean($this->obj->getField(self::FIELD_VEH_REFERENCE)));

        return $objRow;
    }

    /**
     * @since v1.23.07.21
     * @version v1.23.07.22
     */
    public function getEditInterfaceAttributes(): array
    {
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

    
}
