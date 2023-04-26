<?php
namespace core\interfaceimpl;

/**
 * @author Hugues
 * @since v1.00.00
 * @version v1.23.04.30
 */
interface ConstantsInterface
{
    public const VERSION              = 'v1.22.10.09';
  /////////////////////////////////////////////////
  // Ajax Infos
    public const AJAX_ACTION            = 'ajaxAction';

  /////////////////////////////////////////////////
  // Tag's Attributes
    public const ATTR_ALT               = 'alt';
    public const ATTR_CLASS             = 'class';
    public const ATTR_HREF              = 'href';
    public const ATTR_NAME              = 'name';
    public const ATTR_ROLE              = 'role';
    public const ATTR_SRC               = 'src';
    public const ATTR_STYLE             = 'style';
    public const ATTR_TITLE             = 'title';
    public const ATTR_TYPE              = 'type';
    public const ATTR_VALUE             = 'value';

    public const ATTR_DATA              = 'data';
    public const ATTR_DATA_AJAX         = 'ajax';
    public const ATTR_DATA_DATE         = 'data-date';
    public const ATTR_DATA_ICON         = 'data-icon';
    public const ATTR_DATA_TRIGGER      = 'trigger';

  /////////////////////////////////////////////////
  // Constantes
    public const CST_ACTIVE             = 'active';
  // Subonglet Calendar
    public const CST_CAL_COPSDATE       = 'cops_date';
    public const CST_CAL_CURDAY         = 'curday';
    public const CST_CAL_DAY            = 'day';
    public const CST_CAL_EVENT          = 'calendar-event';
    public const CST_CAL_MONTH          = 'month';
    public const CST_CAL_PARAM          = 'calendar-parameters';
    public const CST_CAL_WEEK           = 'week';
    // Subonglet File
    public const CST_FILE_OPENED        = 'opened';
    public const CST_FILE_CLOSED        = 'closed';
    public const CST_FILE_COLDED        = 'colded';
    // Subonglet Mail
    public const CST_FOLDER_ALERT       = 'alert';
    public const CST_FOLDER_DRAFT       = 'draft';
    public const CST_FOLDER_EVENTS      = 'events';
    public const CST_MAIL_INBOX         = 'inbox';
    public const CST_FOLDER_SENT        = 'sent';
    public const CST_FOLDER_SPAM        = 'spam';
    public const CST_MAIL_TRASH         = 'trash';
    public const CST_MAIL_READ          = 'read';
    public const CST_MAIL_WRITE         = 'write';
    // Subonglet Library
    public const CST_LIB_INDEX          = 'index';
    public const CST_LIB_BDD            = 'bdd';
    public const CST_LIB_COPS           = 'cops';
    public const CST_LIB_LAPD           = 'lapd';
    public const CST_LIB_SKILL          = 'skill';
    public const CST_LIB_STAGE          = 'stage';
    // Subonglet Profile
    public const CST_PFL_ABILITIES      = 'abilities';
    public const CST_PFL_BACKGROUND     = 'background';
    public const CST_PFL_CONTACTS       = 'contacts';
    public const CST_PFL_EQUIPMENT      = 'equipment';
    public const CST_PFL_IDENTITY       = 'identity';
    public const CST_PFL_SKILLS         = 'skills';
    // Subonglet Enquête
    public const CST_ENQUETE_OPENED     = 1;
    public const CST_ENQUETE_CLOSED     = 2;
    public const CST_ENQUETE_COLDED     = 0;
    public const CST_ENQUETE_READ       = 'read';
    public const CST_ENQUETE_WRITE      = 'write';
    // Subonglet Autopsie
    public const CST_AUTOPSIE_ARCHIVE   = 'archive';
    // Subonglet Index
    public const CST_CAT_SLUG           = 'catSlug';
    public const CST_CURPAGE            = 'curPage';
    // Subonglet Météo
    public const CST_WEATHER            = 'weather';
    public const CST_SUN                = 'sun';
    public const CST_MOON               = 'moon';

