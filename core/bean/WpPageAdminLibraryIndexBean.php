<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryIndexBean
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.22.10.21
 */
class WpPageAdminLibraryIndexBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objWpCategoryServices = new WpCategoryServices();
        $this->objCopsIndexServices  = new CopsIndexServices();
        
        // L'utilisateur a-t-il les droits d'édition ?
        $this->hasCopsEditor = self::isCopsEditor();

        // Définition de l'url locale
        $this->url = $this->getSubOngletUrl();
        $this->urlRefresh = $this->url.'&amp;catslug='.$this->catSlug;
        // On initialise l'éventuelle pagination & on ajoute à l'url de Refresh
        $this->curPage = $this->initVar('curPage', 1);
        $this->strExtraCurPage = '&amp;curPage=';
        if ($this->curPage!=1) {
            $this->urlRefresh .= $this->strExtraCurPage.$this->curPage;
        }
        // Est-on sur la page principale ou le filtre Nature est-il activé ?
        $this->blnShowColNature = ($this->catSlug=='');
        
        // On initialise l'éventuelle action : write
        $this->action = $this->initVar(self::CST_ACTION);
        // Selon la valeur, on initialise le panneau à afficher
        if ($this->hasCopsEditor
            && ($this->action==self::CST_WRITE || isset($this->urlParams[self::CST_WRITE_ACTION]))) {
            $this->panel = 'edit';
        } else {
            $this->panel = 'list';
        }
        
        // On initialise l'éventuel id
        $id = $this->initVar(self::FIELD_ID);
        // On initialise l'éventuel objet concerné
        $this->objIndex = $this->objCopsIndexServices->getIndex($id);
        
        // On initialise l'id Wordpress de la Catégory "Index"
        $this->wpCategoryId = 48;
    }
    
    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getSubongletContent()
    {
        /////////////////////////////////////////
        // On doit gérer une modification ou une création.
        if ($this->hasCopsEditor && isset($this->urlParams[self::CST_WRITE_ACTION])) {
            $this->dealWithWriteAction();
        }
        $blnExtraButton = ($this->panel=='edit');
        
        /////////////////////////////////////////
        // Le bouton de création ou d'annulation.
        $strButtonCreation = '';
        $classe = 'btn btn-primary mb-3'.($blnExtraButton ? ' col-6' : ' btn-block');
        if ($this->panel!='list') {
            $label = $this->getIcon('angles-left').'&nbsp;Retour';
            $strButtonCreation .= $this->getLink($label, $this->urlRefresh, $classe);
        }
        if ($this->hasCopsEditor || $blnExtraButton) {
            $strButtonCreation .= $this->getLink('Créer une entrée', $this->urlRefresh.'&amp;action=write', $classe);
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        // Pour ça, on doit récupérer les catégories Wp qui sont des enfants de la catégorie Index.
        $menuContent = '';
        $objsCategoryMenu = $this->objWpCategoryServices->getCategoryChildren($this->wpCategoryId);
        while (!empty($objsCategoryMenu)) {
            $objWpCategory = array_shift($objsCategoryMenu);
            $blnSelected = ($this->catSlug==$objWpCategory->getField('slug'));
            $menuContent .= $objWpCategory->getBean()->getCategoryNavItem($this->url, 'book', $blnSelected);
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Définition du contenu. Par défaut, la liste des entrées, éventuellement filtrée par Nature
        // Mais peut aussi être l'édition ou la création d'une entrée
        if ($this->panel=='edit') {
            $mainContent = $this->getEditContent();
        } else {// list
            $mainContent = $this->getListContent();
        }
        /////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-index',
            // Le bouton éventuel de création / retour...
            $strButtonCreation,
            // Le nom du bloc du menu de gauche
            'Catégories',
            // La liste des éléments du menu de gauche
            $menuContent,
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function dealWithWriteAction()
    {
        $this->objIndex->setField('nomIdx', stripslashes($this->urlParams['nomIdx']));
        $this->objIndex->setField('natureId', stripslashes($this->urlParams['natureId']));
        $this->objIndex->setField('descriptionPJ', stripslashes($this->urlParams['descriptionPJ']));
        if (self::isAdmin()) {
            $this->objIndex->setField('descriptionMJ', stripslashes($this->urlParams['descriptionMJ']));
            $this->objIndex->setField('reference', stripslashes($this->urlParams['reference']));
            $this->objIndex->setField('code', stripslashes($this->urlParams['code']));
        }
        if ($this->objIndex->checkFields()) {
            if ($this->objIndex->getField(self::FIELD_ID)=='') {
                $this->objCopsIndexServices->insertIndex($this->objIndex);
            } else {
                $this->objCopsIndexServices->updateIndex($this->objIndex);
            }
        }
    }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getEditContent()
    {
        $urlTemplateEdit = 'web/pages/public/fragments/public-fragments-section-library-index-edit.php';
        
        $objsIndexNature = $this->objCopsIndexServices->getIndexNatures();
        $strSelect = '';
        while (!empty($objsIndexNature)) {
            $objIndexNature = array_shift($objsIndexNature);
            $optionAttributes = array(
                self::ATTR_VALUE => $objIndexNature->getField('idIdxNature'),
            );
            if ($objIndexNature->getField('idIdxNature')==$this->objIndex->getField('natureId')
                || $this->objWpCategory->getField('name')==$objIndexNature->getField('nomIdxNature')) {
                $optionAttributes[self::CST_SELECTED] = self::CST_SELECTED;
            }
            $nomNature = $objIndexNature->getField('nomIdxNature');
            $strSelect .= $this->getBalise(self::TAG_OPTION, $nomNature, $optionAttributes);
        }
        
        $attributes = array(
            $this->objIndex->getField(self::FIELD_ID),
            $this->urlRefresh,
            $this->objIndex->getField('nomIdx'),
            $strSelect,
            $this->objIndex->getField('descriptionPJ'),
            (self::isAdmin() ? $this->objIndex->getField('descriptionMJ') : ''),
            (self::isAdmin() ? $this->objIndex->getField('reference') : ''),
            (self::isAdmin() ? '' : ' d-none'),
            ($this->objIndex->getField('code')==2 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField('code')==1 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField('code')==0 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField('code')==-1 ? ' '.self::CST_CHECKED : ''),
        );
        return $this->getRender($urlTemplateEdit, $attributes);
    }
    
    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getListContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments à afficher.
        // Les données sont extraites de la table wp_7_cops_index.
        // Si aucune catégorie n'est sélectionnée, on affiche tout
        // Sinon, on filtre via la catégorie, le nom de la catégorie doit correspondre
        // à la valeur du champ nomIdxNature de la table wp_7_cops_index_nature
        $urlTemplateList = 'web/pages/public/fragments/public-fragments-section-onglet-list.php';
        $titre = ($this->blnShowColNature ? 'Index' : $this->objWpCategory->getField('name'));
        
        // On va construire le Header du tableau
        $thAttributes = array(
            'scope'=>'col',
            self::ATTR_CLASS => 'mailbox-name',
        );
        $headerContent  = $this->getBalise(self::TAG_TH, 'Nom', $thAttributes);
        if ($this->blnShowColNature) {
            $thAttributes['style'] = 'width:150px;';
            $headerContent .= $this->getBalise(self::TAG_TH, 'Nature', $thAttributes);
            unset($thAttributes['style']);
        }
        $headerContent .= $this->getBalise(self::TAG_TH, 'Description', $thAttributes);
        if ($this->hasCopsEditor) {
            $thAttributes = array('style'=>'width:60px;', 'scope'=>'col');
            $headerContent .= $this->getBalise(self::TAG_TH, '&nbsp;', $thAttributes);
        }
        $header = $this->getBalise(self::TAG_TR, $headerContent);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        // Si on a une Catégorie spécifique, on va chercher son équivalent Nature dans la base.
        if (!$this->blnShowColNature) {
            $name = $this->objWpCategory->getField('name');
            $objCopsIndexNature = $this->copsIndexServices->getCopsIndexNatureByName($name);
            $attributes[self::SQL_WHERE_FILTERS] = array(
                'natureId' => $objCopsIndexNature->getField('idIdxNature'),
            );
        }
        $objsCopsIndex = $this->copsIndexServices->getIndexes($attributes);
        
        $listContent = '';
        if (empty($objsCopsIndex)) {
            $listContent = '<tr><td class="text-center" colspan="3">Aucune entrée.</td></tr>';
        } else {
            /////////////////////////////////////////////:
            // Pagination
            $strPagination = $this->buildPagination($objsCopsIndex);
            /////////////////////////////////////////////:
            foreach ($objsCopsIndex as $objCopsIndex) {
                $listContent .= $objCopsIndex->getBean()->getCopsIndexRow($this->blnShowColNature, $this->hasCopsEditor);
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Toolbar & Pagination
        // Bouton pour recharger la liste
        $label = $this->getLink($this->getIcon('arrows-rotate'), $this->urlRefresh, self::CST_TEXT_WHITE);
        $btnAttributes = array('title' => 'Rafraîchir la liste');
        $strToolBar = $this->getButton($label, $btnAttributes);
        
        // Bouton pour créer une nouvelle entrée, si droits d'édition
        if ($this->hasCopsEditor) {
            $href = $this->urlRefresh.'&amp;action=write';
            $label = $this->getLink($this->getIcon('arrows-plus'), $href, self::CST_TEXT_WHITE);
            $btnAttributes = array('title' => 'Créer une entrée');
            $strToolBar .= $this->getButton($label, $btnAttributes);
        }
        // Bouton pour effectuer un export Excel
        $btnAttributes = array(
            self::ATTR_CLASS => self::AJAX_ACTION,
            'title' => 'Exporter la liste',
            'data-trigger' => 'click',
            'data-ajax' => 'csvExport',
            'data-natureid' => $this->objCopsIndexNature->getField('idIdxNature'),
        );
        $strToolBar .= '&nbsp;'.$this->getButton($this->getIcon('download'), $btnAttributes);
        // Ajout de la pagination
        $strToolBar .= $this->getBalise(self::TAG_DIV, $strPagination, array(self::ATTR_CLASS=>'float-right'));
        /////////////////////////////////////////
        
        $listAttributes = array(
            $titre,
            $strToolBar,
            $header,
            $listContent,
        );
        /////////////////////////////////////////
        
        return $this->getRender($urlTemplateList, $listAttributes);
    }

    /**
     * 
     * @param array $objs
     * @return string
     * @since v1.22.10.27
     * @version v1.22.10.27
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
                $href = $this->urlRefresh.$this->strExtraCurPage.($this->curPage-1);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = 'disabled text-white';
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= $this->getButton($btnContent, $btnAttributes).'&nbsp;';
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= ($firstItem+1).' - '.$lastItem.' sur '.$nbItems;
            
            // Le bouton page suivante
            $label = $this->getIcon('caret-right');
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $href = $this->urlRefresh.$this->strExtraCurPage.($this->curPage+1);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = ' disabled text-white';
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= '&nbsp;'.$this->getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }
}
