<?php
namespace core\actions;

use core\utils\DateUtils;

/**
 * CopsIndexActions
 * @since 1.22.10.22
 * @version v1.23.08.12
 */
class CopsIndexActions extends LocalActions
{
    /**
     * @since 1.22.10.22
     * @version 1.22.10.22
     */
    public function __construct(CopsIndexServices $objCopsIndexServices)
    {
        parent::__construct();
        $this->objCopsIndexServices = $objCopsIndexServices;
    }

    /**
     * @since 1.22.10.22
     * @version 1.22.10.22
     */
    public static function dealWithStatic($params)
    {
        $objCopsIndexServices = new CopsIndexServices();
        $objCopsIndexActions = new CopsIndexActions($objCopsIndexServices);
        if ($params[self::AJAX_ACTION]=='csvExport') {
            $returned = $objCopsIndexActions->csvExport($params);
        } else {
            $returned = static::getErrorActionContent($params[self::AJAX_ACTION]);
        }
        return $returned;
    }

    /**
     * @since 1.22.10.22
     * @version v1.23.08.12
     */
    public function csvExport($params)
    {
        $objCopsIndex = null;
        // Définir la première ligne du CSV
        $arrHeader = [self::LABEL_NOM];
        
        // Récupération des paramètres
        $attributes = [];
        if (isset($params[self::FIELD_NATURE_ID]) && $params[self::FIELD_NATURE_ID]!='') {
            $attributes[self::FIELD_NATURE_ID] = $params[self::FIELD_NATURE_ID];
        } else {
            $arrHeader[] = self::LABEL_NATURE;
        }
        if (static::isAdmin()) {
            $arrToMerge = [
                self::LABEL_CODE,
                self::LABEL_DESCRIPTION_PJ,
                self::LABEL_DESCRIPTION_MJ,
                self::LABEL_REFERENCE];
            $arrHeader = array_merge($arrHeader, $arrToMerge);
        } else {
            $arrHeader[] = self::LABEL_DESCRIPTION_PJ;
        }
        $csvContent = implode(self::CSV_SEP, $arrHeader).self::CSV_EOL;
        
        // Initialisation de la liste à exporter
        $objsCopsIndex = $this->objCopsIndexServices->getIndexes($attributes);

        // Définir chaque ligne du CSV, à partir de la liste à exporter
        while (!empty($objsCopsIndex)) {
            $objCopsIndex = array_shift($objsCopsIndex);
            $arrLine = [$objCopsIndex->getField(self::FIELD_NOM_IDX)];
            if (!isset($params[self::FIELD_NATURE_ID]) || $params[self::FIELD_NATURE_ID]=='') {
                $arrLine[] = $objCopsIndex->getNature()->getField(self::FIELD_NOM_IDX_NATURE);
            }
            if (static::isAdmin()) {
                $arrToMerge = [
                    $objCopsIndex->getStrCode(),
                    $objCopsIndex->getField(self::FIELD_DESCRIPTION_PJ),
                    $objCopsIndex->getField(self::FIELD_DESCRIPTION_MJ),
                    $objCopsIndex->getField(self::FIELD_REFERENCE)];
                $arrLine = array_merge($arrLine, $arrToMerge);
            } else {
                $arrLine[] = $objCopsIndex->getField(self::FIELD_DESCRIPTION_PJ);
            }
            $csvContent .= implode(self::CSV_SEP, $arrLine).self::CSV_EOL;
        }
        
        // Définir le répertoire de stockage, à moins de le retourner direct en download
        $strDirectory = PLUGIN_PATH.'web/rsc/files/';
        // Définir le nom du CSV
        if (isset($params[self::FIELD_NATURE_ID]) && $params[self::FIELD_NATURE_ID]!='') {
            $nomNature = $objCopsIndex->getNature()->getField(self::FIELD_NOM_IDX_NATURE);
            $strFileName = 'export_index_'.$nomNature.'_'.DateUtils::getStrDate('Ymd', time()).'.csv';
        } else {
            $strFileName = 'export_index_'.DateUtils::getStrDate('Ymd', time()).'.csv';
        }
        // On stocke le contenu du fichier
        $fp = fopen($strDirectory.$strFileName, 'w');
        fwrite($fp, $csvContent);
        fclose($fp);
        
        $url = '/wp-content/plugins/hj-cops/web/rsc/files/'.$strFileName;
        $strMessage = 'Le fichier CSV peut être téléchargé <a href="'.$url.'" class="text-white">ici</a>.';
        return $this->getToastContentJson('success', 'Download', $strMessage);
    }

}
