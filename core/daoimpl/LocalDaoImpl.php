<?php
namespace core\daoimpl;

use core\domain\MySQLClass;
use core\utils\LogUtils;

/**
 * Classe LocalDaoImpl
 * @author Hugues
 * @since 1.22.04.28
 * @version v1.23.12.02
 */
class LocalDaoImpl extends GlobalDaoImpl
{
    protected $defaultOrderByAndLimit = " ORDER BY %s %s LIMIT %s";

    public $arrLogs = [
        'select' => false,
        'update' => false,
        'insert' => true,
        'delete' => false,
    ];

    public function __construct()
    {
        // TODO : Tous les enfants appellent ce constructeur. En théorie, faudrait les supprimer.
        // Dans un premier temps, on laisse celui-ci, plus simple.
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.08.12
     */
    public function getSelectRequest(string $fields, string $tableName): string
    { return "SELECT $fields FROM $tableName "; }

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
     * @since v1.23.12.02
     */
    public function getDeleteRequest(string $tableName, string $fieldId): string
    { return "DELETE FROM $tableName WHERE $fieldId = '%s';"; }

    /**
     * @since v1.23.05.26
     * @version v1.23.08.05
     */
    public function selectListDaoImpl($objMixed, string $request, array $attributes): array
    {
        //////////////////////////////
        // Préparation de la requête
        $prepRequest = vsprintf($request, $attributes);
        
        //////////////////////////////
        // Exécution de la requête
        if ($this->arrLogs['select']) {
            LogUtils::logRequest($prepRequest);
        }
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
     * @since 1.23.03.15
     * @version v1.23.08.12
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
        if ($this->arrLogs['insert']) {
            LogUtils::logRequest($sql);
        }
        MySQLClass::wpdbQuery($sql);
        $objMixed->setField($fieldId, MySQLClass::getLastInsertId());
    }

    /**
     * @since v1.23.05.26
     * @version v1.23.08.05
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
        if ($this->arrLogs['update']) {
            LogUtils::logRequest($sql);
        }
        MySQLClass::wpdbQuery($sql);
    }

    /**
     * @since v1.23.12.02
     */
    public function deleteDaoImpl($objStd, string $fieldId, string $request): void
    {
        $prepObject = [$objStd->getField($fieldId)];
        $sql = MySQLClass::wpdbPrepare($request, $prepObject);
        if ($this->arrLogs['delete']) {
            LogUtils::logRequest($sql);
        }
        MySQLClass::wpdbQuery($sql);
    }

    /**
     * @since v1.23.12.02
     */
    public function getDistinctFieldValues($objDefault, array $attributes): array
    {
        $request = "SELECT DISTINCT %s FROM %s ORDER BY %s ASC;";
        return $this->selectListDaoImpl($objDefault, $request, $attributes);
    }

}