    public const CST_ACTION             = 'action';
    public const CST_CHECKED            = 'checked';
    public const CST_CHILDREN           = 'children';
    public const CST_COLSPAN            = 'colspan';
    public const CST_DATE               = 'date';
    public const CST_DISABLED           = 'disabled';
    public const CST_EDIT               = 'edit';
    public const CST_HOME               = 'home';
    public const CST_LIST               = 'list';
    public const CST_ONGLET             = 'onglet';
    public const CST_SELECTED           = 'selected';
    public const CST_SUBONGLET          = 'subOnglet';
    public const CST_TEXT_WHITE         = 'text-white';
    public const CST_URL                = 'url';
    public const CST_WICON              = 'wicon';
    public const CST_WRITE              = 'write';
    public const CST_WRITE_ACTION       = 'writeAction';

    /////////////////////////////////////////////////
    // Fields
    // TABLE multiples
    public const FIELD_ID               = 'id';
    public const FIELD_REFERENCE          = 'reference';

    // Provenance ?
    public const FIELD_ICON             = 'icon';
    public const FIELD_FOLDER_ID        = 'folderId';
    public const FIELD_FROM_ID          = 'fromId';
    public const FIELD_LABEL            = 'label';
    public const FIELD_LIBELLE          = 'libelle';
    public const FIELD_LU               = 'lu';
    public const FIELD_MAIL             = 'mail';
    public const FIELD_MAIL_ID          = 'mailId';
    public const FIELD_MAIL_CONTENT     = 'mail_content';
    public const FIELD_MAIL_DATE_ENVOI  = 'mail_dateEnvoi';
    public const FIELD_MAIL_SUBJECT     = 'mail_subject';
    public const FIELD_NB_PJS           = 'nbPjs';
    public const FIELD_SLUG             = 'slug';
    public const FIELD_TO_ID            = 'toId';

    // TABLE cops_player
    public const FIELD_MATRICULE          = 'matricule';
    public const FIELD_PASSWORD           = 'password';
    public const FIELD_NOM                = 'nom';
    public const FIELD_PRENOM             = 'prenom';
    public const FIELD_SURNOM             = 'surnom';
    public const FIELD_CARAC_CARRURE      = 'carac_carrure';
    public const FIELD_CARAC_CHARME       = 'carac_charme';
    public const FIELD_CARAC_COORDINATION = 'carac_coordination';
    public const FIELD_CARAC_EDUCATION    = 'carac_education';
    public const FIELD_CARAC_PERCEPTION   = 'carac_perception';
    public const FIELD_CARAC_REFLEXES     = 'carac_reflexes';
    public const FIELD_CARAC_SANG_FROID   = 'carac_sangfroid';
    public const FIELD_BIRTH_DATE         = 'birth_date';
    public const FIELD_PV_MAX             = 'pv_max';
    public const FIELD_PV_CUR             = 'pv_cur';
    public const FIELD_PAD_MAX            = 'pad_max';
    public const FIELD_PAD_CUR            = 'pad_cur';
    public const FIELD_PAN_MAX            = 'pan_max';
    public const FIELD_PAN_CUR            = 'pan_cur';
    public const FIELD_TAILLE             = 'taille';
    public const FIELD_POIDS              = 'poids';
    public const FIELD_GRADE              = 'grade';
    public const FIELD_GRADE_ECHELON      = 'grade_echelon';
    public const FIELD_GRADE_RANG         = 'grade_rang';
    public const FIELD_INTEGRATION_DATE   = 'integration_date';
    public const FIELD_SECTION            = 'section';
    public const FIELD_SECTION_LIEUTENANT = 'section_lieutenant';
    public const FIELD_BACKGROUND         = 'background';
    public const FIELD_PX_CUR             = 'px_cur';

    // TABLE cops_skill
    public const FIELD_SKILL_NAME         = 'skillName';
    public const FIELD_SKILL_DESC         = 'skillDescription';
    public const FIELD_SKILL_USES         = 'skillUses';
    public const FIELD_SPEC_LEVEL         = 'specLevel';
    public const FIELD_PAN_USABLE         = 'panUsable';
    public const FIELD_DEFAULT_ABILITY    = 'defaultAbility';

    // TABLE cops_skill_spec
    public const FIELD_SPEC_NAME          = 'specName';
    public const FIELD_SKILL_ID           = 'skillId';

