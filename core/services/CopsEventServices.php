<?php
namespace core\services;

use core\daoimpl\CopsEventDaoImpl;

/**
 * Classe CopsEventServices
 * @author Hugues
 * @since 1.22.06.13
 * @version v1.23.05.07
 */
class CopsEventServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.06.13
   * @since 1.22.06.13
   */
  public function __construct()
  {
    $this->Dao = new CopsEventDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $attributes [E|S]
   * @since 1.22.06.13
   * @version 1.22.06.13
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = [
          // Id
          self::SQL_JOKER_SEARCH,
          // dStart
          '2030-01-01',
          // dEnd
          '2049-12-31',
      ];
    } else {
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = self::SQL_JOKER_SEARCH;
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_DSTART])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DSTART] = '2030-01-01';
      }
      if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_DEND])) {
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DEND] = '2049-12-31';
      }
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_DSTART;
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
   * @return array CopsEventDate
   * @since 1.22.06.13
   * @version 1.22.06.13
   */
  public function getCopsEventDates($attributes=[])
  {
    return [];
    $this->initFilters($attributes);
    return $this->Dao->getCopsEventDates($attributes);
  }

  public function getCopsEvents($attributes=[])
  {
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = 'dateDebut';
    }
    if (!isset($attributes[self::SQL_ORDER])) {
      $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
    }
    if (!isset($attributes[self::SQL_LIMIT])) {
      $attributes[self::SQL_LIMIT] = -1;
    }
    return $this->Dao->getCopsEvents($attributes);
  }

  public function getCopsEvent($id)
  { return $this->Dao->getCopsEvent($id); }

  public function saveEvent(&$Obj)
  {
    $id = $this->Dao->saveEvent($Obj->getInsertAttributes());
    $Obj->setField('id', $id);
  }

  public function saveEventDate(&$Obj)
  {
    $id = $this->Dao->saveEventDate($Obj->getInsertAttributes());
    $Obj->setField('id', $id);
  }

  public function getCategorie($id)
  { return $this->Dao->getCopsEventCategorie($id); }

  /**
   * @return CopsEventCategorie[]
   * @since v1.22.11.26
   * @version v1.22.11.26
   */
  public function getCopsEventCategories($attributes=[])
  {
      if (!isset($attributes[self::SQL_ORDER_BY])) {
          $attributes[self::SQL_ORDER_BY] = self::FIELD_CATEG_LIBELLE;
      }
      if (!isset($attributes[self::SQL_ORDER])) {
          $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
      }
      return $this->Dao->getCopsEventCategories($attributes);
  }

    /**
     * @since v1.23.05.05
     * @version v1.23.05.07
     */
    public function getEventDates(array $attributes): array
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsEventDaoImpl();
        }
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $startDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DSTART] ?? self::CST_FIRST_DATE;
        $endDate = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_DEND] ?? self::CST_LAST_DATE;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;

        // Traitement spécifique pour gérer le tri multi-colonnes
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $orderBy = self::FIELD_DSTART;
        } elseif (is_array($attributes[self::SQL_ORDER_BY])) {
            $orderBy = '';
            while (!empty($attributes[self::SQL_ORDER_BY])) {
                $orderBy .= array_shift($attributes[self::SQL_ORDER_BY]).' ';
                $orderBy .= array_shift($attributes[self::SQL_ORDER]).', ';
            }
            $orderBy = substr($orderBy, 0, -2);
            $order = '';
        } else {
            $orderBy = $attributes[self::SQL_ORDER_BY];
        }
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $id,
            $startDate,
            $endDate,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getEventDates($prepAttributes);
    }
}
