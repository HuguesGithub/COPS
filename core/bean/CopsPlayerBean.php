<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * CopsPlayerBean
 * @author Hugues
 * @since 1.22.04.27
 * @version v1.23.12.02
 */
class CopsPlayerBean extends UtilitiesBean
{
    /**
     * @since v1.23.06.21
     * @version v1.23.06.25
     */
    public function __construct($obj=null)
    {
        $this->objCopsPlayer = $obj;
    }

    /**
     * @since v1.23.06.20
     * @version v.23.08.12
     */
    public function getProfileAbility(string $field, bool $isReadOnly=false): string
    {
        $label = match($field) {
            self::FIELD_CARAC_CARRURE => self::LABEL_CARRURE,
            self::FIELD_CARAC_CHARME => self::LABEL_CHARME,
            self::FIELD_CARAC_COORDINATION => self::LABEL_COORDINATION,
            self::FIELD_CARAC_EDUCATION => self::LABEL_EDUCATION,
            self::FIELD_CARAC_PERCEPTION => self::LABEL_PERCEPTION,
            self::FIELD_CARAC_REFLEXES => self::LABEL_REFLEXES,
            self::FIELD_CARAC_SANGFROID => self::LABEL_SANGFROID,
            self::FIELD_PV_MAX => self::LABEL_POINTSDEVIE,
            self::FIELD_PAD_MAX => self::LABEL_POINTSADRE,
            self::FIELD_PAN_MAX => self::LABEL_POINTSANC,
            self::FIELD_PX_CUMUL => self::LABEL_POINTSEXPE,
            default => 'Erreur CopsPlayerBean > getProfileAbility()',
        };

        $curValue = match($field) {
            self::FIELD_PV_MAX => $this->objCopsPlayer->getField(self::FIELD_PV_CUR),
            self::FIELD_PAD_MAX => $this->objCopsPlayer->getField(self::FIELD_PAD_CUR),
            self::FIELD_PAN_MAX => $this->objCopsPlayer->getField(self::FIELD_PAN_CUR),
            self::FIELD_PX_CUMUL => $this->objCopsPlayer->getField(self::FIELD_PX_CUR),
            default => $this->objCopsPlayer->getCurrentCarac($field),
        };

        $urlTemplate = self::WEB_PPFD_PFL_ABILITY;
        $attributes = [
            $label,
            $this->objCopsPlayer->getField(self::FIELD_ID),
            'field_'.$field,
            $this->objCopsPlayer->getField($field),
            $isReadOnly ? self::CST_READONLY : '',
            $curValue,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.07.02
     * @version v1.23.07.02
     */
    public function getTableRow()
    {
        $objRow = new TableauRowHtmlBean();
        $urlElements = [
            self::WP_PAGE => self::PAGE_ADMIN,
            self::CST_ONGLET => self::ONGLET_PROFILE,
            self::FIELD_ID => $this->objCopsPlayer->getField(self::FIELD_ID),
        ];
        $strLink = HtmlUtils::getLink(
            $this->objCopsPlayer->getField(self::FIELD_MATRICULE),
            UrlUtils::getPublicUrl($urlElements),
            self::CST_TEXT_WHITE.' ',
        );
        $objRow->addCell(new TableauCellHtmlBean($strLink));
        // Le Nom du COPS
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsPlayer->getField(self::FIELD_NOM)));
        // Le Prénom du COPS
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsPlayer->getField(self::FIELD_PRENOM)));
        // Le Surnom du COPS
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsPlayer->getField(self::FIELD_SURNOM)));
        // Le Grade du COPS
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsPlayer->getField(self::FIELD_GRADE)));
        // La Section du COPS
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsPlayer->getField(self::FIELD_SECTION)));

        return $objRow;
    }


  /*
 * @since 1.22.04.28
 * @version 1.22.04.28
   */
  public function getCopsPlayerComps($isCreation=false)
  {
    return '';
  }

  /**
   * @version v1.23.12.02
   */
  public function getCopsPlayerCarac($isCreation=false)
  {
    $selectAttributes = [
        self::ATTR_CLASS         => 'form-control ajaxAction',
        self::ATTR_DATA   => [
            self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CHANGE,
            self::ATTR_DATA_AJAX    => 'saveData,checkLangue'
        ]
    ];

    $urlTemplate  = 'web/pages/public/fragments/public-fragments-section-caracteristiques-panel';
    $urlTemplate .= ($isCreation ? '-edit' : '').'.php';
    $attributes = [
        // Carrure
        $this->CopsPlayer->getField(self::FIELD_CARAC_CARRURE),
        // Charme
        2,
        // Coordination
        2,
        // Education
        2,
        // Perception
        2,
        // Réflexes
        2,
        // Sang-froid
        2,
        // Points de vie max
        26,
        // Points d'adrénaline
        0,
        // Points d'ancienneté
        0,
        // Langue 2
        $this->CopsLangueServices->getSelectHtml(
            array_merge(
                $selectAttributes,
                [
                    self::ATTR_NAME => 'carac-langue-02',
                    self::ATTR_ID   => 'carac-langue-02',
                ]
            )
        ),
        // Langue 3
        $this->CopsLangueServices->getSelectHtml(
            array_merge(
                $selectAttributes,
                [
                    self::ATTR_NAME  => 'carac-langue-03',
                    self::ATTR_ID    => 'carac-langue-03',
                    self::ATTR_STYLE => self::CSS_DISPLAY_NONE,
                ]
            )
        ),
        // Langue 4
        $this->CopsLangueServices->getSelectHtml(
            array_merge(
                $selectAttributes,
                [
                    self::ATTR_NAME  => 'carac-langue-04',
                    self::ATTR_ID    => 'carac-langue-04',
                    self::ATTR_STYLE => self::CSS_DISPLAY_NONE,
                ]
            )
        ),
        // Langue 5
        $this->CopsLangueServices->getSelectHtml(
            array_merge(
                $selectAttributes,
                [
                    self::ATTR_NAME  => 'carac-langue-05',
                    self::ATTR_ID    => 'carac-langue-05',
                    self::ATTR_STYLE => self::CSS_DISPLAY_NONE,
                ]
            )
        ),
        // Points de vie current
        26,
        // Points d'adrénaline current
        0,
        // Points d'ancienneté current
        0,
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

    /**
     * @since v1.22.11.06
     * @version v1.23.05.28
     */
    public function getLibraryRow(string $href, bool $blnDisplaySection=true): string
    {
        $arrColumns = [];
        // Checkbox ?

        // Le matricule.
        $section = $this->CopsPlayer->getField(self::FIELD_SECTION);
        if (in_array($section, ['A-Alpha', 'B-Epsilon']) || $section=='') {
            $id = $this->CopsPlayer->getField(self::FIELD_ID);
            $mask = 'masks/mask-'.$id.($id=='51' ? '.png' : '.jpg');
        } else {
            $mask = 'masks/mask-000.jpg';
        }
        $imgAttributes = [
            self::ATTR_CLASS => 'mask',
            self::ATTR_SRC => 'https://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/img/'.$mask
        ];
        $tdContent = $this->getBalise(self::TAG_IMG, '', $imgAttributes);
        $color = match ($this->CopsPlayer->getField(self::FIELD_GRADE)) {
            'Capitaine' => 'gold',
            'Lieutenant' => 'silver',
            'Détective' => '#CD7F32',
            default => '',
        };
        $cellAttributes = [self::ATTR_CLASS => 'mailbox-name', self::ATTR_STYLE => 'border-left: 10px solid '.$color];
        $cell = $this->getBalise(self::TAG_TD, $tdContent, $cellAttributes);
        $arrColumns[] = $cell;

        // Le matricule
        $label = substr((string) $this->CopsPlayer->getField(self::FIELD_MATRICULE), 4);
        $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-date']);
        $arrColumns[] = $cell;

        // Le nom
        $label = $this->CopsPlayer->getField(self::FIELD_NOM).' '.$this->CopsPlayer->getField(self::FIELD_PRENOM);
        $href .= self::CST_AMP.self::FIELD_ID.'='.$this->CopsPlayer->getField(self::FIELD_ID);
        $tdContent = HtmlUtils::getLink($label, $href, self::CST_TEXT_WHITE);
        $cell = $this->getBalise(self::TAG_TD, $tdContent, [self::ATTR_CLASS=>'mailbox-date']);
        $arrColumns[] = $cell;
        
        // Le surnom
        $label = $this->CopsPlayer->getField(self::FIELD_SURNOM);
        $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-date']);
        $arrColumns[] = $cell;
        
        if ($blnDisplaySection) {
            // La section
            $label = ($section=='' ? 'N/A' : $section);
            $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-date']);
            $arrColumns[] = $cell;
        }
        
        // Construction de la ligne
        $rowContent = '';
        while (!empty($arrColumns)) {
            $td = array_shift($arrColumns);
            $rowContent .= $td;
        }
        return $this->getBalise(self::TAG_TR, $rowContent);
    }
  
  public function getLibraryCard()
  {
    ////////////////////////////////////////////////////////////////////
    switch ($this->CopsPlayer->getField(self::FIELD_GRADE)) {
      case 'Capitaine' :
        $bordure = self::NOTIF_WARNING;
        $section = 'N/A';
      break;
      case 'Lieutenant' :
        $bordure = self::NOTIF_PRIMARY;
        $section = $this->CopsPlayer->getField(self::FIELD_SECTION);
      break;
      case 'Détective' :
        $bordure = self::NOTIF_LIGHT;
        $section = $this->CopsPlayer->getField(self::FIELD_SECTION);
      break;
      default :
        $bordure = '';
        $section = '';
      break;
    }
    ////////////////////////////////////////////////////////////////////
    if (in_array($section, ['N/A', 'A-Alpha', 'B-Epsilon'])) {
      $id = $this->CopsPlayer->getField(self::FIELD_ID);
      $mask = 'masks/mask-'.$id.($id=='51' ? '.png' : '.jpg');
    } else {
      $mask = 'masks/mask-000.jpg';
    }
    ////////////////////////////////////////////////////////////////////
    $matriculeId = substr((string) $this->CopsPlayer->getField(self::FIELD_MATRICULE), 4);
    ////////////////////////////////////////////////////////////////////
    $surnom = $this->CopsPlayer->getField(self::FIELD_SURNOM);
    if ($surnom=='') {
      $surnom = self::CST_NBSP;
    }
    ////////////////////////////////////////////////////////////////////

    $urlTemplate  = 'web/pages/public/fragments/public-fragments-article-library-cops-extract.php';
    $attributes = [
        // Couleur bordure    : warning / primary / ??
        $bordure,
        // Nom                : Skripnick Jason
        $this->CopsPlayer->getField(self::FIELD_NOM).' '.$this->CopsPlayer->getField(self::FIELD_PRENOM),
        // Masque ou Portrait : masks/mask-001.jpg
        $mask,
        // Surnom             : Capitaine
        $surnom,
        // Matricule          : 001
        $matriculeId,
        // Section            : N/A / A-Alpha
        $section,
    ];
    return $this->getRender($urlTemplate, $attributes);
  }
}
