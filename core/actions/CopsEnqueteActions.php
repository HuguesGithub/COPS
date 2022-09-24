<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEnqueteActions
 * @author Hugues
 * @since 1.22.09.24
 * @version 1.22.09.24
 */
class CopsEnqueteActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public function __construct()
    {
        parent::__construct();
        $this->CopsEnqueteServices = new CopsEnqueteServices();
    }

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
    public static function insertEnquete($urlParams)
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsEnqueteServices = new CopsEnqueteServices();
        // On définit le timestamp du jeu
        $tsNow = UtilitiesBean::getCopsDate('tsnow');
        ////////////////////////////////////////////
        // On récupère les données passées en paramètres spécifiques à l'objet Enquete
        // Et on les enrichit de données supplémentaires.
        $attributes = array(
            // Le nom de l'enquête
            self::FIELD_NOM_ENQUETE      => $urlParams[self::FIELD_NOM_ENQUETE],
            // L'id du premier enquêteur
            self::FIELD_IDX_ENQUETEUR    => $urlParams[self::FIELD_IDX_ENQUETEUR],
            // L'id du District Attorney
            self::FIELD_IDX_DISTRICT_ATT => $urlParams[self::FIELD_IDX_DISTRICT_ATT],
            // Le résyumé des faits
            self::FIELD_RESUME_FAITS     => $urlParams[self::FIELD_RESUME_FAITS],
            // La description de la scène de crime
            self::FIELD_DESC_SCENE_CRIME => $urlParams[self::FIELD_DESC_SCENE_CRIME],
            // Les pistes et les démarches
            self::FIELD_PISTES_DEMARCHES => $urlParams[self::FIELD_PISTES_DEMARCHES],
            // Les notes diverses
            self::FIELD_NOTES_DIVERSES   => $urlParams[self::FIELD_NOTES_DIVERSES],
            // Le statut
            self::FIELD_STATUT_ENQUETE   => self::CST_ENQUETE_OPENED,
            // La date de création
            self::FIELD_DSTART           => $tsNow,
            // La date de dernière modification
            self::FIELD_DLAST            => $tsNow,
        );
        ////////////////////////////////////////////
        // On créé l'objet
        $objCopsEnquete = new CopsEnquete($attributes);
        ////////////////////////////////////////////
        // On insère l'objet
        $objCopsEnqueteServices->insertEnquete($objCopsEnquete);
    }
    
    /**
     * @since 1.22.09.24
     * @version 1.22.09.24
     */
    public static function updateEnquete($urlParams)
    {
        ////////////////////////////////////////////
        // On créé le Service
        $objCopsEnqueteServices = new CopsEnqueteServices();
        // On définit le timestamp du jeu
        $tsNow = UtilitiesBean::getCopsDate('tsnow');
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
        $objCopsEnquete->setField(self::FIELD_RESUME_FAITS, $urlParams[self::FIELD_RESUME_FAITS]);
        // La description de la scène de crime
        $objCopsEnquete->setField(self::FIELD_DESC_SCENE_CRIME, $urlParams[self::FIELD_DESC_SCENE_CRIME]);
        // Les pistes et les démarches
        $objCopsEnquete->setField(self::FIELD_PISTES_DEMARCHES, $urlParams[self::FIELD_PISTES_DEMARCHES]);
        // Les notes diverses
        $objCopsEnquete->setField(self::FIELD_NOTES_DIVERSES, $urlParams[self::FIELD_NOTES_DIVERSES]);
        // Le statut ne change pas
        // La date de création ne change pas
        // La date de dernière modification
        $objCopsEnquete->setField(self::FIELD_DLAST, $tsNow);

        ////////////////////////////////////////////
        // On met à jour l'objet
        $objCopsEnqueteServices->updateEnquete($objCopsEnquete);
    }
    
}
