<?php
namespace core\bean;

use core\domain\CopsMeteoClass;
use core\services\CopsMeteoServices;
use core\domain\MySQLClass;

/**
 * AdminPageMeteoBean
 * @author Hugues
 * @since 1.23.04.20
 * @version 1.23.04.30
 */
class AdminPageMeteoBean extends AdminPageBean
{
    private $ajaxUrl = 'https://www.timeanddate.com/scripts/cityajax.php?n=usa/los-angeles&mode=historic&hd=%1$s&month=%2$s&year=%3$s';

    /**
     * @since 1.23.04.20
     */
    public static function getStaticContentPage(): string
    {
        ///////////////////////////////////////////:
        // Initialisation des valeurs par défaut
        $objBean = new AdminPageMeteoBean();
        return $objBean->getContentPage();
    }

    /**
     * @since 1.23.04.20
     */
    public function getContentPage(): string
    {
        $objCopsMeteo = new CopsMeteoClass();
        $objCopsMeteoServices = new CopsMeteoServices();

        $strCompteRendu = '';

        // On récupère le paramètre relatif à la date.
        $strDate = $this->urlParams['date'];
        // S'il n'est pas nul, on va faire le traitement.
        if (!empty($strDate)) {
            $intYear  = substr((string) $strDate, 0, 4);
            $intMonth = substr((string) $strDate, 4, 2)*1;
            // On construit l'url ciblée
            $url  = sprintf($this->ajaxUrl, $strDate, $intMonth, $intYear);
            $strCompteRendu .= '<a href="'.$url.'">Date étudiée</a><br>';
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
            if ($strCompteRendu=='') {
                $strCompteRendu = 'Le script s\'est bien déroulé.';
            }
        }

        // Récupération de la dernière entrée de la table
        $sqlAttributes = [];
        $sqlAttributes[self::SQL_ORDER] = self::SQL_ORDER_DESC;
        $sqlAttributes[self::SQL_LIMIT] = 1;
        $objsCopsMeteoLastInsert = $objCopsMeteoServices->getMeteos($sqlAttributes);
        $objCopsMeteoLastInsert = array_shift($objsCopsMeteoLastInsert);

        // Construction du Header du tableau
        // Heure
        // Température
        // Météo + Icone
        // Force et sens du vent
        // Humidité
        // Baromètre
        // Visibilité
        $strHeader  = '<tr><th>&nbsp;</th><th colspan="3">Conditions</th><th colspan="3">Confort</th>';
        $strHeader .= '<th colspan="2">&nbsp;</th></tr>';
        $strHeader .= '<tr><th>Heure</th><th>&nbsp;</th><th>Temp</th><th>Météo</th><th>Vent</th>';
        $strHeader .= '<th>&nbsp;</th><th>Humidité</th><th>Baromètre</th><th>Visibilité</th></tr>';

        // Récupération des données de la journée
        $strDate = $objCopsMeteoLastInsert->getField(self::FIELD_DATE_METEO);
        $sqlAllAttributes = [];
        $sqlAllAttributes[self::SQL_WHERE_FILTERS][self::FIELD_DATE_METEO] = $strDate;
        $sqlAllAttributes[self::SQL_ORDER_BY] = self::FIELD_HEURE_METEO;
        $objsCopsMeteo = $objCopsMeteoServices->getMeteos($sqlAllAttributes);

        // Gestion des données relatives au soleil
        $strJour = '2028-'.substr($strDate, 4, 2).'-'.substr($strDate, 6, 2);
        $objCopsSoleil = $objCopsMeteoServices->getSoleil($strJour);

        $strBody = '';
        while (!empty($objsCopsMeteo)) {
            $objCopsMeteo = array_shift($objsCopsMeteo);
            $strBody .= '<tr>';
            $strBody .= '<th>'.$objCopsMeteo->getField(self::FIELD_HEURE_METEO).'</th>';
            $strBody .= '<td><div class="wicon" data-icon="'.$objCopsMeteo->getField(self::FIELD_WEATHER_ID);
            $strBody .= '"></div></td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_TEMPERATURE).'°C</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_WEATHER).'</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_FORCE_VENT).' km/h</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_SENS_VENT).'</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_HUMIDITE).'%</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_BAROMETRE).' mbar</td>';
            $strBody .= '<td>'.$objCopsMeteo->getField(self::FIELD_VISIBILITE).' km</td>';
            $strBody .= '</tr>';
        }
    
        // On va afficher la dernière donnée enregistrée
        // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
        $attributes = [
            // La dernière saisie - 1
            $objCopsMeteoLastInsert->getStrDateMeteo(),
            // Le bouton pour lancer la saisie suivante - 2
            $objCopsMeteoLastInsert->getNextDateMeteo(),
            // Le compte-rendu du traitement s'il y a eu - 3
            $strCompteRendu,
            // Le graphe Météo du mois
            $this->getGrapheMeteo(),
            // La date du jour pour rejouer
            $objCopsMeteoLastInsert->getField(self::FIELD_DATE_METEO),
            // Le Header du tableau
            $strHeader,
            // Le contenu du tableau
            $strBody,
            // Les heures de lever et coucher du soleil
            $objCopsSoleil->getField(self::FIELD_HEURE_LEVER),
            $objCopsSoleil->getField(self::FIELD_HEURE_COUCHER),
            $objCopsSoleil->getDureeJournee(),
        ];
        return $this->getRender(self::WEB_PA_METEO, $attributes);
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
                ];
                $strSectionColumns .= $this->getRender($this->urlTemplateGrapheMeteoColumn, $attributes);
            }
        }
        }




}
