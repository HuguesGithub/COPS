<?php
/**
 * @author Hugues
 * @since 1.00.00
 * @version 1.22.09.21
 */
interface ConstantsInterface
{
    const VERSION              = 'v1.22.10.09';
  /////////////////////////////////////////////////
  // Ajax Infos
  const AJAX_ACTION            = 'ajaxAction';

  /////////////////////////////////////////////////
  // Tag's Attributes
  const ATTR_ALT               = 'alt';
  const ATTR_CLASS             = 'class';
  const ATTR_HREF              = 'href';
  const ATTR_SRC               = 'src';
  const ATTR_STYLE             = 'style';
  const ATTR_TITLE             = 'title';
  const ATTR_VALUE             = 'value';

  const ATTR_DATA              = 'data';
  const ATTR_DATA_AJAX         = 'ajax';
  const ATTR_DATA_DATE         = 'data-date';
  const ATTR_DATA_TRIGGER      = 'trigger';

  /////////////////////////////////////////////////
  // Constantes
  const CST_ACTIVE             = 'active';
  // Subonglet Calendar
  const CST_CAL_COPSDATE       = 'cops_date';
  const CST_CAL_CURDAY         = 'curday';
  const CST_CAL_DAY            = 'day';
  const CST_CAL_EVENT          = 'calendar-event';
  const CST_CAL_MONTH          = 'month';
  const CST_CAL_PARAM          = 'calendar-parameters';
  const CST_CAL_WEEK           = 'week';
  // Subonglet File
  const CST_FILE_OPENED        = 'opened';
  const CST_FILE_CLOSED        = 'closed';
  const CST_FILE_COLDED        = 'colded';
  // Subonglet Inbox
  const CST_FOLDER_ALERT       = 'alert';
  const CST_FOLDER_DRAFT       = 'draft';
  const CST_FOLDER_EVENTS      = 'events';
  const CST_FOLDER_INBOX       = 'inbox';
  const CST_FOLDER_SENT        = 'sent';
  const CST_FOLDER_SPAM        = 'spam';
  const CST_FOLDER_TRASH       = 'trash';
  const CST_FOLDER_READ        = 'read';
  const CST_FOLDER_WRITE       = 'write';
  // Subonglet Library
  const CST_LIB_INDEX          = 'index';
  const CST_LIB_BDD            = 'bdd';
  const CST_LIB_COPS           = 'cops';
  const CST_LIB_LAPD           = 'lapd';
  const CST_LIB_SKILL          = 'skill';
  const CST_LIB_STAGE          = 'stage';
  // Subonglet Profile
  const CST_PFL_ABILITIES      = 'abilities';
  const CST_PFL_BACKGROUND     = 'background';
  const CST_PFL_CONTACTS       = 'contacts';
  const CST_PFL_EQUIPMENT      = 'equipment';
  const CST_PFL_IDENTITY       = 'identity';
  const CST_PFL_SKILLS         = 'skills';
  // Subonglet Enquête
  const CST_ENQUETE_OPENED     = 1;
  const CST_ENQUETE_CLOSED     = 2;
  const CST_ENQUETE_COLDED     = 0;
  const CST_ENQUETE_READ       = 'read';
  const CST_ENQUETE_WRITE      = 'write';
  // Subonglet Autopsie
  const CST_AUTOPSIE_ARCHIVE   = 'archive';
  // Subonglet Index
  const CST_CAT_SLUG           = 'catSlug';
  const CST_CURPAGE            = 'curPage';
  
  const CST_ACTION             = 'action';
  const CST_CHECKED            = 'checked';
  const CST_CHILDREN           = 'children';
  const CST_DISABLED           = 'disabled';
  const CST_EDIT               = 'edit';
  const CST_LIST               = 'list';
  const CST_ONGLET             = 'onglet';
  const CST_SELECTED           = 'selected';
  const CST_SUBONGLET          = 'subOnglet';
  const CST_TEXT_WHITE         = 'text-white';
  const CST_URL                = 'url';
  const CST_WRITE              = 'write';
  const CST_WRITE_ACTION       = 'writeAction';
  
