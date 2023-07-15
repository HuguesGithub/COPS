<?php
namespace core\services;

use core\daoimpl\CopsEquipmentDaoImpl;
use core\domain\CopsEquipmentWeaponClass;

/**
 * Classe CopsEquipmentServices
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.07.15
 */
class CopsEquipmentServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////

    ////////////////////////////////////
    // wp_7_cops_weapon
    ////////////////////////////////////

    /**
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public function getWeapon(int $id): CopsEquipmentWeaponClass
    {
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_ID => $id,
            ]
        ];
        $objs = $this->getWeapons($attributes);
        return !empty($objs) ? array_shift($objs) : new CopsEquipmentWeaponClass();
    }

    /**
     * @since v1.23.07.12
     * @version v1.23.07.15
     */
    public function getWeapons(array $attributes): array
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsEquipmentDaoImpl();
        }
        $id = $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] ?? self::SQL_JOKER_SEARCH;

        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $orderBy = $attributes[self::SQL_ORDER_BY] ?? self::FIELD_NOM_ARME;
        $order = $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC;
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $id,
            $orderBy,
            $order,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->Dao->getWeapons($prepAttributes);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function insertWeapon(CopsEquipmentWeaponClass &$obj): void
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsEquipmentDaoImpl();
        }
        $this->Dao->insertWeapon($obj);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.15
     */
    public function updateWeapon(CopsEquipmentWeaponClass $obj): void
    {
        if ($this->Dao==null) {
            $this->Dao = new CopsEquipmentDaoImpl();
        }
        // Une mise à jour.
        $this->Dao->updateWeapon($obj);
    }
}
