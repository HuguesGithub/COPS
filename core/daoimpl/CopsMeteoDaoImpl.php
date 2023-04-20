<?php
namespace core\daoimpl;

use core\domain\CopsMeteoClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe CopsMeteoDaoImpl
 * @author Hugues
 * @since 1.23.4.20
 * @version 1.23.4.20
 */
class CopsMeteoDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function __construct()
    {
        ////////////////////////////////////
        // Définition des variables spécifiques
        $this->dbTable_met  = "wp_7_cops_meteo";
        $this->dbTable_sol  = "wp_7_cops_soleil";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields_met  = [
            self::FIELD_ID,
            self::FIELD_DATE_METEO,
            self::FIELD_HEURE_METEO,
            self::FIELD_TEMPERATURE,
            self::FIELD_WEATHER,
            self::FIELD_WEATHER_ID,
            self::FIELD_FORCE_VENT,
            self::FIELD_SENS_VENT,
            self::FIELD_HUMIDITE,
            self::FIELD_BAROMETRE,
            self::FIELD_VISIBILITE
        ];
        $this->dbFields_sol  = [self::FIELD_DATE_SOLEIL, self::FIELD_HEURE_LEVER, self::FIELD_HEURE_COUCHER];
        ////////////////////////////////////
        
        parent::__construct();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    //////////////////////////////////////////////////
    // WP_7_COPS_METEO
    //////////////////////////////////////////////////
    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function getMeteos(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_met), $this->dbTable_met);
        $request .= " WHERE dateMeteo LIKE '%s'";
        $request .= " ORDER BY heureMeteo ASC;";
        return $this->selectListDaoImpl(new CopsMeteoClass(), $request, $attributes);
    }

    //////////////////////////////////////////////////
    // WP_7_COPS_SOLEIL
    //////////////////////////////////////////////////
    /**
     * @since 1.23.4.20
     * @version 1.23.4.20
     */
    public function getSoleil(array $prepObject): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields_sol), $this->dbTable_sol, self::FIELD_ID);
        return $this->selectDaoImpl($request, $prepObject);
    }

}
