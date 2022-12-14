<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalServices
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.05.20
 */
class LocalServices extends GlobalServices implements ConstantsInterface
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // LOCAL CRUD
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   *    'orderby' : tri sur une colonne
   *    'order'   : sens du tri
   *    'limit'   : nombre d'éléments max
   *    [mixed]   : champs de l'objet
   * @return array
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getObjs($attributes)
  {
    return $this->Dao->getObjs($attributes);
  }

  /**
   * @param mixed $Obj
   * @return mixed
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function insert($Obj)
  { return $this->Dao->insert($Obj); }

  /**
   * @param mixed $Obj
   * @return mixed
   * @version 1.22.05.20
   * @since 1.22.05.20
   */
  public function update($Obj)
  { return $this->Dao->update($Obj); }


  /**
   * @param int $id
   * @return mixed
   * @version 1.22.04.28
   * @since 1.22.04.28
   *
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param mixed $Obj
   * @version 1.22.04.28
   * @since 1.22.04.28
   *
  public function deleteLocal($Obj)
  { $this->delete(__FILE__, __LINE__, $Obj); }
  */


}
