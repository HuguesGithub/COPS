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
   * @since 1.22.09.05
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
    );
    return $this->getRender($this->urlTemplateAdminPageMeteo, $attributes);
  }





}
