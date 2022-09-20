<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsPlayer
 * @author Hugues
 * @version 1.22.04.28
 * @since 1.22.04.28
 */
class CopsPlayer extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   *
   * @var string $matricule
   */
  protected $matricule;
  /**
   *
   * @var string $password
   */
  protected $password;
  //
  protected $nom;
  protected $prenom;
  protected $surnom;
  // Caractéristiques
  protected $carac_carrure;
  protected $carac_charme;
  protected $carac_coordination;
  protected $carac_education;
  protected $carac_perception;
  protected $carac_reflexes;
  protected $carac_sangfroid;
  // Points de vie d'adrénaline et d'ancienneté
  protected $pv_max;
  protected $pv_cur;
  protected $pad_max;
  protected $pad_cur;
  protected $pan_max;
  protected $pan_cur;
  // Autres
  protected $birth_date;
  protected $taille;
  protected $poids;
  protected $sexe;
  protected $ethnie;
  protected $cheveux;
  protected $yeux;
  protected $etudes;
  protected $origine_sociale;
  protected $grade;
  protected $grade_rang;
  protected $grade_echelon;
  protected $integration_date;
  protected $section;
  protected $section_lieutenant;
  protected $background;
  protected $px_max;


  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function getId()
  { return $this->id; }
  /**
   * @param int $id
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function setId($id)
  { $this->id = $id; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    //$this->Services              = new GonePresenceServices();
    $this->stringClass = 'CopsPlayer';
  }
  /**
   * @param array $row
   * @return CopsPlayer
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsPlayer(), $row); }
  /**
   * @return CopsPlayerBean
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function getBean()
  { return new CopsPlayerBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  public function getMaskMatricule()
  {
    $maskMatricule = $this->id;
    /*
    if (!is_file('/wp-content/plugins/hj-cops/web/rsc/img/masks/mask-'.$maskMatricule.'.jpg')) {
      $maskMatricule = '000';
    }
    * */
    return $maskMatricule;
  }
  public function getMatricule()
  { return substr($this->matricule, -3); }

  public function getFullName()
  { return $this->prenom.' '.$this->nom; }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public static function getCurrentCopsPlayer()
  {
    if (isset($_SESSION[self::FIELD_MATRICULE])) {
      $attributes[self::SQL_WHERE_FILTERS] = array(
        self::FIELD_ID => self::SQL_JOKER_SEARCH,
        self::FIELD_MATRICULE => $_SESSION[self::FIELD_MATRICULE],
        self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH,
      );
      $Services = new CopsPlayerServices();
      $CopsPlayers = $Services->getCopsPlayers($attributes);
      $CopsPlayer = array_shift($CopsPlayers);
    } else {
      $CopsPlayer = new CopsPlayer();
    }
    return $CopsPlayer;
  }

  public function getStrPoids($format)
  {
    $strFormatted = '';
    switch ($format) {
      case 'd/m/Y' :
        $strFormatted = substr($this->birth_date, 8, 2).'/'.substr($this->birth_date, 5, 2).'/'.substr($this->birth_date, 0, 4);
      break;
    }
    return $strFormatted;
  }

  public function updateCopsPlayer()
  {  $this->Services->updateLocal($this); }

  /*
   * @since 1.22.05.25
   * @version 1.22.05.25
   */
  public function getCurrentCarac($carac)
  {
    $scoreBase = $this->getField($carac);
    // TODO : ajouter les bonus/malus à la carractéristique.
    // Envisager de le coloriser pour mettre en évidence le bonus ou le malus ?
    return $scoreBase;
  }

  /*
   * @since 1.22.05.25
   * @version 1.22.05.25
   */
  public function getInitMin()
  { return 3-$this->getCurrentCarac(self::FIELD_CARAC_REFLEXES); }


}