  /////////////////////////////////////////////////
  // Fields
  // TABLE multiples
  const FIELD_ID               = 'id';
  const FIELD_REFERENCE          = 'reference';
  
  // Provenance ?
  const FIELD_ICON             = 'icon';
  const FIELD_FOLDER_ID        = 'folderId';
  const FIELD_FROM_ID          = 'fromId';
  const FIELD_LABEL            = 'label';
  const FIELD_LIBELLE          = 'libelle';
  const FIELD_LU               = 'lu';
  const FIELD_MAIL             = 'mail';
  const FIELD_MAIL_ID          = 'mailId';
  const FIELD_MAIL_CONTENT     = 'mail_content';
  const FIELD_MAIL_DATE_ENVOI  = 'mail_dateEnvoi';
  const FIELD_MAIL_SUBJECT     = 'mail_subject';
  const FIELD_NB_PJS           = 'nbPjs';
  const FIELD_SLUG             = 'slug';
  const FIELD_TO_ID            = 'toId';

  // TABLE cops_player
  const FIELD_MATRICULE          = 'matricule';
  const FIELD_PASSWORD           = 'password';
  const FIELD_NOM                = 'nom';
  const FIELD_PRENOM             = 'prenom';
  const FIELD_SURNOM             = 'surnom';
  const FIELD_CARAC_CARRURE      = 'carac_carrure';
  const FIELD_CARAC_CHARME       = 'carac_charme';
  const FIELD_CARAC_COORDINATION = 'carac_coordination';
  const FIELD_CARAC_EDUCATION    = 'carac_education';
  const FIELD_CARAC_PERCEPTION   = 'carac_perception';
  const FIELD_CARAC_REFLEXES     = 'carac_reflexes';
  const FIELD_CARAC_SANG_FROID   = 'carac_sangfroid';
  const FIELD_BIRTH_DATE         = 'birth_date';
  const FIELD_PV_MAX             = 'pv_max';
  const FIELD_PV_CUR             = 'pv_cur';
  const FIELD_PAD_MAX            = 'pad_max';
  const FIELD_PAD_CUR            = 'pad_cur';
  const FIELD_PAN_MAX            = 'pan_max';
  const FIELD_PAN_CUR            = 'pan_cur';
  const FIELD_TAILLE             = 'taille';
  const FIELD_POIDS              = 'poids';
  const FIELD_GRADE              = 'grade';
  const FIELD_GRADE_ECHELON      = 'grade_echelon';
  const FIELD_GRADE_RANG         = 'grade_rang';
  const FIELD_INTEGRATION_DATE   = 'integration_date';
  const FIELD_SECTION            = 'section';
  const FIELD_SECTION_LIEUTENANT = 'section_lieutenant';
  const FIELD_BACKGROUND         = 'background';
  const FIELD_PX_CUR             = 'px_cur';

  // TABLE cops_skill
  const FIELD_SKILL_NAME         = 'skillName';
  const FIELD_SKILL_DESC         = 'skillDescription';
  const FIELD_SKILL_USES         = 'skillUses';
  const FIELD_SPEC_LEVEL         = 'specLevel';
  const FIELD_PAD_USABLE         = 'padUsable';
  const FIELD_DEFAULT_ABILITY    = 'defaultAbility';

  // TABLE cops_skill_spec
  const FIELD_SPEC_NAME          = 'specName';
  const FIELD_SKILL_ID           = 'skillId';

  // TABLE cops_stage_categorie
  const FIELD_STAGE_CAT_NAME     = 'stageCategorie';

  // TABLE cops_stage_spec
  const FIELD_SPEC_DESC          = 'specDescription';

  // TABLE cops_stage
  const FIELD_STAGE_CAT_ID       = 'stageCategorieId';
  const FIELD_STAGE_LIBELLE      = 'stageLibelle';
  const FIELD_STAGE_LEVEL        = 'stageNiveau';
  const FIELD_STAGE_REFERENCE    = 'stageReference';
  const FIELD_STAGE_REQUIS       = 'stagePreRequis';
  const FIELD_STAGE_CUMUL        = 'stageCumul';
  const FIELD_STAGE_DESC         = 'stageDescription';
  const FIELD_STAGE_BONUS        = 'stageBonus';

