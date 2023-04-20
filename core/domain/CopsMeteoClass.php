<?php
namespace core\domain;

use core\bean\UtilitiesBean;
use core\services\CopsMeteoServices;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMeteoClass
 * @author Hugues
 * @version 1.22.09.05
 * @since 1.22.09.05
 */
class CopsMeteoClass extends LocalDomainClass
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
    public function __construct($attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsMeteoClass';

        // Pattern des données issues du site web extérieur
        $this->pattern = "/th>([0-9:]*).*title=\"([a-zA-Z\. ]*).*wt-([0-9]*).*>([0-9]*)&nbsp;°.*>(N\/A|No wind|[0-9]* km\/h)<.*comp sa([0-9]*)\".*>([0-9]*)%.*>(N\/A|([0-9]*) mbar).*>(N\/A|([0-9]*)&nbsp;km).*</";
    }

  /**
   * @param array $row
   * @return CopsMeteo
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
    public static function convertElement($row): CopsMeteoClass
    {
        return parent::convertRootElement(new CopsMeteoClass(), $row);
    }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function init(): void
    {
        $strDate = UtilitiesBean::getCopsDate(self::FORMAT_DATE_YMDHIS);
        [$strJour, $strHeure] = explode(' ', $strDate);
        [$Y, $m, $d] = explode('-', $strJour);
        [$H, $i, ] = explode(':', $strHeure);

        $objCopsMeteoServices = new CopsMeteoServices();
        //$this->objCopsSoleil = $objCopsMeteoServices->getSoleil($strJour);
        // TODO : supprimer le -8.
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos(($Y-8).$m.$d);

        //$this->id = $this->objCopsSoleil->getField(self::FIELD_ID);
        $this->dateMeteo = $Y.$m.$d;

        $this->maxTemp = -15;
        $this->minTemp = 55;
        while (!empty($objsCopsMeteo)) {
            $objCopsMeteo = array_shift($objsCopsMeteo);
            $heureMeteo = $objCopsMeteo->getField(self::FIELD_HEURE_METEO);
            $temperature = $objCopsMeteo->getField(self::FIELD_TEMPERATURE);

            if ($temperature>$this->maxTemp) {
                $this->maxTemp = $temperature;
            }
            if ($temperature<$this->minTemp) {
                $this->minTemp = $temperature;
            }

            if ($heureMeteo>$H.':'.$i) {
                continue;
            }

            $this->heureMeteo = $heureMeteo;
            $this->temperature = $temperature;
            $this->weather = $objCopsMeteo->getField(self::FIELD_WEATHER);
            $this->weatherId = $objCopsMeteo->getField(self::FIELD_WEATHER_ID);
        }
    }

    public function getStrHour(): string
    {
        return UtilitiesBean::getCopsDate(self::FORMAT_DATE_HIS);
    }

    public function getStrDate(): string
    {
        return UtilitiesBean::getCopsDate(self::FORMAT_STRJOUR);
    }

    public function getAltStr(): string
    {
        return 'Météo : '.$this->temperature.'°C - '.$this->weather;
    }

    public function getMaxTemp(): int
    {
        return $this->maxTemp;
    }

    public function getMinTemp(): int
    {
        return $this->minTemp;
    }

    /**
     * @return string
     * @since 1.22.09.05
     * @version 1.22.10.17
     */
    public function parseData(string $str, string $strDate): string
    {
        $strCompteRendu = '';
        // On recherche le pattern dans la ligne
        if (preg_match_all($this->pattern, (string) $str, $matches)) {
            // Si on le trouve, on a une entrée à saisir. Sauf si elle est déjà présente.
            $this->dateMeteo    = $strDate;
            $this->heureMeteo   = $matches[1][0];
            $this->temperature  = $matches[4][0];
            $this->weather      = $matches[2][0];
            $strCompteRendu .= $this->controlerWeather();
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
                $strCompteRendu .= "Cette donnée (".$this->dateMeteo.", ".$this->heureMeteo.") existe déjà.<br>";
            } else {
                // Sinon, on doit l'insérer
                $this->insertCopsMeteo();
            }
        } else {
            $strCompteRendu .= "<strong>/!\</strong> Erreur d'analyse dans la chaîne suivante :<br>$str<br><br>";
        }
        return $strCompteRendu;
    }

  public function controlerForceVent()
  {
    if ($this->forceVent=='N/A' || $this->forceVent=='No wind') {
      $this->forceVent = 0;
    }
  }

    /**
     * @return string
     * @since 1.22.09.05
     * @version 1.22.10.17
     */
    public function controlerWeather(): string
    {
        $requete = "SHOW COLUMNS FROM wp_7_cops_meteo LIKE 'weather';";
        $rows = MySQLClass::wpdbSelect($requete);
        $strEnumType = $rows[0]->Type;
        preg_match('/enum\((.*)\)$/', (string) $strEnumType, $matches);
        $arrVals = explode(',', $matches[1]);
        if (!in_array("'".$this->weather."'", $arrVals)) {
            $requete  = "ALTER TABLE wp_7_cops_meteo MODIFY COLUMN weather ENUM (";
            $requete .= implode(",", $arrVals).", '".$this->weather."');";
            MySQLClass::wpdbQuery($requete);
            $strAlert  = '<strong>/!\</strong> Attention, nouvelle valeur pour le champ weather <em>';
            $strAlert .= $this->weather.'</em>.<br>';
            return $strAlert;
        }
    }

    public function checkIfExists(): bool
    {
        $requete  = "SELECT id FROM wp_7_cops_meteo WHERE ";
        $requete .= "dateMeteo = '".$this->dateMeteo."' AND ";
        $requete .= "heureMeteo = '".$this->heureMeteo."';";
        $rows = MySQLClass::wpdbSelect($requete);
        return !empty($rows);
    }

    /**
     * @return string
     * @since 1.22.09.05
     * @version 1.22.10.17
     */
    public function insertCopsMeteo(): void
    {
        $requete  = "INSERT INTO wp_7_cops_meteo ";
        $requete .= "(dateMeteo, heureMeteo, temperature, weather, weatherId, forceVent, ";
        $requete .= "sensVent, humidite, barometre, visibilite) ";
        $requete .= "VALUES (";
        $requete .= "'".$this->dateMeteo."', ";      // La date
        $requete .= "'".$this->heureMeteo."', "; // L'heure
        $requete .= "'".$this->temperature."', "; // La température
        $requete .= "'".$this->weather."', "; // La météo : certains ne sont peut-être pas définis dans l'enum...
        $requete .= "'".$this->weatherId."', "; // L'id météo
        $requete .= "'".$this->forceVent."', "; // La force du vent
        $requete .= "'".$this->sensVent."', "; // La direction du vent : prefixé par "sa".
        $requete .= "'".$this->humidite."', "; // L'humidité
        $requete .= "'".$this->barometre."', "; // Le baromètre
        $requete .= "'".$this->visibilite."'";   // La visibilité
        $requete .= ");";

        MySQLClass::wpdbQuery($requete);
    }

    public function getLastInsertFormatted(): string
    {
        $requete  = "SELECT dateMeteo FROM wp_7_cops_meteo ORDER BY dateMeteo DESC;";
        $rows = MySQLClass::wpdbSelect($requete);
        return $rows[0]->dateMeteo;
    }

    /**
     * @return string
     * @since 1.22.09.05
     * @version 1.22.10.17
     */
    public function getUrlForNextInsert(): string
    {
        $strLastInsert = $this->getLastInsertFormatted();
        $m = substr((string) $strLastInsert, 4, 2);
        $d = substr((string) $strLastInsert, 6)+1;
        $y = substr((string) $strLastInsert, 0, 4);
        return date('Ymd', mktime(0, 0, 0, $m, $d, $y));
    }
}
