<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsProfilePageBean
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.05.19
 */
class AdminCopsProfilePageBean extends AdminCopsPageBean implements ConstantsInterface
{
  public function __construct()
  {
    parent::__construct();

    /////////////////////////////////////////
    // Construction du menu de l'inbox
    $this->arrSubOnglets = array(
      self::CST_PFL_IDENTITY    => array(self::FIELD_LABEL => 'Identité'),
      self::CST_PFL_ABILITIES   => array(self::FIELD_LABEL => 'Caractéristiques'),
      self::CST_PFL_SKILLS      => array(self::FIELD_LABEL => 'Compétences'),
      self::CST_PFL_EQUIPMENT   => array(self::FIELD_LABEL => 'Équipement'),
      self::CST_PFL_CONTACTS    => array(self::FIELD_LABEL => 'Contacts'),
      self::CST_PFL_BACKGROUND  => array(self::FIELD_LABEL => 'Background'),
    );
    /////////////////////////////////////////

  }

  /**
   * @return string
   * @since 1.22.04.28
   * @version 1.22.06.09
   */
  public function getBoard()
  {
    $this->subOnglet = (isset($this->urlParams[self::CST_SUBONGLET]) ? $this->urlParams[self::CST_SUBONGLET] : self::CST_PFL_IDENTITY);
    $this->CopsPlayer = CopsPlayer::getCurrentCopsPlayer();

    $this->buildBreadCrumbs('Profil', self::ONGLET_PROFILE, true);

    switch ($this->subOnglet) {
      case self::CST_PFL_ABILITIES :
        $strContent = $this->getSubongletAbilities();
      break;
      case self::CST_PFL_BACKGROUND :
        $strContent = $this->getSubongletBackground();
      break;
      case self::CST_PFL_CONTACTS :
        $strContent = $this->getSubongletContacts();
      break;
      case self::CST_PFL_EQUIPMENT :
        $strContent = $this->getSubongletEquipment();
      break;
      case self::CST_PFL_IDENTITY :
        $strContent = $this->getSubongletIdentity();
      break;
      case self::CST_PFL_SKILLS :
        $strContent = $this->getSubongletSkills();
      break;
      default :
        $strContent = $this->getOngletContent();
      break;
    }



    // Soit on est loggué et on affiche le contenu du bureau du cops
    $urlTemplate = 'web/pages/public/public-board.php';
    $attributes = array(
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

      '', '', '', '', '', '', '', '', '', '', '',
    );
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
      $attributes = array(self::ONGLET_PROFILE, $subOnglet, $arrData[self::FIELD_LABEL]);
      $strContent .= $this->getRender($urlTemplate, $attributes);
    }
    return $this->getBalise(self::TAG_DIV, $strContent, array(self::ATTR_CLASS=>'row'));
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function getSubongletAbilities()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-abilities.php';
    $attributes = array(
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
      '', '', '', '', '', '',
      '', '', '', '', '', '',
      '', '', '', '', '', '',
      '', '', '', '', '', '',
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function getSubongletBackground()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-background.php';
    $attributes = array(
      // Id
      $this->CopsPlayer->getField(self::FIELD_ID),
      // Background
      $this->CopsPlayer->getField(self::FIELD_BACKGROUND),
      // A priori, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function getSubongletContacts()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-contacts.php';
    $attributes = array(
      '1', '2', '3', '4', '5', '6', '7', '8',
      '1', '2', '3', '4', '5', '6', '7',
      '11', '12', '3', '4', '5', '6', '17',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function getSubongletEquipment()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-equipment.php';
    $attributes = array(
      '1', '2', '3', '4', '5', '6', '7', '8',
      '1', '2', '3', '4', '5', '6', '7',
      '11', '12', '3', '4', '5', '6', '17',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getSubongletIdentity()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-identity.php';
    $attributes = array(
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
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function getSubongletSkills()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-skills.php';
    $attributes = array(
      '1', '2', '3', '4', '5', '6', '7', '8',
      '1', '2', '3', '4', '5', '6', '7',
      '11', '12', '3', '4', '5', '6', '17',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