  // TABLE cops_event_date
  const FIELD_EVENT_LIBELLE      = 'eventLibelle';
  const FIELD_CATEG_ID           = 'categorieId';
  const FIELD_DATE_DEBUT         = 'dateDebut';
  const FIELD_DATE_FIN           = 'dateFin';
  const FIELD_ALL_DAY_EVENT      = 'allDayEvent';
  const FIELD_HEURE_DEBUT        = 'heureDebut';
  const FIELD_HEURE_FIN          = 'heureFin';
  const FIELD_REPEAT_STATUS      = 'repeatStatus';
  const FIELD_REPEAT_TYPE        = 'repeatType';
  const FIELD_REPEAT_INTERVAL    = 'repeatInterval';
  const FIELD_REPEAT_END         = 'repeatEnd';
  const FIELD_REPEAT_END_VALUE   = 'repeatEndValue';

  // TABLE cops_event_date
  const FIELD_EVENT_ID           = 'eventId';
  const FIELD_DSTART             = 'dStart';
  const FIELD_DEND               = 'dEnd';
  const FIELD_TSTART             = 'tStart';
  const FIELD_TEND               = 'tEnd';

  // TABLE cops_event_categorie
  const FIELD_CATEG_LIBELLE      = 'categorieLibelle';
  const FIELD_CATEG_COLOR        = 'categorieCouleur';
  
  // TABLE cops_enquete
  const FIELD_NOM_ENQUETE        = 'nomEnquete';
  const FIELD_IDX_ENQUETEUR      = 'idxEnqueteur';
  const FIELD_IDX_DISTRICT_ATT   = 'idxDistrictAttorney';
  const FIELD_RESUME_FAITS       = 'resumeFaits';
  const FIELD_DESC_SCENE_CRIME   = 'descSceneDeCrime';
  const FIELD_PISTES_DEMARCHES   = 'pistesDemarches';
  const FIELD_NOTES_DIVERSES     = 'notesDiverses';
  const FIELD_STATUT_ENQUETE     = 'statutEnquete';
  const FIELD_DLAST              = 'dLast';

  // TABLE cops_autopsie
  const FIELD_IDX_ENQUETE        = 'idxEnquete';
  const FIELD_DATA               = 'data';

  // TABLE cops_index
  const FIELD_NOM_IDX            = 'nomIdx';
  const FIELD_NATURE_ID          = 'natureId';
  const FIELD_DESCRIPTION_PJ     = 'descriptionPJ';
  const FIELD_DESCRIPTION_MJ     = 'descriptionMJ';
  const FIELD_CODE               = 'code';
  // TABLE cops_index_nature
  const FIELD_ID_IDX_NATURE      = 'idIdxNature';
  const FIELD_NOM_IDX_NATURE     = 'nomIdxNature';
  
  /////////////////////////////////////////////////
  // Icons
  const I_CIRCLE                = 'circle';
  const I_EDIT                  = 'edit';
  const I_BACKWARD              = 'backward';
  const I_DATABASE              = 'database';
  const I_FILE_CATEGORY         = 'folder-tree';
  const I_FILE_CIRCLE_PLUS      = 'file-circle-plus';
  const I_FILE_CIRCLE_CHECK     = 'file-circle-check';
  const I_FILE_CIRCLE_XMARK     = 'file-circle-xmark';
  const I_FILE_OPENED           = 'folder-open';
  const I_FILE_CLOSED           = 'folder-closed';
  const I_FILE_COLDED           = 'folder';
  const I_ANGLE_LEFT            = 'angle-left';
  const I_ANGLE_RIGHT           = 'angle-right';
  
  /////////////////////////////////////////////////
  // Page d'administration
  const PAGE_ADMIN              = 'admin';
  
