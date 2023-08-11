<?php
namespace core\bean;

use core\services\CopsPlayerServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminConfigBean
 * @author Hugues
 * @since v1.23.08.12
 */
class WpPageAdminConfigBean extends WpPageAdminBean
{

    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_CONFIG,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton(
            $buttonContent,
            [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK_DISABLED]
        );
        /////////////////////////////////////////

        $this->init();
        $this->dealWithSubmit();
    }

    /**
     * @since v1.23.08.12
     */
    private function init(): void
    {
        $this->objPlayerServices = new CopsPlayerServices();
    }

    /**
     * @since v1.23.08.12
     */
    private function dealWithSubmit(): void
    {
        if (SessionUtils::fromPost(self::CST_WRITE_ACTION)=='changeMdp') {
            $oldMdp = SessionUtils::fromPost('oldmdp');
            $mdp = md5((string) $oldMdp);
            $newMdp = SessionUtils::fromPost('newmdp');
            $confirmMdp = SessionUtils::fromPost('cfrmmdp');
            // TODO : Ajouter un contrôle de force sur le mot de passe ?

            if ($mdp==$this->objCopsPlayer->getField(self::FIELD_PASSWORD) && $newMdp==$confirmMdp) {
                $this->objCopsPlayer->setField(self::FIELD_PASSWORD, md5($newMdp));
                $this->objCopsPlayer->setField(self::FIELD_STATUS, self::PS_CREATE_1ST_STEP);
                $this->objPlayerServices->updatePlayer($this->objCopsPlayer);
            }
        }
    }
    
    /**
     * @since v1.23.08.12
     */
    public function getOngletContent(): string
    {
        // On enrichi et retourne le template
        $urlTemplate = self::WEB_PPFS_CONFIG;
        return $this->getRender($urlTemplate);
    }

}
