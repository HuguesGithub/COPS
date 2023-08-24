<?php
namespace core\domain;

use core\bean\CopsPlayerBean;
use core\services\CopsSkillServices;

/**
 * Classe CopsPlayerClass
 * @author Hugues
 * @version 1.22.04.28
 * @version v1.23.08.12
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
    // Le statut du personnage peut impliquer des redirections obligatoires sur le site
    // 11 : première connexion, l'utilisateur doit définir un nouveau mot de passe.
    // 21 : l'utilisateur doit définir un nouveau mot de passe.
    // 31 : l'utilisateur est à la première étape de création de son personnage.
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
        return $scoreBase;
    }
  
    /*
     * @since v1.23.06.20
     * @version v1.23.06.25
    */
    public function getInitMin()
    { return 3-$this->getCurrentCarac(self::FIELD_CARAC_REFLEXES); }


    /*
     * @since v1.23.06.22
     * @version v1.23.08.12
    */
    public function checkField(string $field, $value): bool
    {
        $blnOk = true;
        switch ($field) {
            case self::FIELD_NOM :
            case self::FIELD_PRENOM :
                if ($value=='') {
                    $blnOk = false;
                }
                break;
            case self::FIELD_CARAC_CARRURE :
            case self::FIELD_CARAC_CHARME :
            case self::FIELD_CARAC_COORDINATION :
            case self::FIELD_CARAC_EDUCATION :
            case self::FIELD_CARAC_PERCEPTION :
            case self::FIELD_CARAC_REFLEXES :
            case self::FIELD_CARAC_SANGFROID :
                if (!is_numeric($value) || $value<2 || $value>5) {
                    $blnOk = false;
                }
                break;
            case self::FIELD_TAILLE :
            case self::FIELD_POIDS :
                if (!is_numeric($value)) {
                    $blnOk = false;
                }
                break;
            default :
                break;
        }
        return $blnOk;
    }

    /**
     * @version v1.23.08.12
     */
    public function getCopsSkills(): array
    {
        $attributes = [self::FIELD_COPS_ID => $this->id];
        $objSkillServices = new CopsSkillServices();
        return $objSkillServices->getCopsSkills($attributes);
    }

    /**
     * @since v1.23.08.12
     */
    public function isOverStep(int $step, string &$status=''): bool
    {
        $arrCaracs = [self::FIELD_CARAC_CARRURE, self::FIELD_CARAC_CHARME, self::FIELD_CARAC_COORDINATION,
            self::FIELD_CARAC_EDUCATION, self::FIELD_CARAC_PERCEPTION, self::FIELD_CARAC_REFLEXES,
            self::FIELD_CARAC_SANGFROID];

        $blnOk = true;
        if ($step==1) {
            if ($this->nom=='' || $this->prenom=='') {
                $blnOk = false;
                $status = "Le nom (".$this->nom.") ou le prénom (".$this->prenom.") n'est pas renseigné.";
            } else {
                $nb5 = 0;
                $somme = 0;
                foreach ($arrCaracs as $carac) {
                    $score = $this->{$carac};
                    $somme += $score;
                    if ($score<2 || $score>5) {
                        $blnOk = false;
                        $status = "Une caractéristique est inférieure à 2 ou supérieure à 5.";
                    } elseif ($score==5) {
                        ++$nb5;
                    } else {
                        // TODO : pour Sonar
                    }
                }
                if ($somme!=21 || $nb5>=2) {
                    $blnOk = false;
                    $status  = "La somme des caractéristiques ne fait pas 21 ($somme) ";
                    $status .= "ou plus d'une caractéristique ($nb5) est à 5.";
                }
            }
        }
        return $blnOk;
    }

    /**
     * @since v1.23.08.12
     */
    public function validFirstCreationStep(): void
    {
        $objSkillServices = new CopsSkillServices();

        $this->pvMax = 20 + 3*$this->caracCarrure;
        $this->pvCur = 20 + 3*$this->caracCarrure;
        $this->grade = 'Détective';
        $this->gradeRang = 2;
        $this->gradeEchelon = 1;
        $this->integrationDate = '2030-06-03';
        $this->section = 'alpha';
        $this->status = self::PS_CREATE_2ND_STEP;

        // Possibilités de langues :
        // Autant que max(Education, Charme)
        $nbLangues = max($this->caracEducation, $this->caracCharme);
        switch ($nbLangues) {
            case 5 :
                // Si 5 langues, on a 2, 4, 5, 6 et 7
                $arrScores = [2, 4, 5, 6, 7];
                break;
            case 4 :
                // Si 4 langues, on a 3, 4, 5 et 7
                $arrScores = [3, 4, 5, 7];
                break;
            case 3 :
                // Si 3 langues, on a 4, 5 et 7
                $arrScores = [4, 5, 7];
                break;
            case 2 :
            default :
                // Si 2 langues, on a 5 et 7
                $arrScores = [5, 7];
                break;
        }
        foreach ($arrScores as $score) {
            $attributes = [self::FIELD_COPS_ID => $this->id, self::FIELD_SKILL_ID => 34, self::FIELD_SCORE => $score];
            $obj = new CopsSkillJointClass($attributes);
            $objSkillServices->insertPlayerSkill($obj);
        }

        // Arme d'épaule 8+
        // Arme de contact 8+
        // Arme de poing 7+
        // Athlétisme 7+
        // Bureaucratie 8+
        // Conduite[Voiture] 7+
        // Corps à corps [coups, projection ou immobilisation] 7+
        // Une seule doit être conservée parmi les trois suivantes
        // Discrétion 7+
        // Informatique 7+
        // Instinct de flic 9+
        // Premiers secours 8+
        // Scène de crime 7+
        // Eloquence, Intimidation ou Rhétorique 7+
        // Une seule doit être conservée parmi les trois suivantes
        $baseSkills = [
            [1, 8], [2, 8], [3, 7], [5, 7], [6, 8], [7, 9], [7, 7, 17], [9, 8], [9, 7, 22], [9, 7, 23], [9, 7, 24],
            [11, 7], [16, 7], [17, 9], [26, 8], [31, 7], [13, 7], [18, 7], [30, 7],
        ];
        // Ajout de compétences de base.
        foreach ($baseSkills as $statSkill) {
            [$skillId, $score, $specSkillId] = $statSkill;
            if ($specSkillId!=null) {
                $attributes = [
                    self::FIELD_COPS_ID => $this->id,
                    self::FIELD_SKILL_ID => $skillId,
                    self::FIELD_SPEC_SKILL_ID => $specSkillId,
                    self::FIELD_SCORE => $score
                ];
            } else {
                $attributes = [
                    self::FIELD_COPS_ID => $this->id,
                    self::FIELD_SKILL_ID => $skillId,
                    self::FIELD_SCORE => $score
                ];
            }
            $obj = new CopsSkillJointClass($attributes);
            $objSkillServices->insertPlayerSkill($obj);
        }
        // 10 points de compétence à répartir
        // Diminuer une compétence initiale de 1 ou 2 (1 ou 2 points), 5 max
        // Acquérir de nouvelles compétences à 9+ ou 8+ (1 ou 2 points)
    }






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
