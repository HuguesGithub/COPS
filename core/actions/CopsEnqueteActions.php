<?php
declare(strict_types=1);

namespace core\actions;

use core\services\CopsEnqueteServices;
use core\domain\CopsEnqueteClass;
use core\bean\UtilitiesBean;
use core\utils\DateUtils;

/**
 * CopsEnqueteActions
 * @author Hugues
 * @since 1.22.09.24
 * @version v1.23.04.30
 */
class CopsEnqueteActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////

    // TODO :
    // Gérer les données relatives aux tables annexes.
    // Enquêtes de personnalité
    // Témoins / Suspects
    // Chronologie
    // Autopsie
    // Analyse de scène de crime
    
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public static function insertEnquete(array $urlParams): CopsEnqueteClass
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsEnqueteServices = new CopsEnqueteServices();
        // On définit le timestamp du jeu
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        ////////////////////////////////////////////
        // On créé l'objet
        $objCopsEnquete = new CopsEnqueteClass();
        ////////////////////////////////////////////
        // On récupère les données passées en paramètres spécifiques à l'objet Enquete
        // Et on les enrichit de données supplémentaires.
        // Le nom de l'enquête
        $objCopsEnquete->setField(self::FIELD_NOM_ENQUETE, $urlParams[self::FIELD_NOM_ENQUETE]);
        // L'id du premier enquêteur
        $objCopsEnquete->setField(self::FIELD_IDX_ENQUETEUR, $urlParams[self::FIELD_IDX_ENQUETEUR]);
        // L'id du District Attorney
        $objCopsEnquete->setField(self::FIELD_IDX_DISTRICT_ATT, $urlParams[self::FIELD_IDX_DISTRICT_ATT]);
        // Le résumé des faits
        $content = stripslashes((string) $urlParams[self::FIELD_RESUME_FAITS]);
        $objCopsEnquete->setField(self::FIELD_RESUME_FAITS, $content);
        // La description de la scène de crime
        $content = stripslashes((string) $urlParams[self::FIELD_DESC_SCENE_CRIME]);
        $objCopsEnquete->setField(self::FIELD_DESC_SCENE_CRIME, $content);
        // Les pistes et les démarches
        $content = stripslashes((string) $urlParams[self::FIELD_PISTES_DEMARCHES]);
        $objCopsEnquete->setField(self::FIELD_PISTES_DEMARCHES, $content);
        // Les notes diverses
        $content = stripslashes((string) $urlParams[self::FIELD_NOTES_DIVERSES]);
        $objCopsEnquete->setField(self::FIELD_NOTES_DIVERSES, $content);
        // Le statut à ouvert par défaut
        $objCopsEnquete->setField(self::FIELD_STATUT_ENQUETE, self::CST_ENQUETE_OPENED);
        // La date de création
        $objCopsEnquete->setField(self::FIELD_DSTART, $tsNow);
        // La date de dernière modification
        $objCopsEnquete->setField(self::FIELD_DLAST, $tsNow);
        
        ////////////////////////////////////////////
        // On insère l'objet
        $objCopsEnqueteServices->insertEnquete($objCopsEnquete);
        return $objCopsEnquete;
    }
    
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public static function updateEnquete(array $urlParams): CopsEnqueteClass
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsEnqueteServices = new CopsEnqueteServices();
        // On définit le timestamp du jeu
        $tsNow = DateUtils::getCopsDate(self::FORMAT_TS_NOW);
        ////////////////////////////////////////////
        // On récupère l'objet présent en base
        $objCopsEnquete = $objCopsEnqueteServices->getEnquete($urlParams[self::FIELD_ID]);
        ////////////////////////////////////////////
        // On met à jour les données saisies
        // Le nom de l'enquête
        $objCopsEnquete->setField(self::FIELD_NOM_ENQUETE, $urlParams[self::FIELD_NOM_ENQUETE]);
        // L'id du premier enquêteur
        $objCopsEnquete->setField(self::FIELD_IDX_ENQUETEUR, $urlParams[self::FIELD_IDX_ENQUETEUR]);
        // L'id du District Attorney
        $objCopsEnquete->setField(self::FIELD_IDX_DISTRICT_ATT, $urlParams[self::FIELD_IDX_DISTRICT_ATT]);
        // Le résumé des faits
        $content = stripslashes((string) $urlParams[self::FIELD_RESUME_FAITS]);
        $objCopsEnquete->setField(self::FIELD_RESUME_FAITS, $content);
        // La description de la scène de crime
        $content = stripslashes((string) $urlParams[self::FIELD_DESC_SCENE_CRIME]);
        $objCopsEnquete->setField(self::FIELD_DESC_SCENE_CRIME, $content);
        // Les pistes et les démarches
        $content = stripslashes((string) $urlParams[self::FIELD_PISTES_DEMARCHES]);
        $objCopsEnquete->setField(self::FIELD_PISTES_DEMARCHES, $content);
        // Les notes diverses
        $content = stripslashes((string) $urlParams[self::FIELD_NOTES_DIVERSES]);
        $objCopsEnquete->setField(self::FIELD_NOTES_DIVERSES, $content);
        // Le statut ne change pas
        // La date de création ne change pas
        // La date de dernière modification
        $objCopsEnquete->setField(self::FIELD_DLAST, $tsNow);

        ////////////////////////////////////////////
        // On met à jour l'objet
        $objCopsEnqueteServices->updateEnquete($objCopsEnquete);
        return $objCopsEnquete;
    }
    
}
