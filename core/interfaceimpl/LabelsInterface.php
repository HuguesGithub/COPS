<?php
namespace core\interfaceimpl;

/**
 * @author Hugues
 * @since v1.22.11.24
 * @version v1.23.07.15
 */
interface LabelsInterface
{
    public const LABEL_HOME           = 'Accueil';
    public const LABEL_ACTUELLE       = 'Actuelle';
    public const LABEL_YEARLY         = 'Annuel';
    public const LABEL_WEAPONS        = 'Armes';
    public const LABEL_NO_RESULT      = 'Aucun résultat';
    public const LABEL_OTHERS         = 'Autres';
    public const LABEL_DATABASES      = 'Bases de données';
    public const LABEL_BACKGROUND     = 'Background';
    public const LABEL_BAROMETER      = 'Baromètre';
    public const LABEL_LIBRARY        = 'Bibliothèque';
    public const LABEL_BUREAU         = 'Bureau';
    public const LABEL_CALENDAR       = 'Calendrier';
    public const LABEL_CATEGORIES     = 'Catégories';
    public const LABEL_ABILITIES      = 'Caractéristiques';
    public const LABEL_CARRURE        = 'Carrure';
    public const LABEL_CHARME         = 'Charme';
    public const LABEL_CODE           = 'Code';
    public const LABEL_SKILLS         = 'Compétences';
    public const LABEL_CONDITIONS     = 'Conditions';
    public const LABEL_CONFORT        = 'Confort';
    public const LABEL_CONTACTS       = 'Contacts';
    public const LABEL_COORDINATION   = 'Coordination';
    public const LABEL_TRASH          = 'Corbeille';
    public const LABEL_CREER_ENTREE   = 'Créer une entrée';
    public const LABEL_DESCRIPTION    = 'Description';
    public const LABEL_DESCRIPTION_MJ = 'Description MJ';
    public const LABEL_DESCRIPTION_PJ = 'Description PJ';
    public const LABEL_EDUCATION      = 'Éducation';
    public const LABEL_EQUIPMENT      = 'Équipement';
    public const LABEL_ERREUR         = 'Erreur';
    public const LABEL_EVENTS         = 'Événements';
    public const LABEL_EXPORT_LIST    = 'Exporter la liste';
    public const LABEL_WEEKLY         = 'Hebdomadaire';
    public const LABEL_HEURE          = 'Heure';
    public const LABEL_HUMIDITY       = 'Humidité';
    public const LABEL_IDENTITY       = 'Identité';
    public const LABEL_INDEX          = 'Index';
    public const LABEL_MOON           = 'Lune';
    public const LABEL_WEATHER        = 'Météo';
    public const LABEL_MONTHLY        = 'Mensuel';
    public const LABEL_MESSAGERIE     = 'Messagerie';
    public const LABEL_EDIT_ENTRY     = 'Modifier cette entrée';
    public const LABEL_NATURE         = 'Nature';
    public const LABEL_NOM            = 'Nom';
    public const LABEL_PARENTS        = 'Parents';
    public const LABEL_PERCEPTION     = 'Perception';
    public const LABEL_POINTSDEVIE    = 'Points de vie';
    public const LABEL_POINTSADRE     = 'Adrénaline';
    public const LABEL_POINTSANC      = 'Ancienneté';
    public const LABEL_POINTSEXPE     = 'Expérience';
    public const LABEL_PRECEDENTE     = 'Précédente';
    public const LABEL_PROFILE        = 'Profil';
    public const LABEL_DAILY          = 'Quotidien';
    public const LABEL_REFRESH_LIST   = 'Rafraîchir la liste';
    public const LABEL_INBOX          = 'Réception';
    public const LABEL_WRITE_MAIL     = 'Rédiger un message';
    public const LABEL_REFERENCE      = 'Référence';
    public const LABEL_REFLEXES       = 'Réflexes';
    public const LABEL_RETOUR         = 'Retour';
    public const LABEL_SANGFROID      = 'Sang-froid';
    public const LABEL_SUN            = 'Soleil';
    public const LABEL_COURSES        = 'Stages';
    public const LABEL_SUCCES         = 'Succès';
    public const LABEL_SUIVANTE       = 'Suivante';
    public const LABEL_DELETE         = 'Supprimer';
    public const LABEL_DELETE_EVENT   = 'Supprimer un événement';
    public const LABEL_TABLE_LUNE     = 'Table Lune';
    public const LABEL_TABLE_WEATHER  = 'Table Météo';
    public const LABEL_TABLE_SOLEIL   = 'Table Soleil';
    public const LABEL_TEMP           = 'Temp.';
    public const LABEL_WIND           = 'Vent';
    public const LABEL_CARS           = 'Véhicules';
    public const LABEL_VISIBILITY     = 'Visibilité';
    
    public const DYN_DISPLAYED_PAGINATION = '%1$s - %2$s sur %3$s';
    public const DYN_FIRST_ENTRY      = 'Première entrée : %1$s.';
    public const DYN_LAST_ENTRY       = 'Dernière entrée : %1$s.';
    public const DYN_DELETE_EVENT     = 'Confirmez-vous la suppression de l\'événement <strong>%1$s</strong>, identifiant <strong>%2$s</strong> ?';
    public const DYN_WRONG_ID         = 'Cet identifiant <strong>%1$s</strong> ne correspond à aucune entrée.';
    public const DYN_SUCCESS_FIELD_UPDATE = 'Le champ <em>%1$s</em> du personnage a été mis à jour.';
    public const DYN_WRONG_FIELD      = 'Le champ passé en paramètre n\'a pas une valeur attendue : <strong>%1$s</strong>.';
    public const DYN_WRONG_VALUE      = 'La valeur <strong>%1$s</strong> ne remplit pas le format attendu du champ <strong>%2$s</strong>.';
}
