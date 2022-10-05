<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdminPageMeteoBean
 * @author Hugues
 * @version 1.22.09.05
 * @since 1.22.09.05
 */
class AdminPageMeteoBean extends AdminPageBean
{
  protected $urlTemplateAdminPageMeteo       = 'web/pages/admin/page-admin-meteo.php';
  protected $urlTemplateGrapheMeteo          = 'web/pages/admin/fragments/page-admin-article-graphe-meteo.php';
  protected $urlTemplateGrapheMeteoColumn    = 'web/pages/admin/fragments/page-admin-column-graphe-meteo.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
  }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.22.09.05
   * @since 1.22.10.05
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageMeteoBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function getContentPage()
  {
    $CopsMeteo = new CopsMeteo();
    $str_compteRendu = '';

    // On récupère le paramètre relatif à la date.
    $str_date = $this->urlParams['date'];
    // S'il n'est pas nul, on va faire le traitement.
    if (!empty($str_date)) {
      $int_year  = substr($str_date, 0, 4);
      $int_month = substr($str_date, 4, 2)*1;
      // On construit l'url ciblée
      $url = 'https://www.timeanddate.com/scripts/cityajax.php?n=usa/los-angeles&mode=historic&hd='.$str_date.'&month='.$int_month.'&year='.$int_year;
      $str_compteRendu .= '<a href="'.$url.'">Date étudiée</a><br>';
      // On en récupère le contenu
      $str = file_get_contents($url);
      // On ne veut que le tbody du tableau
      $strpos = strpos($str, 'tbody');
      // On transforme la ligne unique en un tableau
      $arr = explode("/tr><tr", substr($str, $strpos));
      // On parcourt toutes les lignes du tableau
      foreach ($arr as $str) {
        // Que l'on parse pour récupérer les données souhaitées.
        $str_compteRendu .= $CopsMeteo->parseData($str, $str_date);
      }
    }
    
    // On va afficher la dernière donnée enregistrée
    // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
    $attributes = array(
      // La dernière saisie - 1
      $CopsMeteo->getLastInsertFormatted(),
      // Le bouton pour lancer la saisie suivante - 2
      $CopsMeteo->getUrlForNextInsert(),
      // Le compte-rendu du traitement s'il y a eu - 3
      ($str_compteRendu=='' ? 'Le script s\'est bien déroulé.' : $str_compteRendu),
      // Le graphe Météo du mois
      $this->getGrapheMeteo(),
    );
    return $this->getRender($this->urlTemplateAdminPageMeteo, $attributes);
  }
  
  public function getGrapheMeteo()
  {
        $strLeftTemps = '';
        $strSectionColumns = '';
        $this->buildGraph($strLeftTemps, $strSectionColumns);
        $attributes = array(
            // Le mois visualisé
            'septembre 2022 Weather in Los Angeles — Graph',
            // Les valeurs de températures sur la colonne de gauche
            $strLeftTemps,
            // Les données par quart de journée.
            $strSectionColumns,
            //
            '',
        );
        return $this->getRender($this->urlTemplateGrapheMeteo, $attributes);
    
  }
  
  public function buildGraph(&$strLeftTemps, &$strSectionColumns)
  {
        $strDateMeteo = '202209';
        
        $nbDays = 30;
        $gridWidth = 42 * 4 * $nbDays;
        
        // On récupère les données du mois
        $strSql  = "SELECT MIN(temperature) as minT, MAX(temperature) as maxT FROM wp_7_cops_meteo ";
        $strSql .= "WHERE dateMeteo LIKE '$strDateMeteo%';";
        $rows = MySQL::wpdbSelect($strSql);
        $maxT = $rows[0]->maxT;
        $minT = $rows[0]->minT;

        $pasInter = 2;
        $nbInter  = ceil((1 + $maxT - $minT)/$pasInter)+1;
        if ($nbInter>11) {
            $pasInter = 3;
            $nbInter  = ceil((1 + $maxT - $minT)/$pasInter)+1;
        }
        
        $height = 22;
        $start = $maxT-$pasInter*($nbInter-1);
        
        for ($i=$start; $i<=$maxT; $i+=$pasInter) {
            $top = round((270-($i-$start)*$height/$pasInter), 3);
            $strLeftTemps .= '<div class="gridtext" style="top: '.$top.'px;">'.$i.'</div>';
            $strSectionColumns .= '<div class="gridline" style="width: '.$gridWidth.'px; top: '.$top.'px;"></div>';
        }
        
        $y = substr($strDateMeteo, 0, 4);
        $m = substr($strDateMeteo, 4, 2)*1;
        for ($i=0; $i<$nbDays; $i++) {
            $n = date('w', mktime(0, 0, 0, $m, $i+1, $y));
    
            for ($j=0; $j<4; $j++) {
                // on récupère les données du jour entre deux créneaux horaires.
                $strSql  = "SELECT * FROM wp_7_cops_meteo ";
                $strSql .= "WHERE dateMeteo = '$strDateMeteo".str_pad(1+$i, 2, '0', STR_PAD_LEFT);
                $strSql .= "' AND heureMeteo BETWEEN '".str_pad($j*6, 2, '0', STR_PAD_LEFT);
                $strSql .= ":00' AND '".str_pad(($j+1)*6, 2, '0', STR_PAD_LEFT).":00' ORDER BY heureMeteo ASC;";
                $rows = MySQL::wpdbSelect($strSql);
              
                $maxDayT = -100;
                $minDayT = 100;
                $sumForceVent = 0;
                $nbMesures = 0;
                $arrWeather = array();
                while (!empty($rows)) {
                    $row = array_shift($rows);
                    $temperature = $row->temperature;
                    $maxDayT = max($maxDayT, $temperature);
                    $minDayT = min($minDayT, $temperature);
                    $sumForceVent += $row->forceVent;
                    $nbMesures++;
                    if (isset($arrWeather[$row->weatherId])) {
                        $arrWeather[$row->weatherId]++;
                    } else {
                        $arrWeather[$row->weatherId] = 1;
                    }
                }
                arsort($arrWeather);
                $weatherId = array_key_first($arrWeather);
                if ($weatherId=='') {
                    $weatherId = 36;
                }
                
                $decalMin = 2;
                $decalMax = 4.5;
                
                $posMin = 270-($decalMin+$minDayT-$minT)*$height/3;
                $posMax = 270-($decalMax+$maxDayT-$minT)*$height/3;
                $posLog = $posMax-50;
                if ($weatherId==36) {
                    $posLog = 80;
                    $posMax = 120;
                }
              
                $attributes = array(
                    // L'identifiant
                    $i*4+$j,
                    // La date 'jeu 1 sep'
                    $this->arrShortDays[$n].' '.($i+1).' '.$this->arrShortMonths[$m],
                    // Position Texte pour t° minimale
                    $posMin,
                    // Température minimale
                    $minDayT,
                    // L'heure du début du créneau
                    str_pad($j*6, 2, '0', STR_PAD_LEFT),
                    // Le type du logo
                    $weatherId,
                    // La position du Logo
                    $posLog,
                    // Position texte pour t° maximale
                    $posMax,
                    // Température maximale
                    $maxDayT,
                    // Rotation pour sens du vent
                    '',
                    // Force du vent
                    ($nbMesures==0 ? 0 : round($sumForceVent/$nbMesures, 0)),
                );
                $strSectionColumns .= $this->getRender($this->urlTemplateGrapheMeteoColumn, $attributes);
            }
        }
    }




}
