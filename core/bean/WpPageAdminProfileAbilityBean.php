<?php
namespace core\bean;

use core\domain\CopsPlayerClass;
use core\services\CopsPlayerServices;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileAbilityBean
 * @author Hugues
 * @since v1.23.06.20
 * @version v1.23.06.25
 */
class WpPageAdminProfileAbilityBean extends WpPageAdminProfileBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $this->urlAttributes = [
            self::WP_PAGE=>$this->slugPage,
            self::CST_ONGLET=>self::ONGLET_PROFILE,
            self::CST_SUBONGLET=>self::CST_PFL_ABILITIES,
        ];
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
        ];
        return $this->getTabsBar().$this->getRender($urlTemplate, $attributes);
    }
}
