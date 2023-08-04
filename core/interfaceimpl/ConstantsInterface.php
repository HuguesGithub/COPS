<?php
namespace core\interfaceimpl;

/**
 * @author Hugues
 * @since v1.00.00
 * @version v1.23.08.05
 */
interface ConstantsInterface
{
    public const VERSION              = 'v1.23.08.05';

    /////////////////////////////////////////////////
    // Ajax Infos
    public const AJAX_ACTION            = 'ajaxAction';

    /////////////////////////////////////////////////
    // Tag's Attributes
    public const ATTR_ALT               = 'alt';
    public const ATTR_ARIA              = 'aria';
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
    public const ATTR_DESCRIBEDBY       = 'describedby';

    public const ATTR_TIME              = 'time';

    /////////////////////////////////////////////////
    // Bootstrap
    public const BTS_BTN_DARK           = 'btn-dark';
    public const BTS_BTN_DARK_DISABLED  = 'btn-dark disabled';
    public const BTS_BTN_PRIMARY        = 'btn btn-primary';
    public const BTS_BTN_GROUP_SM       = 'btn-group btn-group-sm';

    /////////////////////////////////////////////////
    // CSS
    public const CSS_FLOAT_LEFT         = 'float-start';
    public const CSS_FLOAT_RIGHT        = 'float-end';
    public const CSS_TEXT_END           = 'text-end';
    public const CSS_DISPLAY_NONE       = 'display:none;';
    public const CSS_COL                = ' col';
    public const CSS_COL_1              = ' col-1';
    public const CSS_COL_2              = ' col-2';

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
    // Subonglet Equipement
    public const CST_EQPT_WEAPON        = 'weapon';
    public const CST_EQPT_CAR           = 'car';
    public const CST_EQPT_OTHER         = 'autres';

    public const CST_ACTION             = 'action';
    public const CST_ADD                = 'add';
    public const CST_CHECKED            = 'checked';
    public const CST_CHANGE_DATE        = 'changeDate';
    public const CST_CHILDREN           = 'children';
    public const CST_COLSPAN            = 'colspan';
    public const CST_DATE               = 'date';
    public const CST_DELETE             = 'delete';
    public const CST_DISABLED           = 'disabled';
    public const CST_EDIT               = 'edit';
    public const CST_ENDDATE            = 'endDate';
    public const CST_FIRST_DATE         = '2030-01-01';
    public const CST_HOME               = 'home';
    public const CST_LAST_DATE          = '2036-12-31';
    public const CST_LIST               = 'list';
    public const CST_ONGLET             = 'onglet';
    public const CST_QUANTITY           = 'quantite';
    public const CST_READONLY           = 'readonly';
    public const CST_REQUIRED           = 'required';
    public const CST_ROWSPAN            = 'rowspan';
    public const CST_SELECTED           = 'selected';
    public const CST_STARTDATE          = 'startDate';
    public const CST_SUBONGLET          = 'subOnglet';
    public const CST_TEXT_WHITE         = 'text-white';
    public const CST_UNITE              = 'unite';
    public const CST_URL                = 'url';
    public const CST_WICON              = 'wicon';
    public const CST_WRITE              = 'write';
    public const CST_WRITE_ACTION       = 'writeAction';
    public const CST_CUSHION            = '-cushion';
    public const CST_FRAME              = '-frame';

    // Constantes de certains champs.
    public const CST_EVENT_RT_NEVER        = 'never';
    public const CST_EVENT_RT_ENDDATE      = 'endDate';
    public const CST_EVENT_RT_ENDREPEAT    = 'endRepeat';
    public const CST_EVENT_RT_DAILY        = 'daily';
    public const CST_EVENT_RT_WEEKLY       = 'weekly';
    public const CST_EVENT_RT_MONTHLY      = 'monthly';
    public const CST_EVENT_RT_YEARLY       = 'yearly';
    public const CST_EVENT_RT_CUSTOM       = 'custom';
  
