<?php
namespace core\interfaceimpl;

/**
 * @author Hugues
 * @since v1.22.11.21
 * @version v1.23.07.15
 */
interface UrlsInterface
{
    public const WEB_PAGES_ADMIN  = 'web/pages/admin/';
    public const WEB_PAGES_PUBLIC = 'web/pages/publique/';

    //////////////////////////////////////////////////////
    // Directories
    public const WEB_PA_FRAGMENTS = self::WEB_PAGES_ADMIN.'fragments/';
    public const WEB_PP_FRAGMENTS = self::WEB_PAGES_PUBLIC.'fragments/';
    public const WEB_PPF_ARTICLE  = self::WEB_PP_FRAGMENTS.'article/';
    public const WEB_PPF_DIV      = self::WEB_PP_FRAGMENTS.'div/';
    public const WEB_PPF_FORM     = self::WEB_PP_FRAGMENTS.'form/';
    public const WEB_PPF_SECTION  = self::WEB_PP_FRAGMENTS.'section/';

    public const WEB_LOG          = 'web/logs/';
    public const WEB_LOG_REQUEST  = 'web/logs/requests.log';
    
    // Files
    public const WEB_PA_INDEX               = self::WEB_PAGES_ADMIN.'page-admin-index.tpl';
    public const WEB_PA_DEFAULT             = self::WEB_PAGES_ADMIN.'page-admin-default.tpl';
    public const WEB_PA_METEO_HOME          = self::WEB_PAGES_ADMIN.'page-admin-meteo-home.tpl';
    public const WEB_PA_METEO_METEO         = self::WEB_PAGES_ADMIN.'page-admin-meteo-meteo.tpl';
    public const WEB_PA_METEO_MOON          = self::WEB_PAGES_ADMIN.'page-admin-meteo-moon.tpl';
    public const WEB_PA_METEO_SUN           = self::WEB_PAGES_ADMIN.'page-admin-meteo-soleil.tpl';
    public const WEB_PA_CALENDAR_HOME       = self::WEB_PAGES_ADMIN.'page-admin-calendar-home.tpl';
    public const WEB_PA_CALENDAR_DELETE     = self::WEB_PAGES_ADMIN.'page-admin-calendar-delete.tpl';
    public const WEB_PA_CALENDAR_EVENT_EDIT = self::WEB_PAGES_ADMIN.'page-admin-calendar-event-edit.tpl';
    public const WEB_PA_EQPT_WEAPON_EDIT    = self::WEB_PAGES_ADMIN.'page-admin-equipment-weapon-edit.tpl';
    public const WEB_PA_EQUIPMENT_HOME      = self::WEB_PAGES_ADMIN.'page-admin-equipment-home.tpl';
    public const WEB_PA_EQUIPMENT_WEAPON    = self::WEB_PAGES_ADMIN.'page-admin-equipment-weapon.tpl';
    public const WEB_PA_CAL_EVT_EDIT_CATEG  = self::WEB_PA_FRAGMENTS.'page-admin-fragments-edit-categ-block.tpl';
    public const WEB_PA_CAL_EVT_EDIT_DATE   = self::WEB_PA_FRAGMENTS.'page-admin-fragments-edit-date-block.tpl';
    public const WEB_PA_CAL_EVT_EDIT_REPTYPE= self::WEB_PA_FRAGMENTS.'page-admin-fragments-edit-period-block.tpl';
    public const WEB_PA_CAL_EVT_EDIT_REPINT = self::WEB_PA_FRAGMENTS.'page-admin-fragments-edit-recursive-block.tpl';
    public const WEB_PA_CAL_EVT_EDIT_CUSTOM = self::WEB_PA_FRAGMENTS.'page-admin-fragments-edit-custom-block.tpl';
    public const WEB_PA_CALENDAR_EVENT_LIST = self::WEB_PAGES_ADMIN.'page-admin-calendar-events-list.tpl';
    public const WEB_PA_PROFILE_USER_LIST   = self::WEB_PAGES_ADMIN.'page-admin-profile-users-list.tpl';
    public const WEB_PAF_PAGINATION         = self::WEB_PA_FRAGMENTS.'page-admin-fragments-pagination.tpl';
    public const WEB_PAF_DEFAULT_LIST       = self::WEB_PA_FRAGMENTS.'page-admin-fragments-default-list.tpl';
    public const WEB_PAFS_CALENDAR          = self::WEB_PA_FRAGMENTS.'page-admin-fragments-section-calendar.tpl';
    public const WEB_PAFT_EVENT_ROW         = self::WEB_PA_FRAGMENTS.'page-admin-fragments-tr-event-row.tpl';

