<?php
namespace core\domain;

use core\bean\UtilitiesBean;
use core\services\CopsMeteoServices;
use core\daoimpl\CopsMeteoDaoImpl;
use core\utils\DateUtils;
use core\bean\CopsMeteoBean;

/**
 * Classe CopsMeteoClass
 * @author Hugues
 * @version 1.22.09.05
 * @since v1.23.04.30
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

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getBean(): CopsMeteoBean
    { return new CopsMeteoBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////

    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function init(): void
    {
        $strDate = DateUtils::getCopsDate(self::FORMAT_DATE_YMDHIS);
        [$strJour, $strHeure] = explode(' ', $strDate);
        [$Y, $m, $d] = explode('-', $strJour);
        [$H, $i, ] = explode(':', $strHeure);

        $objCopsMeteoServices = new CopsMeteoServices();
        //$this->objCopsSoleil = $objCopsMeteoServices->getSoleil($strJour);
        // TODO : supprimer le -8.
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_METEO] = ($Y-8).$m.$d;
        $attributes[self::SQL_ORDER_BY] = self::FIELD_HEURE_METEO;
        $attributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($attributes);

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
        return DateUtils::getCopsDate(self::FORMAT_DATE_HIS);
    }

    public function getStrDate(): string
    {
        return DateUtils::getCopsDate(self::FORMAT_STRJOUR);
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
            $this->barometre    = $matches[9][0];
            $this->visibilite   = $matches[11][0];

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
        return '';
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
        $objCopsMeteoDaoImpl = new CopsMeteoDaoImpl();
        $objCopsMeteoDaoImpl->traceRequest($requete);

        MySQLClass::wpdbQuery($requete);
    }

    public function getLastInsertFormatted(): string
    {
        $requete  = "SELECT dateMeteo FROM wp_7_cops_meteo ORDER BY dateMeteo DESC;";
        $rows = MySQLClass::wpdbSelect($requete);
        return $rows[0]->dateMeteo;
    }

    /**
     * @since 1.23.04.21
     */
    public function getStrDateMeteo(): string
    {
        $m = substr((string) $this->dateMeteo, 4, 2);
        $d = substr((string) $this->dateMeteo, 6);
        $y = substr((string) $this->dateMeteo, 0, 4);
        return $d.'/'.$m.'/'.$y;
    }

    /**
     * @since 1.23.04.21
     */
    public function getNextDateMeteo(): string
    {
        $m = substr((string) $this->dateMeteo, 4, 2);
        $d = substr((string) $this->dateMeteo, 6)+1;
        $y = substr((string) $this->dateMeteo, 0, 4);
        return date('Ymd', mktime(0, 0, 0, $m, $d, $y));
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getHeatIndex(): int
    {
        // T : une température en °F
        $t = $this->temperature;
        // On doit la convertir de C à F
        $t = round(9*$t/5 + 32);
        // rh : un taux d'humidité.
        $rh = $this->humidite;

        $tFarnheit = -42.379
            + (2.04901523*$t) + (10.14333127*$rh)
            - (0.22475541*$t*$rh) - (0.00683783*$t*$t)
            - (0.05481717*$rh*$rh) + (0.00122874*$t*$t*$rh)
            + (0.00085282*$t*$rh*$rh) - (0.00000199*$t*$t*$rh*$rh);
        
        return round(5*($tFarnheit-32)/9);
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getWindChillIndex(): int
    {
        // T : une température en °C
        $t = $this->temperature;
        // v : une vitesse en km/h
        $v = $this->forceVent;

        return round(13.12 + 0.6215*$t - 11.37*pow($v, 0.16) + 0.3965*$t*pow($v, 0.16));
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function getDateHeure(): string
    {
        // TODO : On est obligé de prendre dateMeteo pour le moment car son format n'est pas bon.
        $strDate = substr($this->dateMeteo, 0, 4).'-'.substr($this->dateMeteo, 4, 2).'-'.substr($this->dateMeteo, -2);
        return DateUtils::getStrDate('d M y H:i:s', $strDate.' '.$this->heureMeteo);
    }
}
