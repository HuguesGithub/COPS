<?php
namespace core\bean;

/**
 * Classe AdminCopsProfilePageBean
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.23.04.30
 */
class AdminCopsProfilePageBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Construction du menu de l'inbox
        $this->arrSubOnglets = [
            self::CST_PFL_IDENTITY    => [self::FIELD_LABEL => self::LABEL_IDENTITY],
            self::CST_PFL_ABILITIES   => [self::FIELD_LABEL => self::LABEL_ABILITIES],
            self::CST_PFL_SKILLS      => [self::FIELD_LABEL => self::LABEL_SKILLS],
            self::CST_PFL_EQUIPMENT   => [self::FIELD_LABEL => self::LABEL_EQUIPMENT],
            self::CST_PFL_CONTACTS    => [self::FIELD_LABEL => self::LABEL_CONTACTS],
            self::CST_PFL_BACKGROUND  => [self::FIELD_LABEL => self::LABEL_BACKGROUND]
        ];
        /////////////////////////////////////////
    }

    /**
     * @return string
     * @since 1.22.04.28
     * @version 1.22.06.09
     */
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
   */
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
   */
  public function getSubongletAbilities()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-abilities.php';
    $attributes = [
        // Id
        $this->CopsPlayer->getField(self::FIELD_ID),
        // Carrure
        $this->CopsPlayer->getField(self::FIELD_CARAC_CARRURE),
        // Charme
        $this->CopsPlayer->getField(self::FIELD_CARAC_CHARME),
        // Coordination
        $this->CopsPlayer->getField(self::FIELD_CARAC_COORDINATION),
        // Education
        $this->CopsPlayer->getField(self::FIELD_CARAC_EDUCATION),
        // Perception
        $this->CopsPlayer->getField(self::FIELD_CARAC_PERCEPTION),
        // Réflexes
        $this->CopsPlayer->getField(self::FIELD_CARAC_REFLEXES),
        // Sang-froid
        $this->CopsPlayer->getField(self::FIELD_CARAC_SANG_FROID),
        // Bonus/Malus Carrure
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_CARRURE),
        // Bonus/Malus Charme
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_CHARME),
        // Bonus/Malus Coordination
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_COORDINATION),
        // Bonus/Malus Education
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_EDUCATION),
        // Bonus/Malus Perception
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_PERCEPTION),
        // Bonus/Malus Réflexes
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_REFLEXES),
        // Bonus/Malus Sang-froid
        $this->CopsPlayer->getCurrentCarac(self::FIELD_CARAC_SANG_FROID),
        // Initiative minimale
        $this->CopsPlayer->getInitMin(),
        // PV Current
        $this->CopsPlayer->getField(self::FIELD_PV_CUR),
        // PV Max
        $this->CopsPlayer->getField(self::FIELD_PV_MAX),
        // Pts Adrénaline Current
        $this->CopsPlayer->getField(self::FIELD_PAD_CUR),
        // Pts Adrénaline Max
        $this->CopsPlayer->getField(self::FIELD_PAD_MAX),
        // Pts Ancienneté Current
        $this->CopsPlayer->getField(self::FIELD_PAN_CUR),
        // Pts Ancienneté Max
        $this->CopsPlayer->getField(self::FIELD_PAN_MAX),
        // Pts Expérience Current
        $this->CopsPlayer->getField(self::FIELD_PX_CUR),
        // A priori, plus rien après
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
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
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
   */
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
   */
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
   */
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
   */
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

}
