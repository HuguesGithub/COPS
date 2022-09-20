<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsLangueServices
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class CopsLangueServices extends LocalServices
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
    $this->Dao = new CopsLangueDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $attributes [E|S]
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function initFilters(&$attributes)
  {
    if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
      $attributes[self::SQL_WHERE_FILTERS] = array(
        // Libellé
        self::SQL_JOKER_SEARCH,
      );

    }
    if (!isset($attributes[self::SQL_ORDER_BY])) {
      $attributes[self::SQL_ORDER_BY] = self::FIELD_LIBELLE;
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
   * @return array CopsLangue
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getCopsLangues($attributes=array())
  {
    $this->initFilters($attributes);
    return $this->Dao->getCopsLangues($attributes);
  }

  /**
   * @param array $attributes
   *    [mixed]   : champs de l'objet
   *    [orderby] : tri sur une colonne
   *    [order]   : sens du tri
   *    [limit]   : nombre d'éléments max
   * @return string
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getSelectHtml($selectAttributes=array(), $requestAttributes=array())
  {
    $Bean = new UtilitiesBean();
    $CopsLangues = $this->getCopsLangues($requestAttributes);
    $selectHtml .= $Bean->getBalise(self::TAG_OPTION);
    foreach ($CopsLangues as $CopsLangue) {
      $selectHtml .= $Bean->getBalise(self::TAG_OPTION, $CopsLangue->getField(self::FIELD_LIBELLE), array(self::ATTR_VALUE=>$CopsLangue->getField(self::FIELD_ID)));
    }
    return $Bean->getBalise(self::TAG_SELECT, $selectHtml, $selectAttributes);
  }

}
