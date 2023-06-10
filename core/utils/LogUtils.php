<?php
namespace core\utils;

use core\interfaceimpl\UrlsInterface;

/**
 * LogUtils
 * @author Hugues
 * @since v1.23.05.31
 * @version v1.23.06.11
 */
class LogUtils implements UrlsInterface
{
    /**
     * @since v1.23.06.06
     * @version v1.23.06.11
     */
    public static function logPurge(): void
    {
        // On récupère le nom de base
        $dirName = PLUGIN_PATH.self::WEB_LOG;
        $olderName = 'requests_'.date('Ymd', time()-14*24*60*60).'.log';

        $files = scandir($dirName, SCANDIR_SORT_ASCENDING);
        while (!empty($files)) {
            $file = array_shift($files);
            if ($file=='.' || $file=='..') {
                continue;
            }

            if (strcmp($file, $olderName)>=0) {
                break;
            }
            unlink($dirName.$file);
        }
    }


    /**
     * On enregistre les requêtes effectuées dans ce fichier.
     * Si le fichier est trop volumineux, on va en créer un nouveau.
     * Chaque fichier est suffixé par _YYYYMMDD(_xx).log où xx est le numéro de version sur 2 chiffres.
     * @since v1.23.05.31
     * @version v1.23.06.11
     */
    public static function logRequest(string $prepRequest): void
    {
        static::logPurge();

        // On récupère le nom de base et on le suffixe avec la date du jour
        $baseName = PLUGIN_PATH.self::WEB_LOG_REQUEST;
        $todayBaseName = substr($baseName, 0, -4).'_'.date('Ymd');

        // On va créer le nom de la prochaine archive au cas où l'actuelle serait trop volumineuse.
        $cpt = 1;
        while (true) {
            $todayNextArchive = $todayBaseName.'_'.str_pad($cpt, 2, '0', STR_PAD_LEFT).'.log';
            if (!file_exists($todayNextArchive)) {
                break;
            }
            ++$cpt;
        }
        $todayName = $todayBaseName.'.log';

        // S'il existe et qu'il est trop gros, on l'archive et on en créé un nouveau.
        $fileSize = filesize($todayName);
        if ($fileSize>10*1024*1024) {
            rename($todayName, $todayNextArchive);
        }

        // Sinon, on continue à utiliser l'existant
        $fp = fopen($todayName, 'a');
        if (!$fp) {
            return;
        }
        $strMessage = "[".date('Y-m-d H:i:s')."] - ".$prepRequest."\r\n";
        fwrite($fp, $strMessage);
        fclose($fp);
    }

}
