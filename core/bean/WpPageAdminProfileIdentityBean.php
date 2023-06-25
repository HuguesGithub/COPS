<?php
namespace core\bean;

use core\domain\CopsPlayerClass;
use core\services\CopsPlayerServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileIdentityBean
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.06.25
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
     * @version v1.23.06.25
     */
    public function getOngletContent(): string
    {
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

        $urlTemplate = self::WEB_PPFS_PFL_IDENTITY;
        $attributes = [
            // Url du formulaire
            UrlUtils::getPublicUrl($this->urlAttributes),
            // Id du profile
            $this->objCopsPlayer->getField(self::FIELD_ID),
            // Nom, Prénom et Surnom
            $this->objCopsPlayer->getField(self::FIELD_NOM),
            $this->objCopsPlayer->getField(self::FIELD_PRENOM),
            $this->objCopsPlayer->getField(self::FIELD_SURNOM),
            // Date de naissance, Taille, Poids
            $this->objCopsPlayer->getField(self::FIELD_BIRTH_DATE),
            $this->objCopsPlayer->getField(self::FIELD_TAILLE),
            $this->objCopsPlayer->getField(self::FIELD_POIDS),
            // Grade, Rang, Echelon, Section, Date d'intégration
            $this->objCopsPlayer->getField(self::FIELD_GRADE),
            $this->objCopsPlayer->getField(self::FIELD_SECTION),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_RANG),
            $this->objCopsPlayer->getField(self::FIELD_GRADE_ECHELON),
            $this->objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE),
        ];
        return $this->getTabsBar().$this->getRender($urlTemplate, $attributes);
    }
}
