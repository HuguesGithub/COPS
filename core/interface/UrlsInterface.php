<?php
/**
 * @author Hugues
 * @since 1.22.11.21
 * @version 1.22.11.21
 */
interface UrlsInterface
{
    const WEB_PAGES_PUBLIC_FRAGMENTS = 'web/pages/public/fragments/';

    const WEB_PPF_ARTICLE = self::WEB_PAGES_PUBLIC_FRAGMENTS.'article/';
    const WEB_PPF_SECTION = self::WEB_PAGES_PUBLIC_FRAGMENTS.'section/';
    
    const PF_SECTION_ONGLET = self::WEB_PPF_SECTION.'public-fragments-section-onglet.php';
    const PF_SECTION_CALENDAR = self::WEB_PPF_SECTION.'public-fragments-section-calendar.php';
    const PF_SECTION_CAL_MONTH = self::WEB_PPF_SECTION.'public-fragments-section-calendar-month.php';
    const PF_SECTION_CAL_WEEK = self::WEB_PPF_SECTION.'public-fragments-section-calendar-week.php';
    const PF_SECTION_CAL_DAY = self::WEB_PPF_SECTION.'public-fragments-section-calendar-day.php';
}
