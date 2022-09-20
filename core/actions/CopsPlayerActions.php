<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsPlayerActions
 * @author Hugues
 * @since 1.22.05.19
 * @version 1.22.05.19
 */
class CopsPlayerActions extends LocalActions
{
  /**
   * @since 1.22.05.19
   * @version 1.22.05.19
   */
  public function __construct()
  {
    parent::__construct();
    $this->CopsPlayerServices = new CopsPlayerServices();
  }

  /**
   * @version 1.22.05.19
   * @since 1.22.05.19
   */
  public static function dealWithStatic($params)
  {
    $CopsPlayerActions = new CopsPlayerActions();
    switch ($params[self::AJAX_ACTION]) {
      case 'saveData' :
        $returned = $CopsPlayerActions->updateCopsPlayer($params);
      break;
      default :
        $returned = self::getErrorActionContent($params[self::AJAX_ACTION]);
      break;
    }
    return $returned;
  }

  /**
   * @since 1.22.05.19
   * @version 1.22.05.19
   */
  public function updateCopsPlayer($params)
  {
    $value  = isset($params['value']) ? $params['value'] : '';
    $id     = $params['id'];
    $field  = substr($params['field'], 6);
    $CopsPlayers = $this->CopsPlayerServices->getCopsPlayers(array(self::SQL_WHERE_FILTERS=>array(self::FIELD_ID=>$id)));
    $CopsPlayer = array_shift($CopsPlayers);
    if ($CopsPlayer->getField(self::FIELD_ID)=='') {
      $returned = $this->getToastContentJson('danger', 'Erreur', 'Cet identifiant <strong>'.$id.'</strong> ne correspond à aucun personnage.');
    } else {
      switch ($field) {
        case self::FIELD_BIRTH_DATE :
          //$value = substr($value, 6).'-'.substr($value, 3, 2).'-'.substr($value, 0, 2);
        case self::FIELD_NOM                :
        case self::FIELD_PRENOM             :
        case self::FIELD_SURNOM             :
        case self::FIELD_CARAC_CARRURE      :
        case self::FIELD_CARAC_CHARME       :
        case self::FIELD_CARAC_COORDINATION :
        case self::FIELD_CARAC_EDUCATION    :
        case self::FIELD_CARAC_PERCEPTION   :
        case self::FIELD_CARAC_REFLEXES     :
        case self::FIELD_CARAC_SANG_FROID   :
        case self::FIELD_PV_CUR             :
        case self::FIELD_PAD_CUR            :
        case self::FIELD_PAN_CUR            :
        case self::FIELD_TAILLE             :
        case self::FIELD_POIDS              :
        case self::FIELD_GRADE              :
        case self::FIELD_GRADE_RANG         :
        case self::FIELD_GRADE_ECHELON      :
        case self::FIELD_INTEGRATION_DATE   :
        case self::FIELD_SECTION            :
        case self::FIELD_SECTION_LIEUTENANT :
        case self::FIELD_BACKGROUND         :
        case self::FIELD_PX_CUR             :
          $CopsPlayer->setField($field, $value);
          $this->CopsPlayerServices->update($CopsPlayer);
          $returned = $this->getToastContentJson('success', 'Succès', 'Le champ <em>'.$field.'</em> du personnage a été mis à jour.');
        break;
        default :
          $returned = $this->getToastContentJson('warning', 'Erreur', 'Le champ passé en paramètre n\'a pas une valeur attendue : <strong>'.$field.'</strong>.');
        break;
      }
    }
    return $returned;
  }

}
