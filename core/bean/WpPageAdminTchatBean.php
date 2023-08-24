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
 * @version v1.23.08.12
 */
class WpPageAdminTchatBean extends WpPageAdminBean
{
    private $objTchatServices;

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

        $this->init();
    }

    /**
     * @since v1.23.08.12
     */
    private function init(): void
    {
        $this->objTchatServices = new CopsTchatServices();
    }
    
    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function getOngletContent(): string
    {
        $strTchatContent = $this->buildTchatContent([], 'oneWeekAgo');
        ///////////////////////////////////////////////////////////
        // On met à jour le statut de lastRefreshed
        $objTchatStatut = $this->objTchatServices->getTchatStatus(1, $this->objCopsPlayer->getField(self::FIELD_ID));
        $objTchatStatut->setField(self::FIELD_LAST_REFRESHED, DateUtils::getStrDate('Y-m-d H:i:s', time()));
        if ($objTchatStatut->getField(self::FIELD_ID)=='') {
            $this->objTchatServices->insertTchatStatus($objTchatStatut);
        } else {
            $this->objTchatServices->updateTchatStatus($objTchatStatut);
        }
        ///////////////////////////////////////////////////////////
        $strTchatStatus  = $this->buildTchatContacts();

        // On enrichi et retourne le template
        $urlTemplate = self::WEB_PPFS_TCHAT;
        $attributes = [
            // Liste des messages à afficher dans le Tchat
            $strTchatContent,
            // Liste des contacts participants au salon
            $strTchatStatus,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.08.12
     */
    public function buildTchatContent(array $attributes=[], string $flag=''): string
    {
        $objs = $this->objTchatServices->getTchats($attributes, $flag);
        $strTchats = '';
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $strTchats .= $obj->getBean()->getTchatRow();
        }
        return $strTchats;
    }

    /**
     * @since v1.23.08.12
     * TODO : Faire en sorte que le salon soit dynamique.
     */
    public function buildTchatContacts(): string
    {
        $sqlAttributes = [self::FIELD_SALON_ID => 1];
        $objs = $this->objTchatServices->getTchatStatuss($sqlAttributes);
        $strContacts = '';
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $strContacts .= $obj->getBean()->getContactRow();
        }
        return $strContacts;
    }
}