    // TABLE cops_stage_categorie
    public const FIELD_STAGE_CAT_NAME     = 'stageCategorie';

    // TABLE cops_stage_spec
    public const FIELD_SPEC_DESC          = 'specDescription';

    // TABLE cops_stage
    public const FIELD_STAGE_CAT_ID       = 'stageCategorieId';
    public const FIELD_STAGE_LIBELLE      = 'stageLibelle';
    public const FIELD_STAGE_LEVEL        = 'stageNiveau';
    public const FIELD_STAGE_REFERENCE    = 'stageReference';
    public const FIELD_STAGE_REQUIS       = 'stagePreRequis';
    public const FIELD_STAGE_CUMUL        = 'stageCumul';
    public const FIELD_STAGE_DESC         = 'stageDescription';
    public const FIELD_STAGE_BONUS        = 'stageBonus';

    // TABLE cops_event_date
    public const FIELD_EVENT_LIBELLE      = 'eventLibelle';
    public const FIELD_CATEG_ID           = 'categorieId';
    public const FIELD_DATE_DEBUT         = 'dateDebut';
    public const FIELD_DATE_FIN           = 'dateFin';
    public const FIELD_ALL_DAY_EVENT      = 'allDayEvent';
    public const FIELD_HEURE_DEBUT        = 'heureDebut';
    public const FIELD_HEURE_FIN          = 'heureFin';
    public const FIELD_REPEAT_STATUS      = 'repeatStatus';
    public const FIELD_REPEAT_TYPE        = 'repeatType';
    public const FIELD_REPEAT_INTERVAL    = 'repeatInterval';
    public const FIELD_REPEAT_END         = 'repeatEnd';
    public const FIELD_REPEAT_END_VALUE   = 'repeatEndValue';
    public const FIELD_MINUTE_DEBUT         = 'minuteDebut';
    public const FIELD_MINUTE_FIN           = 'minuteFin';
    public const FIELD_ENDDATE_VALUE         = 'endDateValue';
    public const CST_EVENT_RT_NEVER        = 'never';
    public const CST_EVENT_RT_ENDDATE      = 'endDate';
    public const CST_EVENT_RT_ENDREPEAT    = 'endRepeat';
    public const CST_EVENT_RT_DAILY        = 'daily';
    public const CST_EVENT_RT_WEEKLY       = 'weekly';
    public const CST_EVENT_RT_MONTHLY      = 'monthly';
    public const CST_EVENT_RT_YEARLY       = 'yearly';
    
    // TABLE cops_event_date
    public const FIELD_EVENT_ID           = 'eventId';
    public const FIELD_DSTART             = 'dStart';
    public const FIELD_DEND               = 'dEnd';
    public const FIELD_TSTART             = 'tStart';
    public const FIELD_TEND               = 'tEnd';

    // TABLE cops_event_categorie
    public const FIELD_CATEG_LIBELLE      = 'categorieLibelle';
    public const FIELD_CATEG_COLOR        = 'categorieCouleur';

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

    // TABLE cops_autopsie
    public const FIELD_IDX_ENQUETE        = 'idxEnquete';
    public const FIELD_DATA               = 'data';

    // TABLE cops_index
    public const FIELD_ID_IDX           = 'idIdx';
    public const FIELD_REF_IDX_ID       = 'referenceIdxId';
    public const FIELD_TOME_IDX_ID      = 'tomeIdxId';
    public const FIELD_PAGE             = 'page';
    // TABLE cops_index_reference
    public const FIELD_ID_IDX_REF       = 'idIdxReference';
    public const FIELD_NOM_IDX          = 'nomIdxReference';
    public const FIELD_PRENOM_IDX       = 'prenomIdxReference';
    public const FIELD_AKA_IDX          = 'akaIdxReference';
    public const FIELD_NATURE_IDX_ID    = 'natureIdxId';
    public const FIELD_DESCRIPTION_PJ   = 'descriptionPJ';
    public const FIELD_DESCRIPTION_MJ   = 'descriptionMJ';
    public const FIELD_CODE             = 'code';
    // TABLE cops_index_nature
    public const FIELD_ID_IDX_NATURE    = 'idIdxNature';
    public const FIELD_NOM_IDX_NATURE   = 'nomIdxNature';
    // TABLE cops_index_tome
    public const FIELD_ID_IDX_TOME      = 'idIdxTome';
    public const FIELD_NOM_IDX_TOME     = 'nomIdxTome';
    public const FIELD_ABR_IDX_TOME     = 'abrIdxTome';

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
    // TABLE cops_soleil
    public const FIELD_DATE_SOLEIL      = 'dateSoleil';
    public const FIELD_HEURE_LEVER      = 'heureLever';
    public const FIELD_HEURE_COUCHER    = 'heureCoucher';
  
