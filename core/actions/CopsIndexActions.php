<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsIndexActions
 * @since 1.22.10.22
 * @version 1.22.10.22
 */
class CopsIndexActions extends LocalActions
{
    /**
     * @since 1.22.10.22
     * @version 1.22.10.22
     */
    public function __construct()
    {
        parent::__construct();
        $this->objCopsIndexServices = new CopsIndexServices();
    }

    /**
     * @since 1.22.10.22
     * @version 1.22.10.22
     */
    public static function dealWithStatic($params)
    {
        $objCopsIndexActions = new CopsIndexActions();
        switch ($params[self::AJAX_ACTION]) {
            case 'csvExport' :
                $returned = $objCopsIndexActions->csvExport($params);
                break;
            default :
                $returned = self::getErrorActionContent($params[self::AJAX_ACTION]);
                break;
        }
        return $returned;
    }

    /**
     * @since 1.22.10.22
     * @version 1.22.10.22
     */
    public function csvExport($params)
    {
        // Récupération des paramètres
        $attributes = array();
        if (isset($params['natureId']) && $params['natureId']!='') {
            $attributes[self::SQL_WHERE_FILTERS]['natureId'] = $params['natureId'];
        }
        // Initialisation de la liste à exporter
        $objsCopsIndex = $this->objCopsIndexServices->getIndexes($attributes);

        // Définir le nom du CSV
        $strFileName = 'export_index_'.date('Ymd').'.csv';
        // Définir le répertoire de stockage, à moins de le retourner direct en download
        $strDirectory = PLUGIN_PATH.'web/rsc/files/';
        // Définir la première ligne du CSV
        $arrHeader = array('Nom', 'Nature', 'Code', 'Description', 'Référence');
        $csvContent = implode(self::CSV_SEP, $arrHeader).self::CSV_EOL;
        // Définir chaque ligne du CSV, à partir de la liste à exporter
        while (!empty($objsCopsIndex)) {
            $objCopsIndex = array_shift($objsCopsIndex);
            $arrLine = array(
                $objCopsIndex->getField('nomIdx'),
                $objCopsIndex->getNature()->getField('nomIdxNature'),
                $objCopsIndex->getStrCode(),
                $objCopsIndex->getField('descriptionPJ'),
                $objCopsIndex->getField('reference'),
            );
            $csvContent .= implode(self::CSV_SEP, $arrLine).self::CSV_EOL;
        }
        
        // On stocke le contenu du fichier
        $fp = fopen($strDirectory.$strFileName, 'w');
        fputs($fp, $csvContent);
        fclose($fp);
        
        $url = '/wp-content/plugins/hj-cops/web/rsc/files/'.$strFileName;
        $strMessage = 'Le fichier CSV peut être téléchargé <a href="'.$url.'" class="text-white">ici</a>.';
        $returned = $this->getToastContentJson('success', 'Download', $strMessage);
        return $returned;
    }

}