    /////////////////////////////////////////////////
    // FC
    public const CST_FC_COL_HEADER_CELL    = 'fc-col-header-cell';
    public const CST_FC_COL_HEADER_CELL_CSH= self::CST_FC_COL_HEADER_CELL.self::CST_CUSHION;

    public const CST_FC_DAY                = 'fc-day';
    public const CST_FC_DAY_PAST           = self::CST_FC_DAY.'-past';
    public const CST_FC_DAY_TODAY          = self::CST_FC_DAY.'-today';
    public const CST_FC_DAY_FUTURE         = self::CST_FC_DAY.'-future';
    public const CST_FC_DAY_OTHER          = self::CST_FC_DAY.'-other';

    public const CST_FC_DAYGRID_DAY        = 'fc-daygrid-day';
    public const CST_FC_DAYGRID_DAY_BG     = self::CST_FC_DAYGRID_DAY.'-bg';
    public const CST_FC_DAYGRID_DAY_BTM    = self::CST_FC_DAYGRID_DAY.'-bottom';
    public const CST_FC_DAYGRID_DAY_EVENTS = self::CST_FC_DAYGRID_DAY.'-events';
    public const CST_FC_DAYGRID_DAY_FRAME  = self::CST_FC_DAYGRID_DAY.self::CST_FRAME;
    public const CST_FC_DAYGRID_DAY_NB     = self::CST_FC_DAYGRID_DAY.'-number';
    public const CST_FC_DAYGRID_DAY_TOP    = self::CST_FC_DAYGRID_DAY.'-top';
    public const CST_FC_DG_EVENT_HAR_ABS   = 'fc-daygrid-event-harness-abs';

    public const CST_FC_EVENT_END          = 'fc-event-end';
    public const CST_FC_EVENT_START        = 'fc-event-start';
    public const CST_FC_EVENT_TODAY        = 'fc-event-today';

    public const CST_FC_SCROLLGRID_SYNC_IN = 'fc-scrollgrid-sync-inner';
    public const CST_FC_SCROLLGRID_SHRINK  = 'fc-scrollgrid-shrink';
    public const CST_FC_SCROLLGRID_SHRINK_CUSHION  = self::CST_FC_SCROLLGRID_SHRINK.self::CST_CUSHION;
    public const CST_FC_SCROLLGRID_SHRINK_FRAME    = self::CST_FC_SCROLLGRID_SHRINK.self::CST_FRAME;

    public const CST_FC_TIMEGRID_COL       = 'fc-timegrid-col';
    public const CST_FC_TIMEGRID_COL_BG    = self::CST_FC_TIMEGRID_COL.'-bg';
    public const CST_FC_TIMEGRID_COL_EVENTS= self::CST_FC_TIMEGRID_COL.'-events';
    public const CST_FC_TIMEGRID_COL_FRAME = self::CST_FC_TIMEGRID_COL.self::CST_FRAME;
    public const CST_FC_TIMEGRID_NOW_IC    = 'fc-timegrid-now-indicator-container';
    public const CST_FC_TIMEGRID_SLOT      = 'fc-timegrid-slot';
    public const CST_FC_TIMEGRID_SLOT_LABEL= self::CST_FC_TIMEGRID_SLOT.' fc-timegrid-slot-label';
    public const CST_FC_TIMEGRID_SLOT_LABEL_CUSHION = self::CST_FC_TIMEGRID_SLOT_LABEL.self::CST_CUSHION;
    public const CST_FC_TIMEGRID_SLOT_LABEL_FRAME   = self::CST_FC_TIMEGRID_SLOT_LABEL.self::CST_FRAME;
    public const CST_FC_TIMEGRID_SLOT_LANE = self::CST_FC_TIMEGRID_SLOT.' fc-timegrid-slot-lane';
    public const CST_FC_TIMEGRID_SLOT_MINOR= self::CST_FC_TIMEGRID_SLOT.'-minor';

