<?php
namespace core\interfaceimpl;

/**
 * @author Hugues
 * @since v1.23.05.01
 * @version v1.23.08.12
 */
interface FieldsInterface
{
    /////////////////////////////////////////////////
    // Fields
    // TABLE multiples
    public const FIELD_ID                 = 'id';

    public const FIELD_DATE_DEBUT         = 'dateDebut';
    public const FIELD_DATE_FIN           = 'dateFin';
    public const FIELD_DSTART             = 'dStart';
    public const FIELD_IDX_BDD_DABIS      = 'idxBddDabis';
    public const FIELD_IDX_ENQUETE        = 'idxEnquete';
    public const FIELD_REFERENCE          = 'reference';

    // TABLE cops_autopsie
    public const FIELD_DATA               = 'data';

    // TABLE cops_bdd_avocat
    // TABLE cops_bdd_dabis TODO
    // TABLE cops_bdd_enqueteur
    // TABLE cops_bdd_juge
    // TABLE cops_bdd_procureur
    public const FIELD_IDX_ID             = 'idxId';

    // TABLE cops_bdd_fcid
    public const FIELD_NOM_FCID           = 'nomFcid';
    public const FIELD_RACE_FCID          = 'raceFcid';
    public const FIELD_AGE_FCID           = 'ageFcid';
    public const FIELD_PROFESSION_FCID    = 'professionFcid';
    public const FIELD_ETATCIVIL_FCID     = 'etatCivilFcid';
    public const FIELD_ADRESSE_FCID       = 'adresseFcid';

    // TABLE cops_radio
    public const FIELD_CODE_RADIO         = 'codeRadio';
    public const FIELD_CODE_CATEGORY      = 'codeCategory';
    public const FIELD_CODE_LABEL         = 'codeLabel';

    // TABLE cops_enquete
    public const FIELD_NOM_ENQUETE        = 'nomEnquete';
    public const FIELD_IDX_ENQUETEUR      = 'idxEnqueteur';
    public const FIELD_IDX_DISTRICT_ATT   = 'idxDistrictAttorney';
    public const FIELD_RESUME_FAITS       = 'resumeFaits';
    public const FIELD_DESC_SCENE_CRIME   = 'descSceneDeCrime';
    public const FIELD_PISTES_DEMARCHES   = 'pistesDemarches';
    public const FIELD_NOTES_DIVERSES     = 'notesDiverses';
    public const FIELD_STATUT_ENQUETE     = 'statutEnquete';
    public const FIELD_DLAST              = 'dLast';

    // TABLE cops_enquete_chronologie
    public const FIELD_DATE_HEURE         = 'dateHeure';
    public const FIELD_FAITS              = 'faits';

    // TABLE cops_enquete_personnalite
    public const FIELD_IDX_BDD_FCID       = 'idxBddFcid';
    public const FIELD_FCFA_DETAIL        = 'fcfaDetail';
    public const FIELD_LHE_DETAIL         = 'lheDetail';
    public const FIELD_HQV_DETAIL         = 'hqvDetail';
    public const FIELD_TOA_DETAIL         = 'toaDetail';
    public const FIELD_CDNDV_DETAIL       = 'cdndvDetail';
    public const FIELD_T_DETAIL           = 'tDetail';
    public const FIELD_A_DETAIL           = 'aDetail';

    // TABLE cops_enquete_temoignage
    public const FIELD_NOM_TEMOIN         = 'nomTemoin';
    public const FIELD_QUALITE_TEMOIN     = 'qualiteTemoin';
    public const FIELD_LIEN_TEMOIN        = 'lienTemoin';
    public const FIELD_ALIBI_TEMOIN       = 'temoignageAlibiTemoin';
    public const FIELD_NOTES_TEMOIN       = 'notesTemoin';
    public const FIELD_VERIF_TEMOIN       = 'verifTemoin';
    public const FIELD_RELATION           = 'relation';

    // TABLE cops_event
    public const FIELD_EVENT_LIBELLE      = 'eventLibelle';
    public const FIELD_CATEG_ID           = 'categorieId';
    public const FIELD_ALL_DAY_EVENT      = 'allDayEvent';
    public const FIELD_CONTINU_EVENT      = 'continuEvent';
    public const FIELD_HEURE_DEBUT        = 'heureDebut';
    public const FIELD_HEURE_FIN          = 'heureFin';
    public const FIELD_REPEAT_STATUS      = 'repeatStatus';
    public const FIELD_REPEAT_TYPE        = 'repeatType';
    public const FIELD_REPEAT_INTERVAL    = 'repeatInterval';
    public const FIELD_REPEAT_END         = 'repeatEnd';
    public const FIELD_REPEAT_END_VALUE   = 'repeatEndValue';
    public const FIELD_CUSTOM_EVENT       = 'customEvent';
    public const FIELD_CUSTOM_DAY         = 'customDay';
    public const FIELD_CUSTOM_DAY_WEEK    = 'customDayWeek';
    public const FIELD_CUSTOM_MONTH       = 'customMonth';

