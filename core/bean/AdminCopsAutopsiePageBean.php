<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdminCopsAutopsiePageBean
 * @author Hugues
 * @since 1.22.10.08
 * @version 1.22.10.08
 */
class AdminCopsAutopsiePageBean extends AdminCopsPageBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        $this->urlOnglet    = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_AUTOPSIE;
        $this->urlSubOnglet = $this->urlOnglet.'&amp;'.self::CST_SUBONGLET.'=';
    }

    /**
     * @return string
     * @since 1.22.10.08
     * @version 1.22.10.08
     */
    public function getBoard()
    {
        $this->subOnglet = $this->initVar(self::CST_SUBONGLET, self::CST_FILE_OPENED);
        $this->buildBreadCrumbs('Autopsies', self::ONGLET_AUTOPSIE, true);

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
        $strRightPanel   = $this->getFolderAutopsiesList();
        $attributes = array (
            self::ATTR_HREF  => $this->urlSubOnglet.self::CST_FOLDER_WRITE,
            self::ATTR_CLASS => $strBtnClass,
        );
        $strContent = 'Débuter une autopsie';
        /////////////////////////////////////////

        $attributes = array(
            // Contenu du panneau latéral gauche
            '',
            // Contenu du panneau principal
            $strRightPanel,
            // Eventuel bouton de retour si on est en train de lire ou rédiger un message
            $this->getBalise(self::TAG_A, $strContent, $attributes),
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.08
     * @version 1.22.10.08
     */
    public function getFolderEnquetesList()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-autopsies-list.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        $strContent = '<tr><td class="text-center">Aucune autopsies.<br></td></tr>';

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