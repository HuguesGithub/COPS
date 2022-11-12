<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminMailInboxBean
 * @author Hugues
 * @since 1.22.11.11
 * @version 1.22.11.11
 */
class WpPageAdminMailInboxBean extends WpPageAdminMailBean
{
    public function __construct()
    {
        parent::__construct();
        
        $spanAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $buttonContent = $this->getBalise(self::TAG_SPAN, self::LABEL_INBOX, $spanAttributes);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * @since 1.22.11.11
     * @version 1.22.11.11
     */
    public function getOngletContent()
    {
        ///////////////////////////////////////////////////////////////////
        // Bouton de création d'un nouveau message
        $urlElements = array(self::CST_SUBONGLET=>self::CST_WRITE);
        $href = $this->getOngletUrl($urlElements);
        $strButtonRetour = $this->getLink('Rédiger un message', $href, 'btn btn-primary btn-block mb-3');
        
        ///////////////////////////////////////////////////////////////////
        // Récupération des mails du dossier affiché pour l'utilisateur courant
        $objMailFolder = $this->CopsMailServices->getMailFolder($this->subOnglet);
        $argRequest = array(
            self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
            self::FIELD_TO_ID => $this->CopsPlayer->getField(self::FIELD_ID),
        );
        $objsCopsMailJoint = $this->CopsMailServices->getMailJoints($argRequest);
        
        $strContent = '';
        if (empty($objsCopsMailJoint)) {
            $strContent = '<tr><td class="text-center" colspan="3">'.self::LABEL_NO_RESULT.'</td></tr>';
        } else {
            ///////////////////////////////////////////////////////////////////
            // Pagination
            $strPagination = $this->buildPagination($objsCopsMailJoint);
            foreach ($objsCopsMailJoint as $objCopsMailJoint) {
                $strContent .= $objCopsMailJoint->getBean()->getInboxRow();
            }
        }
        ///////////////////////////////////////////////////////////////////
        
        //////////////////////////////////////////////////////////////
        // Construction de la liste
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-messages.php';
        $attributes = array(
            // Titre du dossier affiché
            self::LABEL_INBOX,
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->slugSubOnglet,
        );
        $mainContent = $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-inbox',
            // Le bouton éventuel de création / retour...
            $strButtonRetour,
            // Le nom du bloc du menu de gauche
            self::LABEL_MESSAGERIE,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @param array $objs
     * @return string
     * @since 1.22.10.27
     * @version 1.22.10.27
     */
    public function buildPagination(&$objs)
    {
        $nbItems = count($objs);
        $nbItemsPerPage = 10;
        $nbPages = ceil($nbItems/$nbItemsPerPage);
        $strPagination = '';
        if ($nbPages>1) {
            // Le bouton page précédente
            $label = $this->getIcon('caret-left');
            if ($this->curPage!=1) {
                $btnClass = '';
                $href = $this->getRefreshUrl(array(self::CST_CURPAGE=>$this->curPage-1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= $this->getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, array($firstItem+1, $lastItem, $nbItems));
            
            // Le bouton page suivante
            $label = $this->getIcon('caret-right');
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $href = $this->getRefreshUrl(array(self::CST_CURPAGE=>$this->curPage+1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= self::CST_NBSP.$this->getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }
    
    /**
     * @param array $urlElements
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getRefreshUrl($urlElements=array())
    {
        // Si catSlug est défini et non présent dans $urlElements, il doit être repris.
        if ($this->catSlug!='' && !isset($urlElements[self::CST_CAT_SLUG])) {
            $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
        }
        // Si curPage est défini et non présent dans $urlElements, il doit être repris.
        if ($this->curPage!='' && !isset($urlElements[self::CST_CURPAGE])) {
            $urlElements[self::CST_CURPAGE] = $this->curPage;
        }
        return $this->getUrl($urlElements);
    }
}
