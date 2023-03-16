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
        
    /**
     * @param mixed [E|S]
     * @param strin $request
     * @since 1.23.03.15
     * @version 1.23.03.15
     */
    public function insertDaoImpl(&$objMixed, $request, $fieldId)
    {
        // On prépare les paramètres, en excluant le premier (l'id)
        $prepObject = array();
        $arrFields  = $objMixed->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $objMixed->getField($field);
        }

        // On prépare la requête, l'exécute et met à jour l'id de l'objet créé.
        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        MySQLClass::wpdbQuery($sql);
        $objMixed->setField($fieldId, MySQLClass::getLastInsertId());
    }

    public function updateDaoImpl($objStd, $request, $fieldId)
    {
        $prepObject = array();
        $arrFields  = $objStd->getFields();
        array_shift($arrFields);
        foreach ($arrFields as $field) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $objStd->getField($field);
        }
        $prepObject[] = $objStd->getField($fieldId);

        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        MySQLClass::wpdbQuery($sql);
    }

    public function selectDaoImpl($request, $prepObject)
    {
        //////////////////////////////
        // Préparation de la requête
        $prepRequest  = MySQLClass::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        return MySQLClass::wpdbSelect($prepRequest);
    }

    public function selectListDaoImpl($objMixed, $request, $attributes)
    {
        //////////////////////////////
        // Préparation de la requête
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = $objMixed::convertElement($row);
            }
        }
        return $objItems;
    }


    public function getSelectRequest($fields, $tableName, $fieldId='')
    {
        $request = "SELECT $fields FROM $tableName ";
        if ($fieldId!='') {
            $request .= "WHERE $fieldId = '%s'";
        }
        return $request;
    }

    public function getInsertRequest($fields, $tableName)
    {
        $request  = "INSERT INTO $tableName (".implode(', ', $fields);
        $request .= ") VALUES (".implode(', ', array_fill(0, count($fields), "'%s'")).");";
        return $request;
    }

    public function getUpdateRequest($dbFields, $tableName, $fieldId)
    {
        $request  = "UPDATE $tableName SET ";
        $request .= implode("='%s', ", $dbFields)."='%s'";
        $request .= " WHERE $fieldId = '%s';";
        return $request;
    }
}
