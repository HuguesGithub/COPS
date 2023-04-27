<?php
namespace core\actions;

use core\utils\DateUtils;

/**
 * CopsAutopsieActions
 * @author Hugues
 * @since 1.22.10.10
 * @version v1.23.04.30
 */
class CopsAutopsieActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public function __construct()
    {
        parent::__construct();
        $this->CopsAutopsieServices = new CopsAutopsieServices();
    }

    /**
     * @since 1.22.10.10
     * @version 1.22.10.10
     */
    public static function insertAutopsie($urlParams)
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsAutopsieServices = new CopsAutopsieServices();
        // On créé l'objet
        $objCopsAutopsie = new CopsAutopsie();
        // On récupère les données passées en paramètres spécifiques à l'objet Autopsie
        $objCopsAutopsie->setField(self::FIELD_IDX_ENQUETE, $urlParams[self::FIELD_IDX_ENQUETE]);
        // On nettoie les données indésirables dans le champ data
        unset($urlParams[self::CST_ONGLET]);
        unset($urlParams[self::CST_SUBONGLET]);
        unset($urlParams[self::CST_WRITE_ACTION]);
        unset($urlParams[self::FIELD_ID]);
        unset($urlParams[self::FIELD_IDX_ENQUETE]);
        ////////////////////////////////////////////
        $objCopsAutopsie->setField(self::FIELD_DATA, serialize($urlParams));
        // On définit le timestamp du jeu
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        $objCopsAutopsie->setField(self::FIELD_DSTART, $tsNow);
        ////////////////////////////////////////////
        
        ////////////////////////////////////////////
        // On insère l'objet
        $objCopsAutopsieServices->insertAutopsie($objCopsAutopsie);
        return $objCopsAutopsie;
    }
    
    /**
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public static function updateAutopsie($urlParams)
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsAutopsieServices = new CopsAutopsieServices();
        // On créé l'objet
        $objCopsAutopsie = $objCopsAutopsieServices->getAutopsie($urlParams[self::FIELD_ID]);
        // On récupère les données passées en paramètres spécifiques à l'objet Autopsie
        $objCopsAutopsie->setField(self::FIELD_IDX_ENQUETE, $urlParams[self::FIELD_IDX_ENQUETE]);
        // On nettoie les données indésirables dans le champ data
        unset($urlParams[self::CST_ONGLET]);
        unset($urlParams[self::CST_SUBONGLET]);
        unset($urlParams[self::CST_WRITE_ACTION]);
        unset($urlParams[self::FIELD_ID]);
        unset($urlParams[self::FIELD_IDX_ENQUETE]);
        ////////////////////////////////////////////
        $objCopsAutopsie->setField(self::FIELD_DATA, serialize($urlParams));
        ////////////////////////////////////////////
        
        ////////////////////////////////////////////
        // On update l'objet
        $objCopsAutopsieServices->updateAutopsie($objCopsAutopsie);
        return $objCopsAutopsie;
    }
    
}
