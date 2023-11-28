<?php
namespace core\bean;

use core\services\CopsCalAddressServices;
use core\services\CopsCalPhoneServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageCalWipBean
 * @author Hugues
 * @since v1.23.12.02
 */
class AdminPageCalWipBean extends AdminPageCalBean
{
    private $objCalStreetSelected;

    /**
     * @since 1.23.12.02
     */
    public function getContentOnglet(): string
    {
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination, l'action ou l'id de l'événement concerné
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        /////////////////////////////////////////

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();

        // Initialisation de la liste des cards qu'on va afficher.
        $strCards = $this->getCard();
        
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
     * @since 1.23.10.14
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_WIP, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.10.14
     */
    public function getCard(): string
    {
        // On a à gauche les données provenant de cal_street.
        // On ne pagine pas. On affiche juste les x premiers éléments. Eventuellement, on peut indiquer le nombre d'éléments totaux pour information.
        // On peut supprimer une donnée de la table et recharger l'écran.
        // La sélection d'une donnée recharge la page avec les données correspondantes à droite
        $strContent = $this->getCalStreetCard();

        // On a à droite, les données provenant de cal_address correspondant à l'élement de cal_street sélectionné
        // On doit pouvoir modifier les données présentées.
        $strContent .= $this->getCalAddressCard();
        // On doit pouvoir créer de nouvelles données.
        $strContent .= $this->getNewCalAddressCard();

        return $strContent;
    }

    public function getCalStreetCard()
    {
        $strContent = '
        <div class="card col mx-1 p-0">
        <div class="card-header">Données de cal_street</div>
        <div class="card-body">
        <div class="row">
        <div class="col">
        <div class="table-responsive">
        <table class="table table-sm table-striped m-0">
        <thead>
        <tr><th class="col-1">&nbsp;</th><th class="col-1">id</th><th class="col-7">streetName</th><th class="col-3">streetSuffixe</th></tr>
        </thead>
        <tbody>';

        $request = "SELECT * FROM wp_7_cops_cal_street ORDER BY streetName ASC;";
        $rows = \core\domain\MySQLClass::wpdbSelect($request);
        $nbRows = count($rows);
        $locRows = array_splice($rows, 0, 10);
        foreach ($locRows as $row) {
            if ($this->objCalStreetSelected==null) {
                $this->objCalStreetSelected = $row;
            }
            $id = $row->id;
            $streetName = $row->streetName;
            $streetSuffix = $row->streetSuffix;
            $strContent .= '<tr>';
            $strContent .= '<td class="col-1"><input type="radio"/></td>';
            $strContent .= '<td class="col-1">'.$id.'</td>';
            $strContent .= '<td class="col-7">'.$streetName.'</td>';
            $strContent .= '<td class="col-3">'.$streetSuffix.'</td>';
            $strContent .= '</tr>';
        }

        $strContent .= '</tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
        <div class="card-footer clearfix">'.$nbRows.' entrées à traiter</div>
        </div>
        ';

        return $strContent;
    }

    public function getCalAddressCard()
    {
        $strContent = '
        <div class="card col mx-1 p-0">
        <div class="card-header">Données de cal_address</div>
        <div class="card-body">
        <div class="row">
        <div class="col">
        <div class="table-responsive">
        <table class="table table-sm table-striped m-0">';

        $objServices = new CopsCalAddressServices();
        $obj = new CopsCalAddressBean();
        $strContent .= $obj->getTableHeader()->getBean();

        $strContent .= '<tbody>';

        $wholeStreetName = $this->objCalStreetSelected->streetName;
        $pos = strrpos($wholeStreetName, ' ');
        $streetName = substr($wholeStreetName, 0, $pos);
        $streetSuffix = substr($wholeStreetName, $pos+1);

        $attributes = [
            self::FIELD_ST_NAME => $streetName,
        ];
        $objs = $objServices->getCalAddresses($attributes);
        if (empty($objs)) {
            $strContent .= $obj->getEmptyRow()->getBean();
        } else {
            foreach ($objs as $obj) {
                $strContent .= $obj->getBean()->getTableRow()->getBean();
            }
        }

        $strContent .= '</tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
        <div class="card-footer clearfix">Nouvelle entrée ?</div>
        </div>
        ';

        return $strContent;
    }

    public function getNewCalAddressCard()
    {
        return '';
    }

}