    /////////////////////////////////////////////////
    // Formats
    public const FORMAT_STRJOUR          = 'strJour';
    public const FORMAT_SIDEBAR_DATE     = 'strSbDown';
    public const FORMAT_TS_NOW           = 'tsnow';
    public const FORMAT_TS_START_DAY     = 'tsStart';
    public const FORMAT_DATE_HIS         = 'H:i:s';
    public const FORMAT_DATE_DMDY        = 'D m-d-Y';
    public const FORMAT_DATE_YMD         = 'Y-m-d';
    public const FORMAT_DATE_MDY         = 'm-d-Y';
    public const FORMAT_DATE_DMY         = 'd-m-Y';
    public const FORMAT_DATE_YMDHIS      = 'Y-m-d h:i:s';

    /////////////////////////////////////////////////
    // Icons
    public const I_ANGLE_LEFT            = 'angle-left';
    public const I_ANGLE_RIGHT           = 'angle-right';
    public const I_ANGLES_LEFT           = 'angles-left';
    public const I_ARROWS_ROTATE         = 'arrows-rotate';
    public const I_BACKWARD              = 'backward';
    public const I_CARET_LEFT           = 'caret-left';
    public const I_CARET_RIGHT          = 'caret-right';
    public const I_CIRCLE                = 'circle';
    public const I_DATABASE              = 'database';
    public const I_DELETE               = 'trash-can';
    public const I_DESKTOP              = 'desktop';
    public const I_DOWNLOAD             = 'download';
    public const I_EDIT                  = 'edit';
    public const I_FILE_CATEGORY         = 'folder-tree';
    public const I_FILE_CIRCLE_PLUS      = 'file-circle-plus';
    public const I_FILE_CIRCLE_CHECK     = 'file-circle-check';
    public const I_FILE_CIRCLE_XMARK     = 'file-circle-xmark';
    public const I_FILE_OPENED           = 'folder-open';
    public const I_FILE_CLOSED           = 'folder-closed';
    public const I_FILE_COLDED           = 'folder';
    public const I_FILTER_CIRCLE_XMARK  = 'filter-circle-xmark';
    public const I_GEAR                 = 'gear';
    public const I_REFRESH              = 'arrows-rotate';
    public const I_SQUARE_CHECK         = 'square-check';
    public const I_SQUARE_XMARK         = 'square-xmark';
    public const I_USERS                = 'users';

    /////////////////////////////////////////////////
    // Page d'administration
    public const PAGE_ADMIN              = 'admin';

    /////////////////////////////////////////////////
    // NAVigation
    public const NAV                    = 'nav';
    public const NAV_ITEM               = 'nav-item';
    public const NAV_LINK               = 'nav-link';

    /////////////////////////////////////////////////
    // Onglets
    public const ONGLET_ARCHIVE         = 'archive';
    public const ONGLET_AUTOPSIE        = 'autopsie';
    public const ONGLET_CALENDAR        = 'calendar';
    public const ONGLET_ENQUETE         = 'enquete';
    public const ONGLET_INBOX           = 'inbox';
    public const ONGLET_INDEX           = 'index';
    public const ONGLET_LIBRARY         = 'library';
    public const ONGLET_METEO           = 'meteo';
    public const ONGLET_PROFILE         = 'profile';
    public const ONGLET_DESK            = 'desk';

    /////////////////////////////////////////////////
    // Rôles
    public const ROLE_GUEST             = 1;
    public const ROLE_PJ                = 10;
    public const ROLE_MJ                = 100;
    public const ROLE_ADMIN             = 999;