    // TODO : Confirmer utilisation et définition dans table.
    public const FIELD_MINUTE_DEBUT       = 'minuteDebut';
    public const FIELD_MINUTE_FIN         = 'minuteFin';
    public const FIELD_ENDDATE_VALUE      = 'endDateValue';
    public const FIELD_ENDREPEAT_VALUE    = 'endRepetitionValue';

    // TABLE cops_event_categorie
    public const FIELD_CATEG_LIBELLE      = 'categorieLibelle';
    public const FIELD_CATEG_COLOR        = 'categorieCouleur';

    // TABLE cops_event_date
    public const FIELD_EVENT_ID           = 'eventId';
    public const FIELD_DEND               = 'dEnd';
    public const FIELD_TSTART             = 'tStart';
    public const FIELD_TEND               = 'tEnd';

    // TODO : continuer à partir d'ici.




    // TABLE cops_index
    public const FIELD_ID_IDX           = 'idIdx';
    public const FIELD_REF_IDX_ID       = 'referenceIdxId';
    public const FIELD_TOME_IDX_ID      = 'tomeIdxId';
    public const FIELD_PAGE             = 'page';

    // TABLE cops_index_nature
    public const FIELD_ID_IDX_NATURE    = 'idIdxNature';
    public const FIELD_NOM_IDX_NATURE   = 'nomIdxNature';

    // TABLE cops_index_reference
    public const FIELD_ID_IDX_REF       = 'idIdxReference';
    public const FIELD_NOM_IDX          = 'nomIdxReference';
    public const FIELD_PRENOM_IDX       = 'prenomIdxReference';
    public const FIELD_AKA_IDX          = 'akaIdxReference';
    public const FIELD_NATURE_IDX_ID    = 'natureIdxId';
    public const FIELD_DESCRIPTION_PJ   = 'descriptionPJ';
    public const FIELD_DESCRIPTION_MJ   = 'descriptionMJ';
    public const FIELD_CODE             = 'code';

    // TABLE cops_index_tome
    public const FIELD_ID_IDX_TOME      = 'idIdxTome';
    public const FIELD_NOM_IDX_TOME     = 'nomIdxTome';
    public const FIELD_ABR_IDX_TOME     = 'abrIdxTome';

    // TABLE cops_lune
    public const FIELD_ID_LUNE          = 'idLune';
    public const FIELD_DATE_LUNE        = 'dateLune';
    public const FIELD_HEURE_LUNE       = 'heureLune';
    public const FIELD_TYPE_LUNE        = 'typeLune';

    // TABLE cops_meteo
    public const FIELD_DATE_METEO       = 'dateMeteo';
    public const FIELD_HEURE_METEO      = 'heureMeteo';
    public const FIELD_TEMPERATURE      = 'temperature';
    public const FIELD_WEATHER          = 'weather';
    public const FIELD_WEATHER_ID       = 'weatherId';
    public const FIELD_FORCE_VENT       = 'forceVent';
    public const FIELD_SENS_VENT        = 'sensVent';
    public const FIELD_HUMIDITE         = 'humidite';
    public const FIELD_BAROMETRE        = 'barometre';
    public const FIELD_VISIBILITE       = 'visibilite';

