<?php
namespace core\bean;

use core\enum\SectionEnum;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileIdentityBean
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.07.02
 */
class WpPageAdminProfileIdentityBean extends WpPageAdminProfileBean
{
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
     * @version v1.23.07.02
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
            $this->getThirdBlock(),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.07.02
     */
    public function getFirstBlock(): string
    {
        $urlTemplate = self::WEB_PPFD_PFL_ID_NAME;
        $attributes = [
            // Id du profile
            $this->objCopsPlayer->getField(self::FIELD_ID),
            // Nom, Prénom et Surnom
            $this->objCopsPlayer->getField(self::FIELD_NOM),
            $this->objCopsPlayer->getField(self::FIELD_PRENOM),
            $this->objCopsPlayer->getField(self::FIELD_SURNOM),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.07.02
     */
    public function getSecondBlock(): string
    {
        $urlTemplate = self::WEB_PPFD_PFL_ID_PHYSIQUE;
        $attributes = [
            // Id du profile
            $this->objCopsPlayer->getField(self::FIELD_ID),
            // Date de naissance, Taille, Poids
            $this->objCopsPlayer->getField(self::FIELD_BIRTH_DATE),
            $this->objCopsPlayer->getField(self::FIELD_TAILLE),
            $this->objCopsPlayer->getField(self::FIELD_POIDS),
            // Cheveux et Yeux
            $this->objCopsPlayer->getField(self::FIELD_CHEVEUX),
            $this->objCopsPlayer->getField(self::FIELD_YEUX),
            // Sexe et Ethnie
            $this->objCopsPlayer->getField(self::FIELD_SEXE),
            $this->objCopsPlayer->getField(self::FIELD_ETHNIE),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.25
     * @version v1.23.07.02
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
            SectionEnum::from($strSection)->label(),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_RANG),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_ECHELON),
            $this->objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
