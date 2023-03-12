<?php
namespace core\daoimpl;

use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe LocalDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class LocalDaoImpl extends GlobalDaoImpl
{
  /**
   * Requête de sélection en base
   * @var string $selectRequest
   */
  protected $select;
  /**
   * Requête de recherche en base avec Filtres
   * @var string $whereFilters
   */
  protected $whereFilters;
  /**
   * Requête de suppression en base
   * @var string $delete
   */
  protected $delete;
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert;
  /**
   * Requête d'update en base
   * @var string $update
   */
  protected $update;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Récupération des champs de l'objet en base
    $arrFields = $this->getFields();
    ////////////////////////////////////

    ////////////////////////////////////
    // Construction des requêtes de base
    $this->select         = "SELECT ".implode(', ', $arrFields)." FROM ".$this->dbTable." ";
    $this->whereFilters   = "WHERE 1=1 ";
    // Delete
    $this->delete         = "DELETE FROM ".$this->dbTable." WHERE id='%s';";
    // On fait sauter le champ id pour les insert et les updates
    array_shift($arrFields);
    // Insert
    $this->insert         = "INSERT INTO ".$this->dbTable." (".implode(', ', $arrFields).") ";
    $this->insert        .= "VALUES (".substr(str_repeat("'%s', ", count($arrFields)), 0, -2).");";
    // Update
    $this->update         = "UPDATE ".$this->dbTable." SET ";
    foreach ($arrFields as $field) {
      $this->update      .= $field."='%s', ";
    }
    $this->update         = substr($this->update, 0, -2)." ";
    ////////////////////////////////////
  }

  public function getSelect($dbTable)
  {
    ////////////////////////////////////
    // Récupération des champs de l'objet en base
    $arrFields = array();
    $rows = MySQLClass::wpdbSelect("DESCRIBE ".$dbTable.";");
    foreach ($rows as $row) {
      $arrFields[] = $row->Field;
    }
    ////////////////////////////////////
    return "SELECT ".implode(', ', $arrFields)." ";
  }
  
  public function getFields()
  {
      $arrFields = array();
      $rows = MySQLClass::wpdbSelect("DESCRIBE ".$this->dbTable.";");
      foreach ($rows as $row) {
          $arrFields[] = $row->Field;
      }
      return $arrFields;
  }
}
