<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\utils\LogUtils;

/**
 * Classe LocalDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version v1.23.07.22
 */
class LocalDaoImpl extends GlobalDaoImpl
{
    protected $defaultOrderByAndLimit = " ORDER BY %s %s LIMIT %s";
    protected $select;
    protected $whereFilters;
    protected $delete;
    protected $insert;
    protected $update;
    public $dbTable;

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

    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function getSelectRequest(string $fields, string $tableName, string $fieldId=''): string
    {
        $request = "SELECT $fields FROM $tableName ";
        if ($fieldId!='') {
            $request .= "WHERE $fieldId = '%s'";
        }
        return $request;
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function getInsertRequest(array $fields, string $tableName): string
    {
        $request  = "INSERT INTO $tableName (".implode(', ', $fields);
        $request .= ") VALUES (".implode(', ', array_fill(0, is_countable($fields) ? count($fields) : 0, "'%s'")).");";
        return $request;
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function getUpdateRequest(array $dbFields, string $tableName, string $fieldId): string
    {
        $request  = "UPDATE $tableName SET ";
        $request .= implode("='%s', ", $dbFields)."='%s'";
        $request .= " WHERE $fieldId = '%s';";
        return $request;
    }


    /**
     * @since v1.23.05.26
     * @version v1.23.06.04
     */
    public function selectListDaoImpl($objMixed, string $request, array $attributes): array
    {
        //////////////////////////////
        // Préparation de la requête
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        LogUtils::logRequest($prepRequest);
        $rows = MySQLClass::wpdbSelect($prepRequest);
        //////////////////////////////
        
        //////////////////////////////
        // Construction du résultat
        $objItems = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $objItems[] = $objMixed::convertElement($row);
            }
        }
        return $objItems;
    }

    /**
     * @param mixed [E|S]
     * @param strin $request
     * @since 1.23.03.15
     * @version 1.23.03.15
     */
    public function insertDaoImpl(&$objMixed, array $arrFields, string $request, string $fieldId): void
    {
        // On prépare les paramètres, en excluant le premier (l'id)
        $prepObject = [];
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

    /**
     * @since v1.23.05.26
     * @version v1.23.06.04
     */
    public function updateDaoImpl($objStd, string $request, string $fieldId): void
    {
        $prepObject = [];
        $arrFields  = $objStd->getFields();
        array_shift($arrFields);
        $arrKeys = array_keys($arrFields);
        foreach ($arrKeys as $field) {
            if ($field=='stringClass') {
                continue;
            }
            $prepObject[] = $objStd->getField($field);
        }
        $prepObject[] = $objStd->getField($fieldId);

        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        LogUtils::logRequest($sql);
        MySQLClass::wpdbQuery($sql);
    }




    
    /**
     * @since v1.23.05.26
     * @version v1.23.06.04
     */
    public function insert(string $dbTable, array $fields, array $attributes): int
    {
        //////////////////////////////
        // Préparation de la requête
        $strFields = implode(', ', $fields);
        $strValues = substr(str_repeat("'%s', ", count($fields)), 0, -2);
        $request  = "INSERT INTO ".$dbTable." (".$strFields." ) VALUES (".$strValues.");";
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        LogUtils::logRequest($prepRequest);
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////

        return MySQLClass::getLastInsertId();
    }

// TODO : à supprimer.
  public function getSelect($dbTable)
  {
    ////////////////////////////////////
    // Récupération des champs de l'objet en base
    $arrFields = [];
    $rows = MySQLClass::wpdbSelect("DESCRIBE ".$dbTable.";");
    foreach ($rows as $row) {
      $arrFields[] = $row->Field;
    }
    ////////////////////////////////////
    return "SELECT ".implode(', ', $arrFields)." ";
  }
  
    /**
     * @since v1.23.05.26
     * @version v1.23.05.28
     */
    public function getFields(string $dbTable=null): array
    {
        $arrFields = [];
        $rows = MySQLClass::wpdbSelect("DESCRIBE ".($dbTable ?? $this->dbTable).";");
        foreach ($rows as $row) {
            $arrFields[] = $row->Field;
        }
        return $arrFields;
    }
        
    /**
     * @since
     * @version v1.23.06.04
     */
    public function selectDaoImpl($request, $prepObject)
    {
        //////////////////////////////
        // Préparation de la requête
        $prepRequest  = MySQLClass::wpdbPrepare($request, $prepObject);
        
        //////////////////////////////
        // Exécution de la requête
        LogUtils::logRequest($prepRequest);
        return MySQLClass::wpdbSelect($prepRequest);
    }




}
