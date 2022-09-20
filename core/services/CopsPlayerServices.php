<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsPlayerServices
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class CopsPlayerServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.04.28
   * @since 1.22.04.28
   */
  public function __construct()
  {
    $this->Dao = new CopsPlayerDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $attributes [E|S]
   * @since 1.22.04.28
   * @version 1.22.05.20
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = array(
        // Id
        self::SQL_JOKER_SEARCH,
        // Matricule
        self::SQL_JOKER_SEARCH,
        // Password
        self::SQL_JOKER_SEARCH,
        // Grade
        self::SQL_JOKER_SEARCH,
      );
    } else {
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_MATRICULE])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_MATRICULE] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_PASSWORD])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_PASSWORD] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = self::SQL_JOKER_SEARCH;
      }
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_MATRICULE;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }
    if (!isset($attributes[self::SQL_LIMIT])) {
      $attributes[self::SQL_LIMIT] = -1;
    }
  }

  /**
   * @param array $attributes
   *    [mixed]   : champs de l'objet
   *    [orderby] : tri sur une colonne
   *    [order]   : sens du tri
   *    [limit]   : nombre d'éléments max
   * @return array CopsPlayer
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getCopsPlayers($attributes=array())
  {
    $this->initFilters($attributes);
    return $this->Dao->getCopsPlayers($attributes);
  }

}
