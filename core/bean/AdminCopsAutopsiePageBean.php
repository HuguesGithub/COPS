<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdminCopsAutopsiePageBean
 * @author Hugues
 * @since 1.22.10.08
 * @version 1.22.10.14
 */
class AdminCopsAutopsiePageBean extends AdminCopsPageBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Construction du menu
        $this->arrSubOnglets = array(
            self::CST_AUTOPSIE_ARCHIVE => array(self::FIELD_ICON => 'box-archive', self::FIELD_LABEL => 'Archive'),
            self::CST_ENQUETE_WRITE => array(self::FIELD_LABEL => 'Rédiger'),
        );
        /////////////////////////////////////////
        $this->urlOnglet    = '/admin?'.self::CST_ONGLET.'=';
        $this->baseUrl      = $this->urlOnglet.self::ONGLET_AUTOPSIE;
        $this->urlSubOnglet = '&amp;'.self::CST_SUBONGLET.'=';
        
        /////////////////////////////////////////
        // Définition des services
        $this->CopsAutopsieServices = new CopsAutopsieServices();
    }

    /**
     * @return string
     * @since 1.22.10.08
     * @version 1.22.10.09
     */
    public function getBoard()
    {
        $this->subOnglet = $this->initVar(self::CST_SUBONGLET, self::CST_AUTOPSIE_ARCHIVE);
        $this->buildBreadCrumbs('Autopsies', self::ONGLET_AUTOPSIE, true);

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
        
        ////////////////////////////////////////////////////////
        $urlTemplate = 'web/pages/public/public-board.php';
        $attributes = array(
            // La sidebar
            $this->getSideBar(),
            // Le contenu de la page
            $this->getOngletContent(),
            // L'id
            $this->CopsPlayer->getMaskMatricule(),
            // Le nom
            $this->CopsPlayer->getFullName(),
            // La barre de navigation
            $this->getNavigationBar(),
            // Le content header
            $this->getContentHeader(),
            // Version
            self::VERSION,
            '', '', '', '', '', '', '', '', '', '', '',
        );
        return $this->getRender($urlTemplate, $attributes);
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
        if ($this->subOnglet==self::CST_ENQUETE_WRITE) {
            $strRightPanel   = $this->objCopsAutopsie->getBean()->getWriteAutopsieBlock();
            $attributes = array (
                self::ATTR_HREF  => $this->baseUrl,
                self::ATTR_CLASS => $strBtnClass,
            );
            $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
        } else {
            $strRightPanel   = $this->getFolderAutopsiesList();
            $attributes = array (
                self::ATTR_HREF  => $this->baseUrl.$this->urlSubOnglet.self::CST_FOLDER_WRITE,
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
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->urlSubOnglet.$this->subOnglet,
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }

}
