<?php
namespace core\bean;

use core\domain\CopsCalGuyClass;
use core\enum\SectionEnum;
use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminDatabaseResultBean
 * @author Hugues
 * @since v1.23.11.25
 */
class WpPageAdminDatabaseResultBean extends WpPageAdminDatabaseBean
{
    private $obj;

    /**
     * @since v1.23.11.25
     */
    public function __construct()
    {
        parent::__construct();

        $this->initServices();

        /////////////////////////////////////////
        $this->urlAttributes[self::CST_SUBONGLET] = self::CST_BDD_RESULT;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_RESULTS,
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
     * @since v1.23.11.25
     */
    public function initServices()
    {
        $this->objServices = new CopsCalGuyServices();
        $objs = $this->objServices->getCalGuys([self::FIELD_GENKEY=>$this->initVar(self::FIELD_GENKEY)??'']);
        $this->obj = !empty($objs) ? array_shift($objs) : new CopsCalGuyClass();
    }

    /**
     * @since v1.23.11.25
     */
    public function getOngletContent(): string
    {
        $name = $this->obj->getFullName();
        if ($name=='') {
            $name = "Erreur d'identifiant.";
            $detailBlock = '';
            $addressBlock = '';
            $phoneBlock = '';
        } else {
            $detailBlock = $this->getDetailBlock();
            $addressBlock = $this->getAddressBlock();
            $phoneBlock = 'TODO';
        }

        $urlTemplate = self::WEB_PPFS_BDD_RESULT;
        $attributes = [
            // Nom de l'individu
            $name,
            // Block Détail
            $detailBlock,
            $addressBlock,
            $phoneBlock,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.11.25
     */
    public function getAddressBlock(): string
    {
        $objs = $this->obj->getCalGuyAddresses();
        if (empty($objs)) {
            $str = '<ul><li>Aucune adresse recensée.</li></ul>';
        } else {
            $str  = '<ul>';
            foreach ($objs as $obj) {
                $str .= $obj->getBean()->getListAddress();
            }
            $str .= '</ul>';

        }
        return $str;
    }

    /**
     * @since v1.23.11.25
     */
    public function getDetailBlock(): string
    {
        $urlTemplate = self::WEB_PPFD_BDD_CAL_GUY;
        $attributes = $this->obj->getBean()->getDetailInterface();
        return $this->getRender($urlTemplate, $attributes);
    }
}

