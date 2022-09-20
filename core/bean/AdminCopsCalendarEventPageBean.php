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
          $CopsEvent->setField('heureDebut', str_pad($_POST['heureDebut'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['minuteDebut'], 2, '0', STR_PAD_LEFT));
          $CopsEvent->setField('heureFin', str_pad($_POST['heureFin'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['minuteFin'], 2, '0', STR_PAD_LEFT));
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
   * @version 1.22.06.09
   */
  public function getOngletContent()
  {
    // TODO :
    // On récupère la liste des événements
    // Et on affiche chacun d'entre eux.
    /*
<tr>
<td><a href="pages/examples/invoice.html">OR9842</a></td>
<td>Call of Duty IV</td>
<td><span class="badge badge-success">Shipped</span></td>
<td>
<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR1848</a></td>
<td>Samsung Smart TV</td>
<td><span class="badge badge-warning">Pending</span></td>
<td>
<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR7429</a></td>
<td>iPhone 6 Plus</td>
<td><span class="badge badge-danger">Delivered</span></td>
<td>
<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR7429</a></td>
<td>Samsung Smart TV</td>
<td><span class="badge badge-info">Processing</span></td>
<td>
<div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR1848</a></td>
<td>Samsung Smart TV</td>
<td><span class="badge badge-warning">Pending</span></td>
<td>
<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR7429</a></td>
<td>iPhone 6 Plus</td>
<td><span class="badge badge-danger">Delivered</span></td>
<td>
<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
</td>
</tr>
<tr>
<td><a href="pages/examples/invoice.html">OR9842</a></td>
<td>Call of Duty IV</td>
<td><span class="badge badge-success">Shipped</span></td>
<td>
<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
</td>
</tr>
     */

    $strContent = '';
    $CopsEvents = $this->CopsEventServices->getCopsEvents();
    while (!empty($CopsEvents)) {
      $CopsEvent = array_shift($CopsEvents);
      $strContent .= $CopsEvent->getBean()->getTableRow();
    }

    $urlTemplate = 'web/pages/public/fragments/public-fragments-form-event.php';
    $attributes = array(
      // Id du Form
      'creerNewEvent',
      // Affichage du form
      'display: none;',
    );
    $strForm = $this->getRender($urlTemplate, $attributes);


    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-events.php';
    $attributes = array(
      // Les lignes à afficher
      $strContent,
      // Formulaire
      $strForm,
    );
    return $this->getRender($urlTemplate, $attributes);
  }


}