    public const WEB_PP_BOARD               = self::WEB_PAGES_PUBLIC.'publique-board.tpl';
    public const WEB_PP_HOME_CONTENT        = self::WEB_PAGES_PUBLIC.'publique-home-content.tpl';
    public const WEB_PP_MAIN_FOOTER         = self::WEB_PAGES_PUBLIC.'publique-main-footer.tpl';
    public const WEB_PPF_SIDEBAR            = self::WEB_PP_FRAGMENTS.'publique-fragments-sidebar.tpl';
    public const WEB_PPFS_ONGLET_MENU_PANEL = self::WEB_PPF_ARTICLE .'publique-fragments-article-onglet-menu-panel.tpl';
    public const WEB_PPFS_BDD_ENTETE        = self::WEB_PPF_ARTICLE .'publique-fragments-article-bdd-entete.tpl';
    public const WEB_PPFA_BDD               = self::WEB_PPF_ARTICLE .'publique-fragments-article-library-bdd.tpl';
    public const WEB_PPFA_LIB_COURSE        = self::WEB_PPF_ARTICLE .'publique-fragments-article-library-stage.tpl';
    public const WEB_PPFA_LIB_COURSE_CATEG  = self::WEB_PPF_ARTICLE .'publique-fragments-article-library-stage-categorie.tpl';
    public const WEB_PPFA_LIB_SKILL         = self::WEB_PPF_ARTICLE .'publique-fragments-article-library-skill.tpl';
    public const WEB_PPFD_ALLDAY_EVENT      = self::WEB_PPF_DIV     .'publique-fragments-div-calendar-allday-event.tpl';
    public const WEB_PPFD_DOT_EVENT         = self::WEB_PPF_DIV     .'publique-fragments-div-calendar-dot-event.tpl';
    public const WEB_PPFD_INSET_EVENT       = self::WEB_PPF_DIV     .'publique-fragments-div-calendar-inset-event.tpl';
    public const WEB_PPFD_PFL_ABILITY       = self::WEB_PPF_DIV     .'publique-fragments-div-profile-ability.tpl';
    public const WEB_PPFD_PFL_SKILL         = self::WEB_PPF_DIV     .'publique-fragments-div-profile-skill.tpl';
    public const WEB_PPFD_PFL_ID_GRADE      = self::WEB_PPF_DIV     .'publique-fragments-div-profile-identity-grade.tpl';
    public const WEB_PPFD_PFL_ID_NAME       = self::WEB_PPF_DIV     .'publique-fragments-div-profile-identity-name.tpl';
    public const WEB_PPFD_PFL_ID_PHYSIQUE   = self::WEB_PPF_DIV     .'publique-fragments-div-profile-identity-physique.tpl';
    public const WEB_PPFF_LIBRARY_INDEX     = self::WEB_PPF_FORM    .'publique-fragments-form-library-index.tpl';
    public const WEB_PPFS_CALENDAR          = self::WEB_PPF_SECTION .'publique-fragments-section-calendar.tpl';
    public const WEB_PPFS_CAL_DAY           = self::WEB_PPF_SECTION .'publique-fragments-section-calendar-day.tpl';
    public const WEB_PPFS_CAL_MONTH         = self::WEB_PPF_SECTION .'publique-fragments-section-calendar-month.tpl';
    public const WEB_PPFS_CAL_WEEK          = self::WEB_PPF_SECTION .'publique-fragments-section-calendar-week.tpl';
    public const WEB_PPFS_CONNEX_PANEL      = self::WEB_PPF_SECTION .'publique-fragments-section-connexion-panel.tpl';
    public const WEB_PPFS_CONTENT_FOOTER    = self::WEB_PPF_SECTION .'publique-fragments-section-content-footer.tpl';
    public const WEB_PPFS_CONTENT_HEADER    = self::WEB_PPF_SECTION .'publique-fragments-section-content-header.tpl';
    public const WEB_PPFS_CONTENT_NAVBAR    = self::WEB_PPF_SECTION .'publique-fragments-section-content-navigation-bar.tpl';
    public const WEB_PPFS_LIB_COURSES       = self::WEB_PPF_SECTION .'publique-fragments-section-library-courses.tpl';
    public const WEB_PPFS_LIB_SKILLS        = self::WEB_PPF_SECTION .'publique-fragments-section-library-skills.tpl';
    public const WEB_PPFS_ONGLET            = self::WEB_PPF_SECTION .'publique-fragments-section-onglet.tpl';
    public const WEB_PPFS_ONGLET_LIST       = self::WEB_PPF_SECTION .'publique-fragments-section-onglet-list.tpl';
    public const WEB_PPFS_PFL_ABILITIES     = self::WEB_PPF_SECTION .'publique-fragments-section-profile-abilities.tpl';
    public const WEB_PPFS_PFL_IDENTITY      = self::WEB_PPF_SECTION .'publique-fragments-section-profile-identity.tpl';
    public const WEB_PPFS_PFL_SKILLS        = self::WEB_PPF_SECTION .'publique-fragments-section-profile-skills.tpl';
    
}
