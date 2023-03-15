<?php
namespace core\services;

use core\daoimpl\CopsIndexDaoImpl;
use core\domain\CopsIndexClass;
use core\domain\CopsIndexNatureClass;
use core\domain\CopsIndexReferenceClass;
use core\domain\CopsIndexTomeClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsIndexServices
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.23.2.15
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

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX
    //////////////////////////////////////////////////

    /**
     * @param array $attributes [E|S]
     * @since 1.22.10.21
     * @version 1.23.02.15
     */
    public function initFilters(&$attributes=array())
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = array(
                // natureIdxId
                self::SQL_JOKER_SEARCH,
            );
        } else {
            if (!isset($attributes[self::SQL_WHERE_FILTERS][self::FIELD_NATURE_IDX_ID])) {
                $attributes[self::SQL_WHERE_FILTERS][self::FIELD_NATURE_IDX_ID] = self::SQL_JOKER_SEARCH;
            }
        }
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $attributes[self::SQL_ORDER_BY] = self::FIELD_NOM_IDX;
        }
        if (!isset($attributes[self::SQL_ORDER])) {
            $attributes[self::SQL_ORDER] = self::SQL_ORDER_ASC;
        }
        if (!isset($attributes[self::SQL_LIMIT])) {
            $attributes[self::SQL_LIMIT] = -1;
        }
    }

    /**
     * @param integer
     * @return CopsIndexClass
     * @since 1.22.10.21
     * @version 1.23.02.15
     */
    public function getIndex($indexId=-1)
    {
        $attributes = array($indexId);
        $row = $this->Dao->getIndex($attributes);
        return (empty($row) ? new CopsIndexClass() : new CopsIndexClass($row[0]));
    }

    /**
     * @param CopsIndex $objCopsIndex [E|S]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function insertIndex(&$objCopsIndex)
    { $this->Dao->insertIndex($objCopsIndex); }

    /**
     * @param array $attributes
     * @return array [CopsIndex]
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getIndexes($attributes)
    { return $this->Dao->getIndexes($attributes); }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_REFERENCE
    //////////////////////////////////////////////////

    /**
     * @param array $idxRefId
     * @return CopsIndexReferenceClass
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexReference($idxRefId)
    {
        $attributes = array($idxRefId);
        $row = $this->Dao->getIndexReference($attributes);
        return (empty($row) ? new CopsIndexReferenceClass() : new CopsIndexReferenceClass($row[0]));
    }

    /**
     * @param array $attributes
     * @return array [CopsIndexReferenceClass]
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getIndexReferences($attributes)
    {
        $this->initFilters($attributes);
        return $this->Dao->getIndexReferences($attributes);
    }

    /**
     * @param $objCopsIndexReference [E|S]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function insertIndexReference(&$objCopsIndexReference)
    { $this->Dao->insertIndexReference($objCopsIndexReference); }

    /**
     * @param $objCopsIndexReference [E|S]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function updateIndexReference(&$objCopsIndexReference)
    { $this->Dao->updateIndexReference($objCopsIndexReference); }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_NATURE
    //////////////////////////////////////////////////

    /**
     * Retourne l'entité correspondant à l'identifiant
     * @param int $natureId
     * @return CopsIndexNature
     * @since 1.23.02.19
     * @version 1.23.02.19
     */
    public function getCopsIndexNature($natureId)
    {
        $attributes = array($natureId);
        $row = $this->Dao->getIndexNature($attributes);
        return (empty($row) ? new CopsIndexNatureClass() : new CopsIndexNatureClass($row[0]));
    }

    /**
     * Retourne l'entité IndexNature correspondant au paramètre
     * @param string $name
     * @return CopsIndexNatureClass
     * @since 1.23.02.15
     * @version 1.23.02.15
     */
    public function getCopsIndexNatureByName($name)
    {
        $attributes = array($name);
        $items = $this->Dao->getCopsIndexNatures($attributes);
        return (empty($items) ? new CopsIndexNatureClass : new CopsIndexNatureClass($items[0]));
    }

    /**
     * @return array[CopsIndexNature]
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexNatures()
    {
        $attributes = array('%');
        return $this->Dao->getCopsIndexNatures($attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_TOME
    //////////////////////////////////////////////////

    /**
     * Retourne l'entité correspondant à l'identifiant
     * @param int $tomeId
     * @return CopsIndexTome
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getCopsIndexTome($tomeId)
    {
        $attributes = array($tomeId);
        $row = $this->Dao->getIndexTome($attributes);
        return (empty($row) ? new CopsIndexTomeClass() : new CopsIndexTomeClass($row[0]));
    }

    /**
     * Retourne l'entité IndexTome correspondant au paramètre
     * @param string $abr
     * @return CopsIndexNatureClass
     * @since 1.23.02.20
     * @version 1.23.02.20
     */
    public function getIndexTomeByAbr($abr)
    {
        $attributes = array($abr);
        $items = $this->Dao->getIndexTomes($attributes);
        return (empty($items) ? new CopsIndexTomeClass : new CopsIndexTomeClass($items[0]));
    }
}
