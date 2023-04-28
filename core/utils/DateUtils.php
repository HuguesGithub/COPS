<?php
namespace core\utils;

use core\interfaceimpl\ConstantsInterface;

/**
 * DateUtils
 * @author Hugues
 * @since 1.23.04.27
 * @version v1.23.04.30
 */
class DateUtils implements ConstantsInterface
{
    public static $arrFullMonths = [
        1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    public static $arrShortMonths = [
        1=>'Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin',
        'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'
    ];
    public static $arrFullDays = [0=>'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    public static $arrShortDays = [0=>'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'];

    public static $arrFullEnglishDays = [
        0=>'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    ];
    public static $arrShortEnglishDays = [0=>'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    /**
     * @param string
     * @return string
     */
    public static function getCopsDate(string $format): string
    {
        $strCopsDate = get_option(self::CST_CAL_COPSDATE);
        $h = substr((string) $strCopsDate, 0, 2);
        $i = substr((string) $strCopsDate, 3, 2);
        $s = substr((string) $strCopsDate, 6, 2);
        $his = substr((string) $strCopsDate, 0, 8);
        $d = substr((string) $strCopsDate, 9, 2);
        $m = substr((string) $strCopsDate, 12, 2);
        $y = substr((string) $strCopsDate, 15);
        $dmy = substr((string) $strCopsDate, 9);
        $tsCops = mktime($h, $i, $s, $m, $d, $y);

        switch ($format) {
            case self::FORMAT_STRJOUR :
                $strJour = self::arrFullDays[date('N', $tsCops)];
                $attributes = [$strJour, $d, self::arrFullMonths[$m*1], $y];
                $formatted = implode(' ', $attributes);
                break;
            case self::FORMAT_SIDEBAR_DATE :
                $formatted = self::arrShortDays[date('N', $tsCops)].' '.$dmy.'<br>'.$his;
                break;
            case self::FORMAT_TS_NOW :
                $formatted = mktime($h, $i, $s, $m, $d, $y);
                break;
            case self::FORMAT_TS_START_DAY :
                $formatted = mktime(0, 0, 0, $m, $d, $y);
                break;
            case self::FORMAT_DATE_HIS    :
            case self::FORMAT_DATE_DMDY   :
            case self::FORMAT_DATE_YMD    :
            case self::FORMAT_DATE_MDY    :
            case self::FORMAT_DATE_DMY    :
            case self::FORMAT_DATE_YMDHIS :
                $formatted = date($format, mktime($h, $i, $s, $m, $d, $y));
                break;
            default :
                $formatted = $format;
                break;
        }
        return $formatted;
    }

    /**
     * @since 1.23.04.27
     * @version v1.23.04.30
     */
    public static function setCopsDate(int $tsNow): void
    {
        update_option(self::CST_CAL_COPSDATE, date('h:i:s d/m/Y', $tsNow));
    }

    /**
     * Retourne au format donné le date obtenue en ajoutant $nbJours à la date passée.
     * $tmpArray attend les informations comme ça : [$nbJours, $nbMois, $nbAns]
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public static function getDateAjout(
        string $strDate,
        array $tmpArray,
        string $dateFormat
    ): string
    {
        [$nbJours, $nbMois, $nbAns] = $tmpArray;
        [$d, $m, $y] = static::parseDate($strDate);
        return date($dateFormat, mktime(0, 0, 0, $m+$nbMois, $d+$nbJours, $y+$nbAns));
    }

    /**
     * v1.23.04.28
     * v1.23.04.30
     */
    public static function getStrDate(string $strFormat, string|int $when): string
    {
        // On détermine si $when est string ou int.
        // Si int, c'est un timestamp.
        // Si string, c'est une chaine de type YYYY-mm-dd HH:ii:ss ou autre.
        [$d, $m, $y, $h, $i, $s] = static::parseDate($when);
        if ($s<10) {
            $s = str_pad($s, 2, '0', STR_PAD_LEFT);
        }

        // Une fois déterminé $when, on doit avoir une chaine au format YYYY-mm-dd HH:ii:ss.
        // On peut alors traiter la donnée pour retourner l'info selon le format souhaité.

        switch ($strFormat) {
            case 'd M y' :
                $strFormatted = $d.' '.static::$arrShortMonths[$m*1].' '.$y;
            break;
            case 'd M y H:i:s' :
                $strFormatted = $d.' '.static::$arrShortMonths[$m*1].' '.$y.' '.$h.':'.$i.':'.$s;
            break;
            default :
                $strFormatted = '';
            break;
        }
        return $strFormatted;
    }
    
    /**
     * Retourne au format donné le premier jour de la semaine de la date passée.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public static function getDateStartWeek(string $strDate, string $dateFormat): string
    {
        [$d, $m, $y, , ,] = static::parseDate($strDate);
        $n = date('N', mktime(0, 0, 0, $m, $d, $y));
        return static::getDateAjout($strDate, [$n-1, 0, 0], $dateFormat);
    }

    /**
     * Retourne un tableau [$d, $m, $y, $h, $i, $s] de la date passée.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public static function parseDate(string $strDate): array
    {
        // On attend un des formats ci-dessous
        // YYYY-MM-DD
        $patternDate = "/^(\d{4})-(\d{2})-(\d{2})$/";
        // HH:II:SS - Note : ":SS" peut ne pas être présent
        $patternHour = "/^(\d{2}):(\d{2}):?(\d{2})?$/";
        // YYYY-MM-DD HH:II:SS - Note : ":SS" peut ne pas être présent
        $patternBoth = "/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):?(\d{2})?$/";

        if (preg_match($patternDate, $strDate, $matches)) {
            $arrParsed = [$matches[3], $matches[2], $matches[1], 0, 0, 0];
        } elseif (preg_match($patternHour, $strDate, $matches)) {
            $arrParsed = [0, 0, 0, $matches[1], $matches[2], $matches[3] ?? 0];
        } elseif (preg_match($patternBoth, $strDate, $matches)) {
            $arrParsed = [$matches[3], $matches[2], $matches[1], $matches[4], $matches[5], $matches[6] ?? 0];
        } else {
            $arrParsed = [0, 0, 0, 0, 0, 0];
        }
        return $arrParsed;
    }
}
