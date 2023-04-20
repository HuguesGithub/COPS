<?php
namespace core\services;

use core\daoimpl\CopsStageDaoImpl;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsStageServices
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.03
 */
class CopsStageServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.06.02
   * @since 1.22.06.02
   */
  public function __construct()
  {
    $this->Dao = new CopsStageDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $attributes [E|S]
   * @since 1.22.06.02
   * @version 1.22.06.03
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = [
          // Id
          self::SQL_JOKER_SEARCH,
          // stageCategorieId
          self::SQL_JOKER_SEARCH,
          // stageNiveau
          self::SQL_JOKER_SEARCH,
      ];
    } else {
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_CAT_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_CAT_ID] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_LEVEL])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_STAGE_LEVEL] = self::SQL_JOKER_SEARCH;
      }
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_STAGE_LEVEL;
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
   * @return array CopsStage
   * @since 1.22.06.02
   * @version 1.22.06.02
   */
  public function getStages($attributes=[])
  {
    $this->initFilters($attributes);
    return $this->Dao->getStages($attributes);
  }

  /**
   * @param array $attributes
   *    [mixed]   : champs de l'objet
   *    [orderby] : tri sur une colonne
   *    [order]   : sens du tri
   *    [limit]   : nombre d'éléments max
   * @return array CopsStageCategorie
   * @since 1.22.06.02
   * @version 1.22.06.02
   */
  public function getCopsStageCategories($attributes=[])
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
        $attributes[self::SQL_WHERE_FILTERS] = [
            // Id
            self::SQL_JOKER_SEARCH,
        ];
    } elseif (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = self::SQL_JOKER_SEARCH;
    } else {
        // TODO
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_STAGE_CAT_NAME;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }
    if (!isset($attributes[self::SQL_LIMIT])) {
      $attributes[self::SQL_LIMIT] = -1;
    }

    return $this->Dao->getCopsStageCategories($attributes);
  }

  /*
   * @since 1.22.07.06
   * @version 1.22.07.06
   */
  public function getStageSpecs($stageId)
  {
    $attributes = [];
    $attributes[self::SQL_WHERE_FILTERS] = [$stageId];

    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_ID;
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }

    return $this->Dao->getCopsStageSpecs($attributes);
  }

}