  /////////////////////////////////////////////////
  // Onglets
  const ONGLET_ARCHIVE         = 'archive';
  const ONGLET_AUTOPSIE        = 'autopsie';
  const ONGLET_CALENDAR        = 'calendar';
  const ONGLET_ENQUETE         = 'enquete';
  const ONGLET_INBOX           = 'inbox';
  const ONGLET_LIBRARY         = 'library';
  const ONGLET_PROFILE         = 'profile';
  const ONGLET_DESK            = 'desk';

  /////////////////////////////////////////////////
  // Rôles
  const ROLE_GUEST             = 1;
  const ROLE_PJ                = 10;
  const ROLE_MJ                = 100;
  const ROLE_ADMIN             = 999;

  /////////////////////////////////////////////////
  // SQL
  const SQL_JOKER_SEARCH       = '%';
  const SQL_LIMIT              = 'limit';
  const SQL_ORDER              = 'order';
  const SQL_ORDER_ASC          = 'ASC';
  const SQL_ORDER_BY           = 'orderby';
  const SQL_ORDER_DESC         = 'DESC';
  const SQL_WHERE_FILTERS      = 'whereFilters';

  /////////////////////////////////////////////////
  // Tags
  const TAG_A                  = 'a';
  const TAG_BUTTON             = 'button';
  const TAG_DIV                = 'div';
  const TAG_I                  = 'i';
  const TAG_IMG                = 'img';
  const TAG_LI                 = 'li';
  const TAG_OPTION             = 'option';
  const TAG_P                  = 'p';
  const TAG_SELECT             = 'select';
  const TAG_SPAN               = 'span';
  const TAG_STRONG             = 'strong';
  const TAG_TD                 = 'td';
  const TAG_TH                 = 'th';
  const TAG_TR                 = 'tr';
  const TAG_UL                 = 'ul';
  
  /////////////////////////////////////////////////
  // Wordpress
  const WP_CAT                 = 'cat';
  const WP_CAT_ID_BDD          = 45;
  const WP_CAT_ID_SKILL        = 47;
  const WP_METAKEY             = 'meta_key';
  const WP_METAVALUENUM        = 'meta_value_num';
  const WP_POSTCONTENT         = 'post_content';
  const WP_POSTNAME            = 'post_name';
  const WP_POSTTITLE           = 'post_title';

  const WP_CF_ADRENALINE       = 'adrenaline';
  const WP_CF_CARACASSOCIEE    = 'caracteristique_associee';
  const WP_CF_LSTSPECS         = 'liste_specialisation';
  const WP_CF_ORDREDAFFICHAGE  = 'ordre_daffichage';
  const WP_CF_SPECIALISATION   = 'specialisation';
  const WP_CF_UTILITE          = 'utilite';

  /*
  const WP_CAT_ID_NEWS         = 2;
  const WP_COMPARE             = 'compare';
  const WP_CURPAGE             = 'cur_page';
  const WP_EVENT_DATE          = 'event_date';
  const WP_EVENT_PLACE         = 'event_place';
  const WP_FIELD               = 'field';
  const WP_KEY                 = 'key';
  const WP_MENUORDER           = 'menu_order';
  const WP_METAQUERY           = 'meta_query';
  const WP_METAVALUE           = 'meta_value';
  const WP_NUMBERPOSTS         = 'numberposts';
  const WP_OFFSET              = 'offset';
  const WP_PAGE                = 'page';
  const WP_POST                = 'post';
  const WP_POST_ID_HOME        = 356;
  const WP_POSTDATE            = 'post_date';
  const WP_POSTSPERPAGE        = 'posts_per_page';
  const WP_POSTSTATUS          = 'post_status';
  const WP_POSTTAG             = 'post_tag';
  const WP_POSTTYPE            = 'post_type';
  const WP_PUBLISH             = 'publish';
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
  const CST_AMP          = '&amp;';
  const CST_NBSP         = '&nbsp;';
  const CSV_EOL          = "\r\n";
  const CSV_SEP          = ';';
  
}
