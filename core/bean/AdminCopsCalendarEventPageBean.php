<?php
namespace core\bean;

/**
 * Classe AdminCopsCalendarEventPageBean
 * @author Hugues
 * @since 1.22.06.15
   * @version v1.23.05.28
 */
class AdminCopsCalendarEventPageBean extends AdminCopsCalendarPageBean
{
  public function __construct()
  {
    parent::__construct();
    $this->CopsEventServices = new CopsEventServices();

    if (isset($_POST) && !empty($_POST)) {
      $CopsEvent = new CopsEvent();

      if (trim((string) $_POST['eventLibelle'])!='') {
        $CopsEvent->setField('eventLibelle', $_POST['eventLibelle']);
        $CopsEvent->setField('categorieId', 1);
        $CopsEvent->setDateDebut($_POST['dateDebut']);
        $CopsEvent->setDateFin($_POST['dateFin']);

        if (isset($_POST['allDayEvent'])) {
          $CopsEvent->setField('allDayEvent', 1);
        } else {
          $CopsEvent->setField('allDayEvent', 0);
          $valeur  = str_pad((string) $_POST['heureDebut'], 2, '0', STR_PAD_LEFT);
          $valeur .= ':'.str_pad((string) $_POST['minuteDebut'], 2, '0', STR_PAD_LEFT);
          $CopsEvent->setField('heureDebut', $valeur);
          $valeur  = str_pad((string) $_POST['heureFin'], 2, '0', STR_PAD_LEFT);
          $valeur .= ':'.str_pad((string) $_POST['minuteFin'], 2, '0', STR_PAD_LEFT);
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
   * @version v1.23.05.28
   */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-form-event.php';
        ///////////////////////////////////////////////////////
        // Contenu du formulaire
        $attributes = [
            // Id du Form
            'creerNewEvent',
            // Affichage du form
            'display: none;',
        ];
        $strForm = $this->getRender($urlTemplate, $attributes);
        ///////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-events.php';
        ///////////////////////////////////////////////////////
        // Contenu de la page
        $strContent = '';
        $objsCopsEvent = $this->CopsEventServices->getEvents();
        while (!empty($objsCopsEvent)) {
            $objCopsEvent = array_shift($objsCopsEvent);
          $strContent .= $objCopsEvent->getBean()->getTableRow();
        }
        ///////////////////////////////////////////////////////
    
        $attributes = [
            // Les lignes à afficher
            $strContent,
            // Formulaire
            $strForm,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }


}
