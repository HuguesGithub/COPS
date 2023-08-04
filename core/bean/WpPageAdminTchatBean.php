<?php
namespace core\bean;

use core\services\CopsTchatServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminTchatBean
 * @author Hugues
 * @since v1.23.08.05
 */
class WpPageAdminTchatBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_TCHAT,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton(
            $buttonContent,
            [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK_DISABLED]
        );
        /////////////////////////////////////////
    }
    
    /**
     * @since v1.23.08.05
     */
    public function getOngletContent(): string
    {
        // On récupère les données à afficher dans le tchat
        $objTchatServices = new CopsTchatServices();
        $objs = $objTchatServices->getTchats();

        // On met à jour le statut de lastRefreshed
        $objTchatStatut = $objTchatServices->getTchatStatus(1, $this->objCopsPlayer->getField(self::FIELD_ID));

        $objTchatStatut->setField(self::FIELD_LAST_REFRESHED, DateUtils::getStrDate('Y-m-d H:i:s', time()));
        if ($objTchatStatut->getField(self::FIELD_ID)=='') {
            $objTchatServices->insertTchatStatus($objTchatStatut);
        } else {
            $objTchatServices->updateTchatStatus($objTchatStatut);
        }

        // On construit la liste des éléments à afficher
        $strTchats = '';
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $strTchats .= $obj->getBean()->getTchatRow();
        }

        // On enrichi et retourne le template
        $urlTemplate = self::WEB_PPFS_TCHAT;
        $attributes = [
            // Liste des messages à afficher dans le Tchat
            $strTchats,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

}
