<?php
/**
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.24
 */
interface UrlsInterface
{
    const WEB_PAGES_PUBLIC_FRAGMENTS = 'web/pages/public/fragments/';

    const WEB_PPF_ARTICLE = self::WEB_PAGES_PUBLIC_FRAGMENTS.'article/';
    const WEB_PPF_DIV = self::WEB_PAGES_PUBLIC_FRAGMENTS.'div/';
    const WEB_PPF_FORM = self::WEB_PAGES_PUBLIC_FRAGMENTS.'form/';
    const WEB_PPF_SECTION = self::WEB_PAGES_PUBLIC_FRAGMENTS.'section/';
    const WEB_PPF_TR = self::WEB_PAGES_PUBLIC_FRAGMENTS.'tr/';
    
    const WEB_PPFD_ALLDAY_EVENT = self::WEB_PPF_DIV.'public-fragments-div-calendar-allday-event.php';

    const PF_FORM_EVENT = self::WEB_PPF_FORM.'public-fragments-form-event.php';
    
    const PF_SECTION_ONGLET = self::WEB_PPF_SECTION.'public-fragments-section-onglet.php';
    const PF_SECTION_CALENDAR = self::WEB_PPF_SECTION.'public-fragments-section-calendar.php';
    const PF_SECTION_CAL_MONTH = self::WEB_PPF_SECTION.'public-fragments-section-calendar-month.php';
    const PF_SECTION_CAL_WEEK = self::WEB_PPF_SECTION.'public-fragments-section-calendar-week.php';
    const PF_SECTION_CAL_DAY = self::WEB_PPF_SECTION.'public-fragments-section-calendar-day.php';
    const PF_SECTION_CAL_EVENTS = self::WEB_PPF_SECTION.'public-fragments-section-calendar-events.php';
    
    const PF_TR_EVENT = self::WEB_PPF_TR.'public-fragments-tr-event-row.php';
}