    /////////////////////////////////////////////////
    // SQL
    public const SQL_JOKER_SEARCH       = '%';
    public const SQL_LIMIT              = 'limit';
    public const SQL_ORDER              = 'order';
    public const SQL_ORDER_ASC          = 'ASC';
    public const SQL_ORDER_BY           = 'orderby';
    public const SQL_ORDER_DESC         = 'DESC';
    public const SQL_WHERE_FILTERS      = 'whereFilters';

    /////////////////////////////////////////////////
    // Tags
    public const TAG_A                  = 'a';
    public const TAG_BUTTON             = 'button';
    public const TAG_DIV                = 'div';
    public const TAG_I                  = 'i';
    public const TAG_IMG                = 'img';
    public const TAG_INPUT              = 'input';
    public const TAG_LABEL              = 'label';
    public const TAG_LI                 = 'li';
    public const TAG_OPTION             = 'option';
    public const TAG_P                  = 'p';
    public const TAG_SELECT             = 'select';
    public const TAG_SPAN               = 'span';
    public const TAG_STRONG             = 'strong';
    public const TAG_TD                 = 'td';
    public const TAG_TH                 = 'th';
    public const TAG_TR                 = 'tr';
    public const TAG_UL                 = 'ul';

    /////////////////////////////////////////////////
    // Wordpress
    public const WP_CAT                 = 'cat';
    public const WP_CAT_ID_BDD          = 45;
    public const WP_CAT_ID_SKILL        = 47;
    public const WP_MENUORDER           = 'menu_order';
    public const WP_METAKEY             = 'meta_key';
    public const WP_METAVALUENUM        = 'meta_value_num';
    public const WP_NAME                = 'name';
    public const WP_POST                = 'post';
    public const WP_POSTCONTENT         = 'post_content';
    public const WP_POSTNAME            = 'post_name';
    public const WP_POSTSTATUS          = 'post_status';
    public const WP_POSTTITLE           = 'post_title';
    public const WP_POSTTYPE            = 'post_type';
    public const WP_POSTSPERPAGE        = 'posts_per_page';
    public const WP_PUBLISH             = 'publish';
    public const WP_SUPPRESS_FILTER     = 'suppress_filters';

    public const WP_CF_ADRENALINE       = 'adrenaline';
    public const WP_CF_CARACASSOCIEE    = 'caracteristique_associee';
    public const WP_CF_LSTSPECS         = 'liste_specialisation';
    public const WP_CF_ORDREDAFFICHAGE  = 'ordre_daffichage';
    public const WP_CF_SPECIALISATION   = 'specialisation';
    public const WP_CF_UTILITE          = 'utilite';

  /*
  const WP_CAT_ID_NEWS         = 2;
  const WP_COMPARE             = 'compare';
  const WP_CURPAGE             = 'cur_page';
  const WP_EVENT_DATE          = 'event_date';
  const WP_EVENT_PLACE         = 'event_place';
  const WP_FIELD               = 'field';
  const WP_KEY                 = 'key';
  const WP_METAQUERY           = 'meta_query';
  const WP_METAVALUE           = 'meta_value';
  const WP_NUMBERPOSTS         = 'numberposts';
  const WP_OFFSET              = 'offset';
  const WP_PAGE                = 'page';
  const WP_POST_ID_HOME        = 356;
  const WP_POSTDATE            = 'post_date';
  const WP_POSTTAG             = 'post_tag';
  const WP_SLIDE_ACTIVE        = 'active_slide';
  const WP_SLIDE_IMAGE         = 'slide_image';
  const WP_SLIDE_ORDER         = 'slide_order';
  const WP_SLIDE_URL           = 'slide_url';
  const WP_SLIDE_URL_AWAY      = 'slide_url_away';
  const WP_SLUG                = 'slug';
  const WP_TAXONOMY            = 'taxonomy';
  const WP_TAXQUERY            = 'tax_query';
  const WP_TERMS               = 'terms';
  const WP_VALUE               = 'value';

  /////////////////////////////////////////////////
  // Divers
  */
    public const CST_AMP          = '&amp;';
    public const CST_ANCHOR       = '#';
    public const CST_NBSP         = '&nbsp;';
    public const CSV_EOL          = "\r\n";
    public const CSV_SEP          = ';';
  
}