    // TABLE cops_player
    public const FIELD_MATRICULE          = 'matricule';
    public const FIELD_PASSWORD           = 'password';
    public const FIELD_NOM                = 'nom';
    public const FIELD_PRENOM             = 'prenom';
    public const FIELD_SURNOM             = 'surnom';
    public const FIELD_CARAC_CARRURE      = 'caracCarrure';
    public const FIELD_CARAC_CHARME       = 'caracCharme';
    public const FIELD_CARAC_COORDINATION = 'caracCoordination';
    public const FIELD_CARAC_EDUCATION    = 'caracEducation';
    public const FIELD_CARAC_PERCEPTION   = 'caracPerception';
    public const FIELD_CARAC_REFLEXES     = 'caracReflexes';
    public const FIELD_CARAC_SANGFROID    = 'caracSangfroid';
    public const FIELD_BIRTH_DATE         = 'birthDate';
    public const FIELD_PV_MAX             = 'pvMax';
    public const FIELD_PV_CUR             = 'pvCur';
    public const FIELD_PAD_MAX            = 'padMax';
    public const FIELD_PAD_CUR            = 'padCur';
    public const FIELD_PAN_MAX            = 'panMax';
    public const FIELD_PAN_CUR            = 'panCur';
    public const FIELD_TAILLE             = 'taille';
    public const FIELD_POIDS              = 'poids';
    public const FIELD_SEXE               = 'sexe';
    public const FIELD_ETHNIE             = 'ethnie';
    public const FIELD_CHEVEUX            = 'cheveux';
    public const FIELD_YEUX               = 'yeux';
    public const FIELD_ETUDES             = 'etudes';
    public const FIELD_ORIGINE_SOCIALE    = 'origineSociale';
    public const FIELD_GRADE              = 'grade';
    public const FIELD_GRADE_ECHELON      = 'gradeEchelon';
    public const FIELD_GRADE_RANG         = 'gradeRang';
    public const FIELD_INTEGRATION_DATE   = 'integrationDate';
    public const FIELD_SECTION            = 'section';
    public const FIELD_BACKGROUND         = 'background';
    public const FIELD_STATUS             = 'status';
    public const FIELD_PX_CUMUL           = 'pxCumul';
    public const FIELD_PX_CUR             = 'pxCur';

    // TABLE cops_skill
    public const FIELD_SKILL_NAME         = 'skillName';
    public const FIELD_SKILL_DESC         = 'skillDescription';
    public const FIELD_SKILL_USES         = 'skillUses';
    public const FIELD_SPEC_LEVEL         = 'specLevel';
    public const FIELD_PAD_USABLE         = 'padUsable';
    public const FIELD_DEFAULT_ABILITY    = 'defaultAbility';

    // TABLE cops_skill_spec
    public const FIELD_SPEC_NAME          = 'specName';
    public const FIELD_SKILL_ID           = 'skillId';

    // TABLE cops_player_skill_joint
    public const FIELD_COPS_ID            = 'copsId';
    public const FIELD_SPEC_SKILL_ID      = 'specSkillId';
    public const FIELD_SCORE              = 'score';

    // TABLE cops_soleil
    public const FIELD_DATE_SOLEIL      = 'dateSoleil';
    public const FIELD_HEURE_LEVER      = 'heureLever';
    public const FIELD_HEURE_COUCHER    = 'heureCoucher';
    public const FIELD_HEURE_CULMINE    = 'heureCulmine';
    public const FIELD_DUREE_JOUR       = 'dureeJour';
    public const FIELD_HEURE_CIVIL_AM   = 'heureCivilAm';
    public const FIELD_HEURE_CIVIL_PM   = 'heureCivilPm';
    public const FIELD_HEURE_NAUTIK_AM  = 'heureNautikAm';
    public const FIELD_HEURE_NAUTIK_PM  = 'heureNautikPm';
    public const FIELD_HEURE_ASTRO_AM   = 'heureAstroAm';
    public const FIELD_HEURE_ASTRO_PM   = 'heureAstroPm';

    // TABLE cops_stage
    public const FIELD_STAGE_CAT_ID       = 'stageCategorieId';
    public const FIELD_STAGE_LIBELLE      = 'stageLibelle';
    public const FIELD_STAGE_LEVEL        = 'stageNiveau';
    public const FIELD_STAGE_REFERENCE    = 'stageReference';
    public const FIELD_STAGE_REQUIS       = 'stagePreRequis';
    public const FIELD_STAGE_REQUIS_NEW   = 'stagePreRequisNew';
    public const FIELD_STAGE_CUMUL        = 'stageCumul';
    public const FIELD_STAGE_DESC         = 'stageDescription';
    public const FIELD_STAGE_BONUS        = 'stageBonus';

    // TABLE cops_stage_categorie
    public const FIELD_STAGE_CAT_NAME     = 'stageCategorie';

    // TABLE cops_stage_spec
    public const FIELD_SPEC_DESC          = 'specDescription';
    public const FIELD_STAGE_ID           = 'stageId';

