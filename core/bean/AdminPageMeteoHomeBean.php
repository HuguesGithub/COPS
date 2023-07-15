<?php
namespace core\bean;

use core\domain\CopsMeteoClass;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageMeteoHomeBean
 * @author Hugues
 * @since 1.23.04.26
 * @version v1.23.07.15
 */
class AdminPageMeteoHomeBean extends AdminPageMeteoBean
{
    private $ajaxUrl = 'https://www.timeanddate.com/scripts/cityajax.php?n=usa/los-angeles&mode=historic&hd=%1$s&month=%2$s&year=%3$s';

    /**
     * @since v1.23.04.29
     * @version v1.23.07.15
     */
    public function getContentOnglet(): string
    {
        // Gestion d'éventuels traitements.
        $strDate = SessionUtils::fromGet(self::CST_DATE);
        if ($strDate!='') {
            $strCompteRendu = $this->dealWithGetActions();
        } else {
            $strCompteRendu = '';
        }

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        // Initialisation de la liste des cards qu'on va afficher.
        // Card Meteo
        // Card Soleil
        // Card Lune
        // Card Saisons
        // Card Home ?
        // Quand on veut aller à la ligne, on doit ajouter une div :
        // <div class="w-100"></div>
        $strCards = '';

        $strCards .= $this->getCard();

        $objBean = new AdminPageMeteoMeteoBean();
        $strCards .= $objBean->getCard($strCompteRendu);

        $strCards .= '<div class="w-100"></div>';

        $objBean = new AdminPageMeteoSunBean();
        $strCards .= $objBean->getCard();

        $objBean = new AdminPageMeteoMoonBean();
        $strCards .= $objBean->getCard();

        $strCards .= '<div class="w-100"></div>';
        
        // On va afficher la dernière donnée enregistrée
        // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
        $attributes = [
            $this->pageTitle,
            $this->pageSubTitle,
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_DEFAULT, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_HOME, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.04.30
     */
    public function getCardContent(string &$titre, string &$strBody): void
    {
        $titre = 'Home';
        $strBody = 'WIP Home Card';
    }

    /**
     * @since v1.23.04.28
     * @version v1.23.06.25
     */
    public function dealWithGetActions(): string
    {
        $objCopsMeteo = new CopsMeteoClass();
        $strDate = SessionUtils::fromGet(self::CST_DATE);
        [, $m, $y, , ,] = DateUtils::parseDate($strDate);
        $strDate = str_replace('-', '', $strDate);

        // On construit l'url ciblée
        $url  = sprintf($this->ajaxUrl, $strDate, $m, $y);
        $strCompteRendu = HtmlUtils::getLink('Date étudiée', $url);

        // On en récupère le contenu
        $str = file_get_contents($url);
        // On ne veut que le tbody du tableau
        $strpos = strpos($str, 'tbody');
        // On transforme la ligne unique en un tableau
        $arr = explode("/tr><tr", substr($str, $strpos));

        // On parcourt toutes les lignes du tableau
        foreach ($arr as $str) {
            // Que l'on parse pour récupérer les données souhaitées.
            $strCompteRendu .= $objCopsMeteo->parseData($str, $strDate);
        }
        return $strCompteRendu;
    }

}
