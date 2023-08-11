<?php
namespace core\services;

use core\daoimpl\CopsEquipmentDaoImpl;
use core\domain\CopsEquipmentCarClass;
use core\domain\CopsEquipmentWeaponClass;

/**
 * Classe CopsEquipmentServices
 * @author Hugues
 * @since v1.23.07.12
 * @version v1.23.08.12
 */
class CopsEquipmentServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
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
            $this->objDao = new CopsEquipmentDaoImpl();
        }
    }

    ////////////////////////////////////
    // wp_7_cops_vehicule
    ////////////////////////////////////

    /**
     * @since v1.23.07.19
     * @version v1.23.08.12
     */
    public function getVehicle(int $id): CopsEquipmentCarClass
    {
        $attributes = [self::FIELD_ID => $id];
        $objs = $this->getVehicles($attributes);
        return !empty($objs) ? array_shift($objs) : new CopsEquipmentCarClass();
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.08.12
     */
    public function getVehicles(array $attributes): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::FIELD_VEH_CATEG] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_VEH_LABEL,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getVehicles($prepAttributes);
    }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.29
     */
    public function insertVehicle(CopsEquipmentCarClass &$obj): void
    { $this->objDao->insertVehicle($obj); }

    /**
     * @since v1.23.07.19
     * @version v1.23.07.29
     */
    public function updateVehicle(CopsEquipmentCarClass $obj): void
    { $this->objDao->updateVehicle($obj); }

    ////////////////////////////////////
    // wp_7_cops_weapon
    ////////////////////////////////////

    /**
     * @since v1.23.07.12
     * @version v1.23.08.12
     */
    public function getWeapon(int $id): CopsEquipmentWeaponClass
    {
        $attributes = [self::FIELD_ID => $id];
        $objs = $this->getWeapons($attributes);
        return !empty($objs) ? array_shift($objs) : new CopsEquipmentWeaponClass();
    }

    /**
     * @since v1.23.07.12
     * @version v1.23.08.12
     */
    public function getWeapons(array $attributes): array
    {
        ///////////////////////////////////////////////////////////
        $prepAttributes = [
            $attributes[self::FIELD_ID] ?? self::SQL_JOKER_SEARCH,
            $attributes[self::SQL_ORDER_BY] ?? self::FIELD_NOM_ARME,
            $attributes[self::SQL_ORDER] ?? self::SQL_ORDER_ASC,
            $attributes[self::SQL_LIMIT] ?? 9999,
        ];
        return $this->objDao->getWeapons($prepAttributes);
    }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.29
     */
    public function insertWeapon(CopsEquipmentWeaponClass &$obj): void
    { $this->objDao->insertWeapon($obj); }

    /**
     * @since v1.23.07.13
     * @version v1.23.07.29
     */
    public function updateWeapon(CopsEquipmentWeaponClass $obj): void
    { $this->objDao->updateWeapon($obj); }
}
