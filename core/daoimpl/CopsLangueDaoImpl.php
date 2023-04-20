<?php
namespace core\daoimpl;

use core\domain\CopsLangueClass;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsLangueDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class CopsLangueDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    $this->ObjClass = new CopsLangueClass();
    $this->dbTable  = "wp_7_cops_langue";
    ////////////////////////////////////

    parent::__construct();

    ////////////////////////////////////
    // Personnalisation de la requête avec les filtres
    $this->whereFilters .= "AND libelle LIKE '%s' ";
    ////////////////////////////////////
  }

  public function getCopsLangues($attributes)
  {
    //////////////////////////////
    // Construction de la requête
    $request  = $this->select.vsprintf($this->whereFilters, $attributes[self::SQL_WHERE_FILTERS]);
    // On trie la liste
    // TODO : si $attributes[self::SQL_ORDER_BY] est un array
    // vérifier que $attributes[self::SQL_ORDER] est bien un array aussi dont la taille correspond
    // et construit l'order by en adéquation
    $request .= "ORDER BY ".$attributes[self::SQL_ORDER_BY]." ".$attributes[self::SQL_ORDER]." ";
    // On limite si nécessaire
    if ($attributes[self::SQL_LIMIT]!=-1) {
      $request .= "LIMIT ".$attributes[self::SQL_LIMIT]." ";
    }
    $request .= ";";
    //////////////////////////////

    //////////////////////////////
    // Exécution de la requête
    $rows = MySQLClass::wpdbSelect($request);
    //////////////////////////////

    //////////////////////////////
    // Construction du résultat
    $objsItem = [];
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $objsItem[] = CopsLangueClass::convertElement($row);
      }
    }
    return $objsItem;
    //////////////////////////////
  }

}
