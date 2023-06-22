<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileBean
 * @author Hugues
 * @since v1.23.06.20
 * @version v1.23.06.25
 */
class WpPageAdminProfileBean extends WpPageAdminBean
{
    /**
     * @since v1.23.06.20
     * @version v1.23.06.25
     */
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Construction du menu du profil
        $this->arrSubOnglets = [
            self::CST_PFL_IDENTITY    => [self::FIELD_LABEL => self::LABEL_IDENTITY],
            self::CST_PFL_ABILITIES   => [self::FIELD_LABEL => self::LABEL_ABILITIES],
            self::CST_PFL_SKILLS      => [self::FIELD_LABEL => self::LABEL_SKILLS],
            self::CST_PFL_EQUIPMENT   => [self::FIELD_LABEL => self::LABEL_EQUIPMENT],
            self::CST_PFL_CONTACTS    => [self::FIELD_LABEL => self::LABEL_CONTACTS],
            self::CST_PFL_BACKGROUND  => [self::FIELD_LABEL => self::LABEL_BACKGROUND]
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        $this->urlAttributes = [
            self::WP_PAGE=>$this->slugPage,
            self::CST_ONGLET=>self::ONGLET_PROFILE,
        ];
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_PROFILE,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK]);
        /////////////////////////////////////////
    }
     
    /**
     * @since v1.23.06.20
     * @version v1.23.06.25
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        return match ($slugSubContent) {
            self::CST_PFL_IDENTITY => new WpPageAdminProfileIdentityBean(),
            self::CST_PFL_ABILITIES => new WpPageAdminProfileAbilityBean(),
            //self::CST_MAIL_READ, self::CST_MAIL_WRITE => new WpPageAdminMailBean(),
            default => new WpPageAdminProfileAbilityBean(),
        };
    }

    public function getTabsBar(): string
    {
        /////////////////////////////////////////
        // Construction des onglets
        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $this->urlAttributes[self::CST_SUBONGLET] = $slugSubOnglet;
            $strIcon = '';

            if (!empty($arrData[self::FIELD_ICON])) {
                $strIcon = HtmlUtils::getIcon($arrData[self::FIELD_ICON]).self::CST_NBSP;
            }

            $blnActive = $this->slugSubOnglet==$slugSubOnglet;
            $blnActive |= $this->slugSubOnglet=='' && $slugSubOnglet==self::CST_PFL_ABILITIES;
            $strLink = HtmlUtils::getLink(
                $strIcon.$arrData[self::FIELD_LABEL],
                UrlUtils::getPublicUrl($this->urlAttributes),
                self::NAV_LINK.' '.self::CST_TEXT_WHITE
            );
            $strLis .= $this->getBalise(
                self::TAG_LI,
                $strLink,
                [self::ATTR_CLASS=>self::NAV_ITEM.($blnActive ? ' btn-info' : ' '.self::BTS_BTN_DARK)]
            );
        }
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return $this->getBalise(self::TAG_UL, $strLis, $attributes);
    }

    
    
    /*

    /**
     * @return string
     * @since 1.22.04.28
     * @version 1.22.06.09
     * /
    public function getBoard()
    {
        $this->subOnglet = $this->initVar(self::CST_SUBONGLET, self::CST_PFL_IDENTITY);
        $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();

        $this->buildBreadCrumbs('Profil', self::ONGLET_PROFILE, true);

        $strContent = match ($this->subOnglet) {
            self::CST_PFL_ABILITIES => $this->getSubongletAbilities(),
            self::CST_PFL_BACKGROUND => $this->getSubongletBackground(),
            self::CST_PFL_CONTACTS => $this->getSubongletContacts(),
            self::CST_PFL_EQUIPMENT => $this->getSubongletEquipment(),
            self::CST_PFL_IDENTITY => $this->getSubongletIdentity(),
            self::CST_PFL_SKILLS => $this->getSubongletSkills(),
            default => $this->getOngletContent(),
        };



        // Soit on est loggué et on affiche le contenu du bureau du cops
        $urlTemplate = self::WEB_PP_BOARD;
        $attributes = [
            // La sidebar
            $this->getSideBar(),
            // Le contenu de la page
            $strContent,
            // L'id
            $this->CopsPlayer->getMaskMatricule(),
            // Le nom
            $this->CopsPlayer->getFullName(),
            // La barre de navigation
            $this->getNavigationBar(),
            // Le content header
            $this->getContentHeader(),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

  /**
   * @since 1.22.04.28
   * @version 1.22.04.29
   * /
  public function getOngletContent()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-onglet-menu-panel.php';
    $strContent = '';
    foreach ($this->arrSubOnglets as $subOnglet => $arrData) {
      $attributes = [self::ONGLET_PROFILE, $subOnglet, $arrData[self::FIELD_LABEL]];
      $strContent .= $this->getRender($urlTemplate, $attributes);
    }
    return HtmlUtils::getDiv($strContent, [self::ATTR_CLASS=>'row']);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletBackground()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-background.php';
    $attributes = [
        // Id
        $this->CopsPlayer->getField(self::FIELD_ID),
        // Background
        $this->CopsPlayer->getField(self::FIELD_BACKGROUND),
        // A priori, plus rien après
        '',
        '',
        '',
        '',
        '',
        '',
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletContacts()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-contacts.php';
    // TODO : à implémenter
    $attributes = [
        '1', '2', '3', '4', '5', '6', '7', '8',
        '1', '2', '3', '4', '5', '6', '7', '11',
        '12', '3', '4', '5', '6', '17'
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletEquipment()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-equipment.php';
    // TODO : à implémenter
    $attributes = [
        '1', '2', '3', '4', '5', '6', '7', '8',
        '1', '2', '3', '4', '5', '6', '7', '11',
        '12', '3', '4', '5', '6', '17'
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.28
   * @version 1.22.04.28
   * /
  public function getSubongletIdentity()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-identity.php';
    $attributes = [
        // Prénom
        $this->CopsPlayer->getField(self::FIELD_PRENOM),
        // Nom
        $this->CopsPlayer->getField(self::FIELD_NOM),
        // Surnom
        $this->CopsPlayer->getField(self::FIELD_SURNOM),
        // Date de naissance
        $this->CopsPlayer->getField(self::FIELD_BIRTH_DATE),
        // Taille (cm)
        $this->CopsPlayer->getField(self::FIELD_TAILLE),
        // Poids (kg)
        $this->CopsPlayer->getField(self::FIELD_POIDS),
        // Id
        $this->CopsPlayer->getField(self::FIELD_ID),
        // Grade
        $this->CopsPlayer->getField(self::FIELD_GRADE),
        // Grade Rang
        $this->CopsPlayer->getField(self::FIELD_GRADE_RANG),
        // Echelon
        $this->CopsPlayer->getField(self::FIELD_GRADE_ECHELON),
        // Section
        $this->CopsPlayer->getField(self::FIELD_SECTION),
        // Section Lieutenant
        $this->CopsPlayer->getField(self::FIELD_SECTION_LIEUTENANT),
        // Date d'intégration
        $this->CopsPlayer->getField(self::FIELD_INTEGRATION_DATE),
        // A priori, plus rien après
        '',
        '',
        '',
        '',
        '',
        '',
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletSkills()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-skills.php';
    // TODO : à implémenter
    $attributes = [
        '1', '2', '3', '4', '5', '6', '7', '8',
        '1', '2', '3', '4', '5', '6', '7', '11',
        '12', '3', '4', '5', '6', '17'
    ];
    return $this->getRender($urlTemplate, $attributes);
  }
*/
}
