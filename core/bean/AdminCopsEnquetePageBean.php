<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdminCopsEnquetePageBean
 * @author Hugues
 * @since 1.22.09.20
 * @version 1.22.09.24
 */
class AdminCopsEnquetePageBean extends AdminCopsPageBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Construction du menu de l'inbox
        $this->arrSubOnglets = array(
            self::CST_FILE_OPENED  => array(self::FIELD_ICON  => 'file-circle-plus', self::FIELD_LABEL => 'En cours'),
            self::CST_FILE_CLOSED  => array(self::FIELD_ICON  => 'file-circle-check', self::FIELD_LABEL => 'Classées'),
            self::CST_FILE_COLDED  => array(self::FIELD_ICON  => 'file-circle-xmark', self::FIELD_LABEL => 'Cold Case'),
            self::CST_FOLDER_READ  => array(self::FIELD_LABEL => 'Lire'),
            self::CST_FOLDER_WRITE => array(self::FIELD_LABEL => 'Rédiger'),
        );
        /////////////////////////////////////////
        $this->urlOnglet    = '/admin?'.self::CST_ONGLET.'='.self::ONGLET_ENQUETE;
        $this->urlSubOnglet = $this->urlOnglet.'&amp;'.self::CST_SUBONGLET.'=';

        /////////////////////////////////////////
        // Définition des services
        $this->CopsEnqueteServices = new CopsEnqueteServices();
    }
    
    /**
     * @return string
     * @since 1.22.09.20
     * @version 1.22.09.24
     */
    public function getBoard()
    {
        $this->subOnglet = $this->initVar(self::CST_SUBONGLET, self::CST_FILE_OPENED);
        $this->buildBreadCrumbs('Enquêtes', self::ONGLET_ENQUETE, true);
    
        ////////////////////////////////////////////////////////
        if (isset($this->urlParams['writeAction'])) {
            // Insertion / Mise à jour de l'enquête saisie via le formulare
            // Mais seulement si le nom de l'enquête a été saisi.
            if ($this->urlParams[self::FIELD_NOM_ENQUETE]!='') {
                if ($this->urlParams[self::FIELD_ID]!='') {
                    $this->CopsEnquete = CopsEnqueteActions::updateEnquete($this->urlParams);
                } else {
                    $this->CopsEnquete = CopsEnqueteActions::insertEnquete($this->urlParams);
                }
            }
        } elseif (isset($this->urlParams['action'])) {
            // Mise à jour du statut (et donc de la dernière date de modification) sur le lien de la liste.
            // On récupère l'enquête associée à l'id.
            $this->CopsEnquete = $this->CopsEnqueteServices->getEnquete($this->urlParams[self::FIELD_ID]);
            // Si elle existe, on effectue le traitement qui va bien.
            $intStatut = $this->CopsEnquete->getField(self::FIELD_STATUT_ENQUETE);
            if ($this->CopsEnquete->getField(self::FIELD_ID)==$this->urlParams[self::FIELD_ID]
                && $intStatut!=self::CST_ENQUETE_CLOSED
                && ($intStatut==self::CST_ENQUETE_OPENED
                    || $intStatut==self::CST_ENQUETE_COLDED && $this->urlParams['action']==self::CST_ENQUETE_OPENED)) {
                        // Si l'enquête existe.
                        // Si l'enquête n'est pas déjà transférée au DA.
                        // Si l'enquête est coldcase, elle ne peut pas être transférée au DA
                
                        // Si tout est bon,
                        // on classe une enquête, on la réouvre ou on la transfère au DA
                        $this->CopsEnquete->setField(self::FIELD_STATUT_ENQUETE, $this->urlParams['action']);
                        $this->CopsEnquete->setField(self::FIELD_DLAST, self::getCopsDate('tsnow'));
                        $this->CopsEnqueteServices->updateEnquete($this->CopsEnquete);
            }
        } else {
            // On récupère l'enquête associée à l'id.
            $this->CopsEnquete = $this->CopsEnqueteServices->getEnquete($this->urlParams[self::FIELD_ID]);
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
            '', '', '', '', '', '', '', '', '', '', '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquetes.php';
        
        /////////////////////////////////////////
        // Construction du panneau de droite
        $strBtnClass = 'btn btn-primary btn-block mb-3';
        switch ($this->subOnglet) {
            case self::CST_FOLDER_READ :
                $strRightPanel   = $this->CopsEnquete->getBean()->getReadEnqueteBlock();
                $attributes = array (
                    self::ATTR_HREF  => $this->urlOnglet,
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
            break;
            case self::CST_FOLDER_WRITE :
                $strRightPanel   = $this->CopsEnquete->getBean()->getWriteEnqueteBlock();
                $attributes = array (
                    self::ATTR_HREF  => $this->urlOnglet,
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
            break;
            default :
                $strRightPanel   = $this->getFolderEnquetesList();
                $attributes = array (
                    self::ATTR_HREF  => $this->urlSubOnglet.self::CST_FOLDER_WRITE,
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = 'Ouvrir une enquête';
            break;
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
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function getFolderBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-li-menu-folder.php';
        /////////////////////////////////////////
        // Construction du panneau de gauche
        $strLeftPanel = '';
        foreach ($this->arrSubOnglets as $slug => $subOnglet) {
            // On exclu les sous onglets sans icones
            if (!isset($subOnglet[self::FIELD_ICON])) {
                continue;
            }
            // On construit l'entrée de l'onglet/
            $attributes = array(
                // Menu sélectionné ou pas ?
                ($slug==$this->subOnglet ? ' '.self::CST_ACTIVE : ''),
                // L'url du folder
                $this->urlSubOnglet.$slug,
                // L'icône
                $subOnglet[self::FIELD_ICON],
                // Le libellé
                $subOnglet[self::FIELD_LABEL],
            );
            $strLeftPanel .= $this->getRender($urlTemplate, $attributes);
       }
       /////////////////////////////////////////
       return $strLeftPanel;
    }
  
    /**
     * @since 1.22.09.20
     * @version 1.22.09.20
     */
    public function getFolderEnquetesList()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquetes-list.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        // Liste des dossiers pour une catégorie spécifique (En cours, Classées, Cold Case...)
        switch ($this->subOnglet) {
            case self::CST_FILE_CLOSED :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_CLOSED,
                ));
            break;
            case self::CST_FILE_COLDED :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_COLDED,
                ));
             break;
            case self::CST_FILE_OPENED :
            default :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_OPENED,
                ));
            break;
        }
    
        $objsCopsEnquete = $this->CopsEnqueteServices->getEnquetes($attributes);
        if (empty($objsCopsEnquete)) {
            $strContent = '<tr><td class="text-center">Aucune enquête.<br></td></tr>';
        } else {
            $strContent = '';
            foreach ($objsCopsEnquete as $objCopsEnquete) {
                $strContent .= $objCopsEnquete->getBean()->getCopsEnqueteRow();
            }
        }
        /////////////////////////////////////////
        // Gestion de la pagination
        $strPagination = '';
        /////////////////////////////////////////
    
        $attributes = array(
            // Titre du dossier affiché
            $this->arrSubOnglets[$this->subOnglet][self::FIELD_LABEL],
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