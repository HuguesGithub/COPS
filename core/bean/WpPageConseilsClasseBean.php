<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageConseilsClasseBean
 * @author Hugues
 * @version 1.21.07.15
 * @since 1.21.06.29
 */
class WpPageConseilsClasseBean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-conseils-classes.php';
  /**
   * Class Constructor
   * @param WpPage $WpPage
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->Services = new CompteRenduServices();
    $this->ParentDelegueServices = new ParentDelegueServices();
  }

  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function getContentPage()
  {
    $arr_Fields = array(self::FIELD_TRIMESTRE, "STR_TO_DATE(dateConseil, '%d/%m/%Y')", 'heureConseil', 'salleConseil');
    $arr_Orders = array(self::ORDER_DESC, self::ORDER_ASC, self::ORDER_ASC, self::ORDER_DESC);
    $ComptesRendus = $this->Services->getCompteRendusWithFilters(array(), $arr_Fields, $arr_Orders);
    $trimestre = 0;
    $dateConseil = '';
    $heureConseil = '';
    $nbConseils = 0;
    $strTableContent = '<tr class="table-dark"><th colspan="3">Conseils de Classes 2022-2023</th></tr>';
    while (!empty($ComptesRendus)) {
      $CompteRendu = array_shift($ComptesRendus);
      switch ($CompteRendu->getStatus()) {
        case self::STATUS_PUBLISHED :
          $statusColor = 'success';
        break;
        case self::STATUS_PENDING :
          $statusColor = 'warning';
        break;
        default :
          $statusColor = 'danger';
        break;
      }
      $ParentsDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters(array(self::FIELD_DIVISION_ID=>$CompteRendu->getDivision()->getId()));
      $nbParentDelegues = 0;
      while (!empty($ParentsDelegues)) {
        $ParentDelegue = array_shift($ParentsDelegues);
        $Adulte = $ParentDelegue->getAdulte();
        if ($Adulte->isAdherent()) {
          $nbParentDelegues++;
        }
      }
      if ($trimestre!=$CompteRendu->getTrimestre()) {
        $trimestre = $CompteRendu->getTrimestre();
        $dateConseil = '';
        $heureConseil = '';
        $nbConseils = 0;
        $strTableContent .= '<tr class="table-primary"><th colspan="3">Trimestre '.$trimestre.'</th></tr>';
      }
      if ($dateConseil!=$CompteRendu->getDateConseil()) {
        if ($nbConseils==1) {
          $strTableContent .= '<td>-</td></tr>';
        } elseif ($nbConseils==2) {
          $strTableContent .= '</tr>';
        }
        $dateConseil = $CompteRendu->getDateConseil();
        $heureConseil = '';
        $nbConseils = 0;
        $strTableContent .= '<tr class="table-info"><th>'.$dateConseil.'</th><th>Mme Avril<br>Salle des Actes</th><th>Mme Assier<br>103</th></tr>';
      }
      if ($heureConseil!=$CompteRendu->getHeureConseil()) {
        if ($nbConseils==1) {
          $strTableContent .= '<td>-</td></tr>';
          $nbConseils = 0;
        } elseif ($nbConseils==2) {
          $strTableContent .= '</tr>';
          $nbConseils = 0;
        }
        $heureConseil = $CompteRendu->getHeureConseil();
        $strTableContent .= '<tr><td>'.$heureConseil.'</td>';
        if ($nbConseils==0 && $CompteRendu->getSalleConseil()=='103') {
          $strTableContent .= '<td>-</td>';
          $nbConseils = 2;
        } else {
          $nbConseils++;
        }
        $strTableContent .= '<td>'.$CompteRendu->getDivision()->getLabelDivision().' - <span class="badge rounded-pill bg-'.($nbParentDelegues==0 ? 'danger' : 'success').'">';
        $strTableContent .= $nbParentDelegues.'</span> - <span class="oi oi-file text-'.$statusColor.'"></span></td>';
      } else {
        $strTableContent .= '<td>'.$CompteRendu->getDivision()->getLabelDivision().' - <span class="badge rounded-pill bg-'.($nbParentDelegues==0 ? 'danger' : 'success').'">';
        $strTableContent .= $nbParentDelegues.'</span> - <span class="oi oi-file text-'.$statusColor.'"></span></td>';
        $nbConseils++;
      }
    }

    $args = array(
      // - 1
      $strTableContent,
    );
    return $this->getRender($this->urlTemplate, $args);
  }

}
