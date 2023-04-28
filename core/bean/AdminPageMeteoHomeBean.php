<?php
namespace core\bean;


use core\domain\CopsMeteoClass;
use core\domain\MySQLClass;
use core\services\CopsMeteoServices;
use core\utils\DateUtils;

/**
 * AdminPageMeteoHomeBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.04.30
 */
class AdminPageMeteoHomeBean extends AdminPageMeteoBean
{
    private $ajaxUrl = 'https://www.timeanddate.com/scripts/cityajax.php?n=usa/los-angeles&mode=historic&hd=%1$s&month=%2$s&year=%3$s';

    public function getContentOnglet(): string
    {
        // Gestion d'éventuels traitements.
        $strDate = static::fromGet(self::CST_DATE);
        if ($strDate!='') {
            $strCompteRendu = $this->dealWithGetActions();
        } else {
            $strCompteRendu = '';
        }

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Initialisation de la liste des cards qu'on va afficher.
        // Card Meteo
        // Card Soleil
        // Card Lune
        // Card Saisons
        // Card Home ?
        // Quand on veut aller à la ligne, on doit ajouter une div :
        // <div class="w-100"></div>
        $strCards = '';

        $strCards .= $this->getCard();

        $objBean = new AdminPageMeteoMeteoBean();
        $strCards .= $objBean->getCard($strCompteRendu);

        $strCards .= '<div class="w-100"></div>';

        $objBean = new AdminPageMeteoSunBean();
        $strCards .= $objBean->getCard();

        $objBean = new AdminPageMeteoMoonBean();
        $strCards .= $objBean->getCard();

        $strCards .= '<div class="w-100"></div>';
        
        // On va afficher la dernière donnée enregistrée
        // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
        $attributes = [
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_METEO_HOME, $attributes);
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = 'Home';
        $strBody = 'WIP Home Card';
    }



  protected $urlTemplateGrapheMeteo          = 'web/pages/admin/fragments/page-admin-article-graphe-meteo.php';
  protected $urlTemplateGrapheMeteoColumn    = 'web/pages/admin/fragments/page-admin-column-graphe-meteo.php';

  
  public function getGrapheMeteo()
  {
        $strLeftTemps = '';
        $strSectionColumns = '';
        $this->buildGraph($strLeftTemps, $strSectionColumns);
        $attributes = [
            // Le mois visualisé
            'septembre 2022 Weather in Los Angeles — Graph',
            // Les valeurs de températures sur la colonne de gauche
            $strLeftTemps,
            // Les données par quart de journée.
            $strSectionColumns,
            //
            '',
        ];
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
        $rows = MySQLClass::wpdbSelect($strSql);
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
        for ($i=0; $i<$nbDays; ++$i) {
            $n = date('w', mktime(0, 0, 0, $m, $i+1, $y));
    
            for ($j=0; $j<4; ++$j) {
                // on récupère les données du jour entre deux créneaux horaires.
                $strSql =  "SELECT * FROM wp_7_cops_meteo WHERE dateMeteo = '$strDateMeteo";
                $strSql .= str_pad(1+$i, 2, '0', STR_PAD_LEFT)."' AND heureMeteo BETWEEN '";
                $strSql .= str_pad($j*6, 2, '0', STR_PAD_LEFT).":00' AND '";
                $strSql .= str_pad(($j+1)*6, 2, '0', STR_PAD_LEFT).":00' ORDER BY heureMeteo ASC;";
                $rows = MySQLClass::wpdbSelect($strSql);
              
                $maxDayT = -100;
                $minDayT = 100;
                $sumForceVent = 0;
                $nbMesures = 0;
                $arrWeather = [];
                while (!empty($rows)) {
                    $row = array_shift($rows);
                    $temperature = $row->temperature;
                    $maxDayT = max($maxDayT, $temperature);
                    $minDayT = min($minDayT, $temperature);
                    $sumForceVent += $row->forceVent;
                    ++$nbMesures;
                    if (isset($arrWeather[$row->weatherId])) {
                        ++$arrWeather[$row->weatherId];
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
              
                $attributes = [
                    // L'identifiant
                    $i*4+$j,
                    // La date 'jeu 1 sep'
                    DateUtils::arrShortDays[$n].' '.($i+1).' '.DateUtils::arrShortMonths[$m],
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
                ];
                $strSectionColumns .= $this->getRender($this->urlTemplateGrapheMeteoColumn, $attributes);
            }
        }
        }

    /**
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function dealWithGetActions(): string
    {
        $objCopsMeteo = new CopsMeteoClass();
        $strDate = static::fromGet(self::CST_DATE);
        [, $m, $y, , ,] = DateUtils::parseDate($strDate);
        $strDate = str_replace('-', '', $strDate);

        // On construit l'url ciblée
        $url  = sprintf($this->ajaxUrl, $strDate, $m, $y);
        $strCompteRendu = $this->getLink('Date étudiée', $url, '');

        // On en récupère le contenu
        $str = file_get_contents($url);
        // On ne veut que le tbody du tableau
        $strpos = strpos($str, 'tbody');
        // On transforme la ligne unique en un tableau
        $arr = explode("/tr><tr", substr($str, $strpos));

        // On parcourt toutes les lignes du tableau
        foreach ($arr as $str) {
            // Que l'on parse pour récupérer les données souhaitées.
            $strCompteRendu .= $objCopsMeteo->parseData($str, $strDate);
        }
        return $strCompteRendu;
    }


}
