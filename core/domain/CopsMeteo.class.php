<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMeteo
 * @author Hugues
 * @version 1.22.09.05
 * @since 1.22.09.05
 */
class CopsMeteo extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;

  protected $dateMeteo;
  protected $heureMeteo;
  protected $temperature;
  protected $weather;
  protected $weatherId;
  protected $forceVent;
  protected $sensVent;
  protected $humidite;
  protected $barometre;
  protected $visibilite;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->stringClass = 'CopsMeteo';

    // Pattern des données issues du site web extérieur
    $this->pattern = "/th>([0-9:]*).*title=\"([a-zA-Z\. ]*).*wt-([0-9]*).*>([0-9]*)&nbsp;°.*>(N\/A|No wind|[0-9]* km\/h)<.*comp sa([0-9]*)\".*>([0-9]*)%.*>(N\/A|([0-9]*) mbar).*>(N\/A|([0-9]*)&nbsp;km).*</";
  }
  /**
   * @param array $row
   * @return CopsMeteo
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public static function convertElement($row)
  { return parent::convertRootElement(new CopsMeteo(), $row); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

  public function parseData($str, $str_date)
  {
    $str_compteRendu = '';
    // On recherche le pattern dans la ligne
    if (preg_match_all($this->pattern, $str, $matches)) {
      // Si on le trouve, on a une entrée à saisir. Sauf si elle est déjà présente.
      $this->dateMeteo    = $str_date;
      $this->heureMeteo   = $matches[1][0];
      $this->temperature  = $matches[4][0];
      $this->weather      = $matches[2][0];
      $str_compteRendu .= $this->controlerWeather();
      $this->weatherId    = $matches[3][0];
      $this->forceVent    = $matches[5][0];
      $this->controlerForceVent();
      $this->sensVent     = $matches[6][0];
      $this->humidite     = $matches[7][0];
      $this->barometre    = $matches[8][0];
      $this->visibilite   = $matches[9][0];

      // On peut envisager des contrôles sur les données saisies...

      // Idéalement, il faudrait vérifier que l'entrée n'est pas déjà présente
      // On se base sur le couple (dateMeteo, heureMeteo).
      if ($this->checkIfExists()) {
        // S'il est présent, on pourrait envisager de le mettre à jour si nécessaire.
        $str_compteRendu .= "Cette donnée (".$this->dateMeteo.", ".$this->heureMeteo.") existe déjà.<br>";
      } else {
        // Sinon, on doit l'insérer
        $str_compteRendu .= $this->insertCopsMeteo();
      }
    } else {
      $str_compteRendu .= "<strong>/!\</strong> Erreur d'analyse dans la chaîne suivante :<br>$str<br><br>";
    }
    return $str_compteRendu;
  }

  public function controlerForceVent()
  {
    if ($this->forceVent=='N/A' || $this->forceVent=='No wind') {
      $this->forceVent = 0;
    }
  }

  public function controlerWeather()
  {
    $requete = "SHOW COLUMNS FROM wp_7_cops_meteo LIKE 'weather';";
    $rows = MySQL::wpdbSelect($requete);
    $str_enumType = $rows[0]->Type;
    preg_match('/enum\((.*)\)$/', $str_enumType, $matches);
    $arr_vals = explode(',', $matches[1]);
    if (!in_array("'".$this->weather."'", $arr_vals)) {
      $requete = "ALTER TABLE wp_7_cops_meteo MODIFY COLUMN weather ENUM (".implode(",", $arr_vals).", '".$this->weather."');";
      MySQL::wpdbQuery($requete);
      return '<strong>/!\</strong> Attention, nouvelle valeur pour le champ weather <em>'.$this->weather.'</em>.<br>';
    }
  }

  public function checkIfExists()
  {
    $requete  = "SELECT id FROM wp_7_cops_meteo WHERE ";
    $requete .= "dateMeteo = '".$this->dateMeteo."' AND ";
    $requete .= "heureMeteo = '".$this->heureMeteo."';";
    $rows = MySQL::wpdbSelect($requete);
    return !empty($rows);
  }

  public function insertCopsMeteo()
  {
      $requete  = "INSERT INTO wp_7_cops_meteo ";
      $requete .= "(dateMeteo, heureMeteo, temperature, weather, weatherId, forceVent, sensVent, humidite, barometre, visibilite) ";
      $requete .= "VALUES (";
      $requete .= "'".$this->dateMeteo."', ";      // La date
      $requete .= "'".$this->heureMeteo."', "; // L'heure
      $requete .= "'".$this->temperature."', "; // La température
      $requete .= "'".$this->weather."', "; // La météo : certains ne sont peut-être pas définis dans l'enum...
      $requete .= "'".$this->weatherId."', "; // L'id météo
      $requete .= "'".$this->forceVent."', "; // La force du vent
      $requete .= "'".$this->sensVent."', "; // La direction du vent : prefixé par "sa", donne une class pour orienter la flèche.
      $requete .= "'".$this->humidite."', "; // L'humidité
      $requete .= "'".$this->barometre."', "; // Le baromètre
      $requete .= "'".$this->visibilite."'";   // La visibilité
      $requete .= ");";

      MySQL::wpdbQuery($requete);
      return '';
  }

  public function getLastInsertFormatted()
  {
    $requete  = "SELECT dateMeteo FROM wp_7_cops_meteo ORDER BY dateMeteo DESC;";
    $rows = MySQL::wpdbSelect($requete);
    return $rows[0]->dateMeteo;
  }

  public function getUrlForNextInsert()
  {
    $str_lastInsert = $this->getLastInsertFormatted();
    $int_ts = mktime(0, 0, 0, substr($str_lastInsert, 4, 2), substr($str_lastInsert, 6)+1, substr($str_lastInsert, 0, 4));
    return date('Ymd', $int_ts);
  }
}