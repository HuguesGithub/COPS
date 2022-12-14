<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAutopsieBean
 * @author Hugues
 * @since 1.22.10.08
 * @version 1.22.10.19
 */
class WpPageAdminAutopsieBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugOnglet = self::ONGLET_AUTOPSIE;

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = array(
            self::CST_AUTOPSIE_ARCHIVE => array(self::FIELD_ICON => 'box-archive', self::FIELD_LABEL => 'Archive'),
            self::CST_ENQUETE_READ => array(self::FIELD_LABEL => 'Lire'),
            self::CST_ENQUETE_WRITE => array(self::FIELD_LABEL => 'Rédiger'),
        );
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
     * @version 1.22.10.08
     */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-autopsies.php';

        /////////////////////////////////////////
        // Construction du panneau de droite
        $strBtnClass = 'btn btn-primary btn-block mb-3';
        if ($this->slugSubOnglet==self::CST_ENQUETE_WRITE) {
            // Si on est en mode écriture
            $strRightPanel   = $this->objCopsAutopsie->getBean()->getWriteAutopsieBlock();
            $attributes = array (
                self::ATTR_HREF  => $this->getOngletUrl(),
                self::ATTR_CLASS => $strBtnClass,
            );
            $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
        } else {
            // Si on est sur la page de listing des autopsies
            $strRightPanel   = $this->getFolderAutopsiesList();
            $attributes = array (
                self::ATTR_HREF  => $this->getSubOngletUrl(self::CST_FOLDER_WRITE),
                self::ATTR_CLASS => $strBtnClass,
            );
            $strContent = 'Débuter une autopsie';
        }
        /////////////////////////////////////////

        $attributes = array(
            // Contenu du panneau latéral gauche
            $this->getFolderBlock(),
            // Contenu du panneau principal
            $strRightPanel,
            // Eventuel bouton de retour si on est en train de lire ou rédiger un message
            $this->getBalise(self::TAG_A, $strContent, $attributes),
        );
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
        $attributes = array();
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

        $attributes = array(
            // Titre du dossier affiché
            'Autopsies',
            // Nombre d'autopsies : 1-50/200
            $strPagination,
            // La liste des autopsies
            $strContent,
            // L'url de retour
            $this->getSubOngletUrl(),
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
}