    public const CST_GRIDCELL              = 'gridcell';
    public const CST_COLUMNHEADER          = 'columnheader';

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
    public const FORMAT_DATE_DMONTHY     = 'd month Y';
    public const FORMAT_DATE_DMYHIS      = 'd M y H:i:s';
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
    public const I_HOUSE                = 'house';
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
    public const NAV_FILL               = 'nav-fill';
    public const NAV_ITEM               = 'nav-item';
    public const NAV_LINK               = 'nav-link';
    public const NAV_PILLS              = 'nav-pills';

    /////////////////////////////////////////////////
    // Onglets
    public const ONGLET_ARCHIVE         = 'archive';
    public const ONGLET_AUTOPSIE        = 'autopsie';
    public const ONGLET_CALENDAR        = 'calendar';
    public const ONGLET_DESK            = 'desk';
    public const ONGLET_ENQUETE         = 'enquete';
    public const ONGLET_EQUIPMENT       = 'equipment';
    public const ONGLET_INBOX           = 'inbox';
    public const ONGLET_INDEX           = 'index';
    public const ONGLET_LIBRARY         = 'library';
    public const ONGLET_METEO           = 'meteo';
    public const ONGLET_PROFILE         = 'profile';
    public const ONGLET_TCHAT           = 'tchat';
    public const ONGLET_USERS           = 'users';

    /////////////////////////////////////////////////
    // Pagination
    public const PAGE_OPT_SIMPLE        = 'simple';
    public const PAGE_OPT_NUMBERS       = 'numbers';
    public const PAGE_OPT_SMP_NMB       = 'simple_numbers';
    public const PAGE_OPT_FULL          = 'full';
    public const PAGE_OPT_FULL_NMB      = 'full_numbers';
    public const PAGE_OPT_FST_LAST_NMB  = 'first_last_numbers';
    public const PAGE_NBPERPAGE         = 'nbPerPage';
    public const PAGE_DEFAULT_NBPERPAGE = 10;
    public const PAGE_OPTION            = 'option';
    public const PAGE_OBJS              = 'objs';
    public const PAGE_QUERY_ARG         = 'queryArg';
    public const PAGE_PREVIOUS          = '&lsaquo;';
    public const PAGE_NEXT              = '&rsaquo;';
    public const PAGE_FIRST             = '&laquo;';
    public const PAGE_LAST              = '&raquo;';

    /////////////////////////////////////////////////
    // Rôles
    public const ROLE_GUEST             = 1;
    public const ROLE_PJ                = 10;
    public const ROLE_MJ                = 100;
    public const ROLE_ADMIN             = 999;

    /////////////////////////////////////////////////
    // STYLE / CLASS
    public const STYLE_TEXT_CENTER      = 'text-center';

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
    public const TAG_BUTTON             = 'button';
    public const TAG_IMG                = 'img';
    public const TAG_INPUT              = 'input';
    public const TAG_LABEL              = 'label';
    public const TAG_NAV                = 'nav';
    public const TAG_LI                 = 'li';
    public const TAG_P                  = 'p';
    public const TAG_SELECT             = 'select';
    public const TAG_SPAN               = 'span';
    public const TAG_STRONG             = 'strong';
    public const TAG_TH                 = 'th';
    public const TAG_THEAD              = 'thead';
    public const TAG_BODY               = 'body';
    public const TAG_TABLE              = 'table';
    public const TAG_TFOOT              = 'tfoot';
    public const TAG_TD                 = 'td';
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
    public const WP_PAGE                = 'page';
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

    /////////////////////////////////////////////////
    // Divers
    public const CST_AMP          = '&amp;';
    public const CST_ANCHOR       = '#';
    public const CST_NBSP         = '&nbsp;';
    public const CSV_EOL          = "\r\n";
    public const CSV_SEP          = ';';
  
}
