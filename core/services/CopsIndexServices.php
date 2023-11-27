<?php
namespace core\services;

use core\daoimpl\CopsIndexDaoImpl;
use core\domain\CopsIndexClass;
use core\domain\CopsIndexNatureClass;
use core\domain\CopsIndexReferenceClass;
use core\domain\CopsIndexTomeClass;

/**
 * Classe CopsIndexServices
 * @author Hugues
 * @since 1.22.10.21
 * @version v1.23.12.02
 */
class CopsIndexServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @version v1.23.07.29
     * @since 1.22.10.21
     */
    public function __construct()
    {
        $this->initDao();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    private function initDao(): void
    {
        if ($this->objDao==null) {
            $this->objDao = new CopsIndexDaoImpl();
        }
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX
    //////////////////////////////////////////////////

    /**
     * @param array $attributes [E|S]
     * @since 1.22.10.21
     * @version v1.23.12.02
     */
    public function initFilters(&$attributes=[])
    {
        if (!isset($attributes[self::FIELD_NATURE_IDX_ID])) {
            $attributes[self::FIELD_NATURE_IDX_ID] = self::SQL_JOKER_SEARCH;
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
     * @version v1.23.07.29
     */
    public function getIndex($indexId=-1)
    {
        $attributes = [$indexId];
        $row = $this->objDao->getIndex($attributes);
        return empty($row) ? new CopsIndexClass() : new CopsIndexClass($row[0]);
    }

    /**
     * @param CopsIndex $objCopsIndex [E|S]
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function insertIndex(&$objCopsIndex)
    { $this->objDao->insertIndex($objCopsIndex); }

    /**
     * @param array $attributes
     * @return array [CopsIndex]
     * @since 1.22.10.21
     * @version v1.23.07.29
     */
    public function getIndexes($attributes)
    {
        $builtAttributes = [];
        if (!isset($builtAttributes[self::SQL_WHERE_FILTERS])) {
            $builtAttributes[self::SQL_WHERE_FILTERS] = $attributes;
        }
        return $this->objDao->getIndexes($builtAttributes);
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_REFERENCE
    //////////////////////////////////////////////////

    /**
     * @param array $idxRefId
     * @return CopsIndexReferenceClass
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function getIndexReference($idxRefId)
    {
        $attributes = [$idxRefId];
        $row = $this->objDao->getIndexReference($attributes);
        return empty($row) ? new CopsIndexReferenceClass() : new CopsIndexReferenceClass($row[0]);
    }

    /**
     * @param array $attributes
     * @return array [CopsIndexReferenceClass]
     * @since 1.23.02.15
     * @version v1.23.07.29
     */
    public function getIndexReferences($attributes)
    {
        $this->initFilters($attributes);
        return $this->objDao->getIndexReferences($attributes);
    }

    /**
     * @param $objCopsIndexReference [E|S]
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function insertIndexReference(&$objCopsIndexReference)
    { $this->objDao->insertIndexReference($objCopsIndexReference); }

    /**
     * @param $objCopsIndexReference [E|S]
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function updateIndexReference(&$objCopsIndexReference)
    { $this->objDao->updateIndexReference($objCopsIndexReference); }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_NATURE
    //////////////////////////////////////////////////

    /**
     * Retourne l'entité correspondant à l'identifiant
     * @param int $natureId
     * @return CopsIndexNature
     * @since 1.23.02.19
     * @version v1.23.07.29
     */
    public function getCopsIndexNature($natureId)
    {
        $attributes = [$natureId];
        $row = $this->objDao->getIndexNature($attributes);
        return empty($row) ? new CopsIndexNatureClass() : new CopsIndexNatureClass($row[0]);
    }

    /**
     * Retourne l'entité IndexNature correspondant au paramètre
     * @param string $name
     * @return CopsIndexNatureClass
     * @since 1.23.02.15
     * @version v1.23.07.29
     */
    public function getCopsIndexNatureByName($name)
    {
        $attributes = [];
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = [$name];
        }
        $items = $this->objDao->getIndexNatures($attributes);
        return empty($items) ? new CopsIndexNatureClass : new CopsIndexNatureClass($items[0]);
    }

    /**
     * @return array[CopsIndexNature]
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function getIndexNatures(&$attributes=[])
    {
        if (!isset($attributes[self::SQL_WHERE_FILTERS])) {
            $attributes[self::SQL_WHERE_FILTERS] = ['%'];
        }
        return $this->objDao->getIndexNatures($attributes);
    }
    
    //////////////////////////////////////////////////
    // WP_7_COPS_INDEX_TOME
    //////////////////////////////////////////////////

    /**
     * @since v1.23.07.13
     * @version v1.23.08.12
     */
    public function getIndexTomes(array $attributes=[]): array
    {
        $this->objDao = new CopsIndexDaoImpl();

        $id = $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;
        $abbr = $attributes[self::FIELD_ABR_IDX_TOME] ?? self::SQL_JOKER_SEARCH;
        ///////////////////////////////////////////////////////////

        $prepAttributes = [
            $id,
            $abbr,
            self::FIELD_NOM_IDX_TOME,
            self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getIndexTomes($prepAttributes);
    }


    /**
     * Retourne l'entité correspondant à l'identifiant
     * @param int $tomeId
     * @return CopsIndexTome
     * @since 1.23.02.20
     * @version v1.23.07.29
     */
    public function getCopsIndexTome($tomeId)
    {
        $attributes = [$tomeId];
        $row = $this->objDao->getIndexTome($attributes);
        return empty($row) ? new CopsIndexTomeClass() : new CopsIndexTomeClass($row[0]);
    }

    /**
     * Retourne l'entité IndexTome correspondant au paramètre
     * @since 1.23.02.20
     * @version v1.23.08.12
     */
    public function getIndexTomeByAbr(string $abr): CopsIndexTomeClass
    {
        $attributes = [self::FIELD_ABR_IDX_TOME => $abr];
        $items = $this->getIndexTomes($attributes);
        return empty($items) ? new CopsIndexTomeClass : new CopsIndexTomeClass($items[0]);
    }
}
