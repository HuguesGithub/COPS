<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEventBean
 * @author Hugues
 * @since 1.22.06.16
 * @version 1.22.06.16
 */
class CopsEventBean extends LocalBean
{
  public function __construct($Obj=null)
  {
    $this->CopsEvent = ($Obj==null ? new CopsEvent() : $Obj);
  }

  public function getTableRow()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-event-row.php';
    $attributes = array(
      '/admin/?onglet=calendar&subOnglet=calendar-event&id='.$this->CopsEvent->getField('id'),
      $this->CopsEvent->getField('eventLibelle'),
      $this->CopsEvent->getCategorie()->getField('categorieLibelle'),
      $this->CopsEvent->getField('dateDebut'),
      $this->CopsEvent->getField('dateFin'),
      ($this->CopsEvent->getField('repeatStatus')==1 ? 'Oui' : 'Non'),
    );
    return $this->getRender($urlTemplate, $attributes);
  }
}
