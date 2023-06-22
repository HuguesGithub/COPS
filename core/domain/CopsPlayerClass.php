<?php
namespace core\domain;

use core\bean\CopsPlayerBean;

/**
 * Classe CopsPlayerClass
 * @author Hugues
 * @version 1.22.04.28
 * @version v1.23.06.25
 */
class CopsPlayerClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $matricule;
    protected $password;
    //
    protected $nom;
    protected $prenom;
    protected $surnom;
    // Caractéristiques
    protected $caracCarrure;
    protected $caracCharme;
    protected $caracCoordination;
    protected $caracEducation;
    protected $caracPerception;
    protected $caracReflexes;
    protected $caracSangfroid;
    // Points de vie d'adrénaline et d'ancienneté
    protected $pvMax;
    protected $pvCur;
    protected $padMax;
    protected $padCur;
    protected $panMax;
    protected $panCur;
    // Autres
    protected $birthDate;
    protected $taille;
    protected $poids;
    protected $sexe;
    protected $ethnie;
    protected $cheveux;
    protected $yeux;
    protected $etudes;
    protected $origineSociale;
    protected $grade;
    protected $gradeRang;
    protected $gradeEchelon;
    protected $integrationDate;
    protected $section;
    protected $background;
    protected $status;
    protected $pxCumul;
    protected $pxCur;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

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
     * @version 1.22.04.27
     * @since 1.22.04.27
     */
    public static function convertElement($row): CopsPlayerClass
    { return parent::convertRootElement(new CopsPlayerClass(), $row); }

    /**
     * @version 1.22.04.27
     * @since 1.22.04.27
     */
    public function getBean(): CopsPlayerBean
    { return new CopsPlayerBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    public function getMaskMatricule()
    { return substr($this->matricule, -3); }
  
    public function getFullName()
    { return $this->prenom.' '.$this->nom; }

    /*
     * @since v1.23.06.20
     * @version v1.23.06.25
    */
    public function getCurrentCarac(string $carac): string
    {
        $scoreBase = $this->getField($carac);
        // TODO : ajouter les bonus/malus à la carractéristique.
        // Envisager de le coloriser pour mettre en évidence le bonus ou le malus ?
        return 0;//$scoreBase;
    }
  
    /*
     * @since v1.23.06.20
     * @version v1.23.06.25
    */
    public function getInitMin()
    { return 3-$this->getCurrentCarac(self::FIELD_CARAC_REFLEXES); }










    /*
  public function getMatricule()
  { return substr($this->matricule, -3); }

  /**
   * @since 1.23.04.21
   * /
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

  */
}
