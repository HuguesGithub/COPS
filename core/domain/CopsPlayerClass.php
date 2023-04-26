<?php
namespace core\domain;

/**
 * Classe CopsPlayerClass
 * @author Hugues
 * @version 1.22.04.28
 * @version 1.23.04.30
 */
class CopsPlayerClass extends LocalDomainClass
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $matricule;
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
  public function __construct(array $attributes=[])
  {
    parent::__construct($attributes);
    $this->stringClass = 'core\domain\CopsPlayerClass';
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
   * @since 1.23.04.21
   */
    public static function getCurrentCopsPlayer()
    {
        $attributes = [];
        if (isset($_SESSION[self::FIELD_MATRICULE])) {
            $attributes[self::SQL_WHERE_FILTERS] = [
                self::FIELD_ID => self::SQL_JOKER_SEARCH,
                self::FIELD_MATRICULE => $_SESSION[self::FIELD_MATRICULE],
                self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH
            ];
            $objCopsPlayerServices = new CopsPlayerServices();
            $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
            $objCopsPlayer = array_shift($objsCopsPlayer);
        } else {
            $objCopsPlayer = new CopsPlayerClass();
        }
        return $objCopsPlayer;
    }

    // C'est quoi ce nom de méthode vu le contenu ?
    public function getStrPoids($format)
    {
        $strFormatted = '';
        // Anciennement un switch.
        if ($format=='d/m/Y') {
            $strFormatted  = substr((string) $this->birth_date, 8, 2);
            $strFormatted .= '/'.substr((string) $this->birth_date, 5, 2);
            $strFormatted .= '/'.substr((string) $this->birth_date, 0, 4);
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
