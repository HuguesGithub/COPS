<?php
namespace core\bean;

use core\enum\SectionEnum;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileIdentityBean
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.12
 */
class WpPageAdminProfileIdentityBean extends WpPageAdminProfileBean
{
    private $spanCol3 = [self::ATTR_CLASS=>'input-group-text col-3'];
    private $baseInpAttr = [
        self::ATTR_TYPE  => 'text',
        self::ATTR_CLASS => 'form-control col-9 ajaxAction',
        self::ATTR_DATA  => [
            self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CHANGE,
            self::ATTR_DATA_AJAX    => self::AJAX_SAVE_DATA,
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $this->urlAttributes[self::CST_SUBONGLET] = self::CST_PFL_IDENTITY;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_IDENTITY,
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
        $urlTemplate = self::WEB_PPFS_PFL_IDENTITY;
        $attributes = [
            // Url du formulaire
            UrlUtils::getPublicUrl($this->urlAttributes),
            // Onglets
            $this->getTabsBar(),
            // Premier Block
            $this->getFirstBlock(),
            // Deuxième Block
            $this->getSecondBlock(),
            // Troisième Block
            $this->isCreate1stStep ? '' : $this->getThirdBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getFirstBlock(): string
    {
        //////////////////////////////////////////////////
        // Construction de l'input Surnom
        if ($this->isCreate1stStep) {
            $thirdCell = '';
        } else {
            $thirdCell = $this->getCellContent('Surnom', self::FIELD_SURNOM, false);
        }
        //////////////////////////////////////////////////

        $urlTemplate = self::WEB_PPFD_PFL_ID_NAME;
        $attributes = [
            // Nom, Prénom et Surnom
            $this->getCellContent('Nom', self::FIELD_NOM, !$this->isCreate1stStep),
            $this->getCellContent('Prénom', self::FIELD_PRENOM, !$this->isCreate1stStep),
            $thirdCell,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getSecondBlock(): string
    {
        $urlTemplate = self::WEB_PPFD_PFL_ID_PHYSIQUE;
        $attributes = [
            // Id du profile
            $this->objCopsPlayer->getField(self::FIELD_ID),
            // Date de naissance, Taille, Poids
            $this->objCopsPlayer->getField(self::FIELD_BIRTH_DATE),
            $this->getCellContent('Taille', self::FIELD_TAILLE, !$this->isCreate1stStep),
            $this->getCellContent('Poids', self::FIELD_POIDS, !$this->isCreate1stStep),
            // Cheveux et Yeux
            $this->getCellContent('Cheveux', self::FIELD_CHEVEUX, !$this->isCreate1stStep),
            $this->getCellContent('Yeux', self::FIELD_YEUX, !$this->isCreate1stStep),
            // Sexe et Ethnie
            $this->getCellContent('Genre', self::FIELD_SEXE, !$this->isCreate1stStep),
            $this->getCellContent('Ethnie', self::FIELD_ETHNIE, !$this->isCreate1stStep),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.08.12
     */
    public function getThirdBlock(): string
    {
        $strSection = $this->objCopsPlayer->getField(self::FIELD_SECTION);
        $urlTemplate = self::WEB_PPFD_PFL_ID_GRADE;
        $attributes = [
            // Id du profile
            $this->objCopsPlayer->getField(self::FIELD_ID),
            // Grade, Rang, Echelon, Section, Date d'intégration
            $this->objCopsPlayer->getField(self::FIELD_GRADE),
            $strSection=='' ? '' : SectionEnum::from($strSection)->label(),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_RANG),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_ECHELON),
            $this->objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.12
     */
    private function getCellContent(string $label, string $field, bool $isReadonly): string
    {
        $span = HtmlUtils::getBalise(self::TAG_SPAN, $label, $this->spanCol3);
        $this->baseInpAttr[self::ATTR_DATA]['objid'] = $this->objCopsPlayer->getField(self::FIELD_ID);
        $this->baseInpAttr[self::FIELD_ID] = 'field_'.$field;
        $this->baseInpAttr[self::ATTR_NAME] = 'field_'.$field;
        $this->baseInpAttr[self::ATTR_VALUE] = $this->objCopsPlayer->getField($field);

        if ($isReadonly) {
            $this->baseInpAttr[self::CST_READONLY] = self::CST_READONLY;
        } else {
            unset($this->baseInpAttr[self::CST_READONLY]);
        }
        $input = HtmlUtils::getBalise(self::TAG_INPUT, '', $this->baseInpAttr);
        return $span.$input;
    }
}
