<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsCalendarEventPageBean
 * @author Hugues
 * @since 1.22.06.15
 * @version 1.22.06.15
 */
class AdminCopsCalendarEventPageBean extends AdminCopsCalendarPageBean
{
  public function __construct()
  {
    parent::__construct();
    $this->CopsEventServices = new CopsEventServices();

    if (isset($_POST) && !empty($_POST)) {
      $CopsEvent = new CopsEvent();

      if (trim($_POST['eventLibelle'])!='') {
        $CopsEvent->setField('eventLibelle', $_POST['eventLibelle']);
        $CopsEvent->setField('categorieId', 1);
        $CopsEvent->setDateDebut($_POST['dateDebut']);
        $CopsEvent->setDateFin($_POST['dateFin']);

        if (isset($_POST['allDayEvent'])) {
          $CopsEvent->setField('allDayEvent', 1);
        } else {
          $CopsEvent->setField('allDayEvent', 0);
          $valeur  = str_pad($_POST['heureDebut'], 2, '0', STR_PAD_LEFT);
          $valeur .= ':'.str_pad($_POST['minuteDebut'], 2, '0', STR_PAD_LEFT);
          $CopsEvent->setField('heureDebut', $valeur);
          $valeur  = str_pad($_POST['heureFin'], 2, '0', STR_PAD_LEFT);
          $valeur .= ':'.str_pad($_POST['minuteFin'], 2, '0', STR_PAD_LEFT);
          $CopsEvent->setField('heureFin', $valeur);
        }

        if ($CopsEvent->isValidInterval()) {
          if (isset($_POST['repeatStatus'])) {
            $CopsEvent->setField('repeatStatus', 1);
            // repeatType
            $CopsEvent->setField('repeatType', $_POST['repeatType']);
            // repeatInterval
            $CopsEvent->setField('repeatInterval', $_POST['repeatInterval']);
            // repeatEnd
            $CopsEvent->setField('repeatEnd', $_POST['repeatEnd']);
            // repeatEndValue
            if ($_POST['repeatEnd']=='endDate') {
              $CopsEvent->setRepeatEndValue($_POST['endDateValue']);
            } elseif ($_POST['repeatEnd']=='endRepeat') {
              $CopsEvent->setField('repeatEndValue', $_POST['endRepetitionValue']);
            }
          } else {
            $CopsEvent->setField('repeatStatus', 0);
            $CopsEvent->setField('repeatType', '');
            $CopsEvent->setField('repeatInterval', '');
            $CopsEvent->setField('repeatEnd', '');
            $CopsEvent->setField('repeatEndValue', '');
          }
          $CopsEvent->saveEvent();
        } else {
          echo "Intervalle non valide";
        }
      }
    }
  }

  /**
   * @since 1.22.06.09
   * @version 1.22.09.21
   */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-form-event.php';
        ///////////////////////////////////////////////////////
        // Contenu du formulaire
        $attributes = array(
            // Id du Form
            'creerNewEvent',
            // Affichage du form
            'display: none;',
        );
        $strForm = $this->getRender($urlTemplate, $attributes);
        ///////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-events.php';
        ///////////////////////////////////////////////////////
        // Contenu de la page
        $strContent = '';
        $objsCopsEvent = $this->CopsEventServices->getCopsEvents();
        while (!empty($objsCopsEvent)) {
            $CopsEvent = array_shift($objsCopsEvent);
          $strContent .= $CopsEvent->getBean()->getTableRow();
        }
        ///////////////////////////////////////////////////////
    
        $attributes = array(
          // Les lignes à afficher
          $strContent,
          // Formulaire
          $strForm,
        );
        return $this->getRender($urlTemplate, $attributes);
    }


}
