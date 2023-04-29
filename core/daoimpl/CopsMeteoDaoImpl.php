<?php
namespace core\daoimpl;

use core\domain\CopsMeteoClass;

/**
 * Classe CopsMeteoDaoImpl
 * @author Hugues
 * @since 1.23.4.20
 * @version v1.23.04.30
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
        $this->dbTable  = "wp_7_cops_meteo";
        ////////////////////////////////////

        ////////////////////////////////////
        // Définition des champs spécifiques
        $this->dbFields  = [
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
     * @version v1.23.04.30
     */
    public function getMeteos(array $attributes): array
    {
        $request  = $this->getSelectRequest(implode(', ', $this->dbFields), $this->dbTable);
        $request .= " WHERE dateMeteo LIKE '%s'";
        $request .= " ORDER BY %s %s LIMIT %s";
        return $this->selectListDaoImpl(new CopsMeteoClass(), $request, $attributes);
    }

    /**
     * @since v1.23.04.29
     * @version v1.23.04.30
     */
    public function insertMeteo(array $attributes): void
    {
        $request  = "INSERT INTO ".$this->dbTable." (".implode(', ', $this->dbFields).") ";
        $request .= "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
        $this->traceRequest($request);
        MySQLClass::wpdbQuery($request);
    }

}
