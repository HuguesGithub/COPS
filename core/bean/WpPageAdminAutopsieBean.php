<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminAutopsieBean
 * @author Hugues
 * @since 1.22.10.08
 * @version v1.23.12.02
 */
class WpPageAdminAutopsieBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugOnglet = self::ONGLET_AUTOPSIE;

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = [
            self::CST_AUTOPSIE_ARCHIVE => [self::FIELD_ICON => self::I_BOX_ARCHIVE, self::FIELD_LABEL => 'Archive'],
            self::CST_ENQUETE_READ => [self::FIELD_LABEL => 'Lire'],
            self::CST_ENQUETE_WRITE => [self::FIELD_LABEL => 'Rédiger']
        ];
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Définition des services
        $this->CopsAutopsieServices = new CopsAutopsieServices();
    }

    /**
     * @return string
     * @since 1.22.10.19
     * @version 1.22.10.19
     */
    public function initBoard()
    {
        /////////////////////////////////////////
        // Création du Breadcrumbs
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->buildBreadCrumbs('Autopsies', self::ONGLET_AUTOPSIE, true);

        /////////////////////////////////////////
        // Si formulaire soumis, mise à jour ou insertion.
        if (isset($this->urlParams[self::CST_WRITE_ACTION])) {
            // Insertion / Mise à jour de l'autopsie saisie via le formulaire
            if ($this->urlParams[self::FIELD_ID]!='') {
                $this->objCopsAutopsie = CopsAutopsieActions::updateAutopsie($this->urlParams);
            } else {
                $this->objCopsAutopsie = CopsAutopsieActions::insertAutopsie($this->urlParams);
            }
        } else {
            // On récupère l'autopsie associée à l'id.
            $this->objCopsAutopsie = $this->CopsAutopsieServices->getAutopsie($this->urlParams[self::FIELD_ID]);
        }
    }

    /**
     * @since 1.22.10.08
     * @version v1.23.05.28
     */
    public function getOngletContent(): string
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-autopsies.php';

        /////////////////////////////////////////
        // Construction du panneau de droite
        $strBtnClass = 'btn btn-primary btn-block mb-3';
        if ($this->slugSubOnglet==self::CST_ENQUETE_WRITE) {
            // Si on est en mode écriture
            $strRightPanel   = $this->objCopsAutopsie->getBean()->getWriteAutopsieBlock();
            $url = $this->getOngletUrl();
            $strContent = HtmlUtils::getIcon(self::I_BACKWARD).' Retour';
        } else {
            // Si on est sur la page de listing des autopsies
            $strRightPanel   = $this->getFolderAutopsiesList();
            $url = $this->getSubOngletUrl(self::CST_FOLDER_WRITE);
            $strContent = 'Débuter une autopsie';
        }
        /////////////////////////////////////////

        $attributes = [
            // Contenu du panneau latéral gauche
            $this->getFolderBlock(),
            // Contenu du panneau principal
            $strRightPanel,
            // Eventuel bouton de retour si on est en train de lire ou rédiger un message
            HtmlUtils::getLink($strContent, $url, $strBtnClass),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.08
     * @version 1.22.10.14
     */
    public function getFolderAutopsiesList()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-autopsies-list.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        $attributes = [];
        $objsCopsAutopsie = $this->CopsAutopsieServices->getAutopsies($attributes);
        if (empty($objsCopsAutopsie)) {
            $strContent = '<tr><td class="text-center">Aucune autopsie.<br></td></tr>';
        } else {
            $strContent = '';
            foreach ($objsCopsAutopsie as $objCopsAutopsie) {
                $strContent .= $objCopsAutopsie->getBean()->getCopsAutopsieRow();
            }
        }
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Gestion de la pagination
        $strPagination = '';
        /////////////////////////////////////////

        $attributes = [
            // Titre du dossier affiché
            'Autopsies',
            // Nombre d'autopsies : 1-50/200
            $strPagination,
            // La liste des autopsies
            $strContent,
            // L'url de retour
            $this->getSubOngletUrl(),
        ];
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
}
