<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsIndexServices
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.22.10.21
 */
class CopsIndexServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.10.21
   * @since 1.22.10.21
   */
  public function __construct()
  {
    $this->Dao = new CopsIndexDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  
  /**
   * @param array $attributes [E|S]
   * @since 1.22.10.21
   * @version 1.22.10.21
   */
  public function initFilters(&$attributes=array())
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = array(
        // natureId
        self::SQL_JOKER_SEARCH,
      );
    } else {
      if (!isset($attributes[self::SQL_WHERE_FILTERS]['natureId'])) {
        $attributes[self::SQL_WHERE_FILTERS]['natureId'] = self::SQL_JOKER_SEARCH;
      }
    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = 'nomIdx';
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
   * @return array [CopsIndex]
   * @since 1.22.10.21
   * @version 1.22.10.21
   */
  public function getIndexes($attributes)
  {
      $this->initFilters($attributes);
      return $this->Dao->getIndexes($attributes);
  }
  
  
    /**
     * @param integer
     * @return CopsIndex
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndex($indexId=-1)
    {
        $attributes = array($indexId);
        $row = $this->Dao->getIndex($attributes);
        return new CopsIndex($row[0]);
    }
  
    public function updateIndex($objCopsIndex)
    { $this->Dao->updateIndex($objCopsIndex); }
    
    public function insertIndex(&$objCopsIndex)
    { $this->Dao->insertIndex($objCopsIndex); }
    
    public function getIndexNatures()
    { return $this->Dao->getIndexNatures();    }
    
    public function getCopsIndexNature($natureId)
    {
        $attributes = array($natureId);
        $row = $this->Dao->getIndexNature($attributes);
        return new CopsIndexNature($row[0]);
    }
    
    public function getCopsIndexNatureByName($name)
    {
        $attributes = array($name);
        $items = $this->Dao->getCopsIndexNatures($attributes);
        return new CopsIndexNature($items[0]);
    }
}