    // TABLE cops_weapon
    public const FIELD_NOM_ARME           = 'nomArme';
    public const FIELD_TYPE_ARME          = 'typeArme';
    public const FIELD_SKILL_USE          = 'compUtilisee';
    public const FIELD_SCORE_PR           = 'scorePr';
    public const FIELD_SCORE_PU           = 'scorePu';
    public const FIELD_SCORE_FA           = 'scoreFa';
    public const FIELD_SCORE_DIS          = 'scoreDis';
    public const FIELD_SCORE_VRC          = 'scoreVrc';
    public const FIELD_PORTEE             = 'portee';
    public const FIELD_SCORE_VC           = 'scoreVc';
    public const FIELD_SCORE_CT           = 'scoreCt';
    public const FIELD_MUNITIONS          = 'munitions';
    public const FIELD_PRIX               = 'prix';

    // TABLE cops_vehicule
    public const FIELD_VEH_LABEL          = 'vehLabel';
    public const FIELD_VEH_CATEG          = 'vehCategorie';
    public const FIELD_VEH_SS_CATEG       = 'vehSousCategorie';
    public const FIELD_VEH_PLACES         = 'vehPlaces';
    public const FIELD_VEH_SPEED          = 'vehVitesse';
    public const FIELD_VEH_ACCELERE       = 'vehAcceleration';
    public const FIELD_VEH_PS             = 'vehPointStructure';
    public const FIELD_VEH_AUTONOMIE      = 'vehAutonomie';
    public const FIELD_VEH_FUEL           = 'vehCarburant';
    public const FIELD_VEH_OPTIONS        = 'vehOptions';
    public const FIELD_VEH_PRICE          = 'vehPrix';
    public const FIELD_VEH_YEAR           = 'vehAnnee';
    public const FIELD_VEH_REFERENCE      = 'vehReference';
    public const FIELD_VEH_LGN_ROUGE      = 'vehLigneRouge';
    
    // TABLE cops_tchat
    public const FIELD_SALON_ID           = 'salonId';
    public const FIELD_TO_PID             = 'toPlayerId';
    public const FIELD_FROM_PID           = 'fromPlayerId';
    public const FIELD_TIMESTAMP          = 'timestamp';
    public const FIELD_TEXTE              = 'texte';
    
    // TABLE cops_tchat_salon
    public const FIELD_NOM_SALON          = 'nomSalon';
    public const FIELD_OWNER_ID           = 'ownerId';
    
    // TABLE cops_tchat_status
    public const FIELD_PLAYER_ID          = 'playerId';
    public const FIELD_LAST_REFRESHED     = 'lastRefreshed';

    // TABLE wp_7_cops_cal_random_guy
    public const FIELD_GENDER      = 'gender';
    public const FIELD_NAMESET     = 'nameSet';
    public const FIELD_TITLE       = 'title';
    public const FIELD_FIRSTNAME   = 'firstName';
    public const FIELD_LASTNAME    = 'lastName';
    public const FIELD_NBADRESS    = 'numberAdress';
    public const FIELD_STADRESS    = 'streetAdress';
    public const FIELD_CITY        = 'city';
    public const FIELD_ZIPCODE     = 'zipCode';
    public const FIELD_EMAILADRESS = 'emailAdress';
    public const FIELD_PHONENUMBER = 'telephoneNumber';
    public const FIELD_BIRTHDAY    = 'birthday';
    public const FIELD_OCCUPATION  = 'occupation';
    public const FIELD_COMPANY     = 'company';
    public const FIELD_VEHICLE     = 'vehicle';
    public const FIELD_COLOR       = 'color';
    public const FIELD_KILOGRAMS   = 'kilograms';
    public const FIELD_CENTIMETERS = 'centimeters';

    public const FIELD_ZIP         = 'zip';
    public const FIELD_PRIMARY_CITY= 'primaryCity';

    public const FIELD_PHONE_ID    = 'phoneId';
    public const FIELD_CITY_NAME   = 'cityName';


    // Provenance ?
    public const FIELD_ICON             = 'icon';
    public const FIELD_FOLDER_ID        = 'folderId';
    public const FIELD_FROM_ID          = 'fromId';
    public const FIELD_LABEL            = 'label';
    public const FIELD_LU               = 'lu';
    public const FIELD_MAIL             = 'mail';
    public const FIELD_MAIL_ID          = 'mailId';
    public const FIELD_MAIL_CONTENT     = 'mail_content';
    public const FIELD_MAIL_DATE_ENVOI  = 'mail_dateEnvoi';
    public const FIELD_MAIL_SUBJECT     = 'mail_subject';
    public const FIELD_NB_PJS           = 'nbPjs';
    public const FIELD_SLUG             = 'slug';
    public const FIELD_TO_ID            = 'toId';
}
