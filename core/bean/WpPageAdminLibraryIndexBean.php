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

        // On initialise l'éventuelle pagination & on ajoute à l'url de Refresh
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Définition de l'url de Refresh
        $this->urlRefresh = $this->getRefreshUrl();
        
        // Est-on sur la page principale ou le filtre Nature est-il activé ?
        $this->blnShowColNature = ($this->catSlug=='');
        
        // On initialise l'éventuelle action : write
        $this->action = $this->initVar(self::CST_ACTION);
        // Selon la valeur, on initialise le panneau à afficher
        if ($this->hasCopsEditor
            && ($this->action==self::CST_WRITE || isset($this->urlParams[self::CST_WRITE_ACTION]))) {
            $this->panel = self::CST_EDIT;
        } else {
            $this->panel = self::CST_LIST;
        }
        
        // On initialise l'éventuel id
        $id = $this->initVar(self::FIELD_ID);
        // On initialise l'éventuel objet concerné
        $this->objIndex = $this->objCopsIndexServices->getIndex($id);
        
        // On initialise l'id Wordpress de la Catégory "Index"
        $this->wpCategoryId = 48;

        $urlElements = array(
            self::CST_SUBONGLET => self::CST_LIB_INDEX,
        );
        $buttonContent = $this->getLink('Index', $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->catSlug==''?$this->btnDisabled:$this->btnDark));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        
        if ($this->catSlug=='') {
            $this->objCopsIndexNature = new CopsIndexNature();
        } else {
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField('slug', $this->catSlug);
            $name = $this->objWpCategory->getField('name');
            $this->objCopsIndexNature = $this->objCopsIndexServices->getCopsIndexNatureByName($name);
            
            $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
            $buttonContent = $this->getLink($name, $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
            $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
            $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        }
        
    }
    
    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getOngletContent()
    {
        /////////////////////////////////////////
        // On doit gérer une modification ou une création.
        if ($this->hasCopsEditor && isset($this->urlParams[self::CST_WRITE_ACTION])) {
            $this->dealWithWriteAction();
        }
        $blnExtraButton = ($this->panel==self::CST_EDIT);
        
        /////////////////////////////////////////
        // Le bouton de création ou d'annulation.
        $strButtonCreation = '';
        $classe = 'btn btn-primary mb-3'.($blnExtraButton ? ' col-6' : ' btn-block');
        if ($this->panel!=self::CST_LIST) {
            $label = $this->getIcon('angles-left').self::CST_NBSP.self::LABEL_RETOUR;
            $strButtonCreation .= $this->getLink($label, $this->getRefreshUrl(), $classe);
        }
        if ($this->hasCopsEditor || $blnExtraButton) {
            $href = $this->getRefreshUrl(array(self::CST_ACTION=>self::CST_WRITE));
            $strButtonCreation .= $this->getLink(self::LABEL_CREER_ENTREE, $href, $classe);
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        // Pour ça, on doit récupérer les catégories Wp qui sont des enfants de la catégorie Index.
        $menuContent = '';
        $objsCategoryMenu = $this->objWpCategoryServices->getCategoryChildren($this->wpCategoryId);
        usort($objsCategoryMenu, [WpCategory::class, 'compCategories']);
        while (!empty($objsCategoryMenu)) {
            $objWpCategory = array_shift($objsCategoryMenu);
            $blnSelected = ($this->catSlug==$objWpCategory->getField('slug'));
            $menuContent .= $objWpCategory->getBean()->getCategoryNavItem($this->getUrl(), 'book', $blnSelected);
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Définition du contenu. Par défaut, la liste des entrées, éventuellement filtrée par Nature
        // Mais peut aussi être l'édition ou la création d'une entrée
        if ($this->panel==self::CST_EDIT) {
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
            self::LABEL_CATEGORIES,
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
        $arrFields = array(self::FIELD_NOM_IDX, self::FIELD_NATURE_ID, self::FIELD_DESCRIPTION_PJ);
        if (self::isAdmin()) {
            $arrFields = array_merge(
                $arrFields,
                array(self::FIELD_DESCRIPTION_MJ, self::FIELD_REFERENCE, self::FIELD_CODE)
            );
        }
        while (!empty($arrFields)) {
            $field = array_shift($arrFields);
            $this->objIndex->setField($field, stripslashes($this->urlParams[$field]));
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
                self::ATTR_VALUE => $objIndexNature->getField(self::FIELD_ID_IDX_NATURE),
            );
            if ($objIndexNature->getField(self::FIELD_ID_IDX_NATURE)==$this->objIndex->getField(self::FIELD_NATURE_ID)
                || $this->objWpCategory->getField('name')==$objIndexNature->getField(self::FIELD_NOM_IDX_NATURE)) {
                $optionAttributes[self::CST_SELECTED] = self::CST_SELECTED;
            }
            $nomNature = $objIndexNature->getField(self::FIELD_NOM_IDX_NATURE);
            $strSelect .= $this->getBalise(self::TAG_OPTION, $nomNature, $optionAttributes);
        }
        
        $attributes = array(
            $this->objIndex->getField(self::FIELD_ID),
            $this->urlRefresh,
            $this->objIndex->getField(self::FIELD_NOM_IDX),
            $strSelect,
            $this->objIndex->getField(self::FIELD_DESCRIPTION_PJ),
            (self::isAdmin() ? $this->objIndex->getField(self::FIELD_DESCRIPTION_MJ) : ''),
            (self::isAdmin() ? $this->objIndex->getField(self::FIELD_REFERENCE) : ''),
            (self::isAdmin() ? '' : ' d-none'),
            ($this->objIndex->getField(self::FIELD_CODE)==2 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField(self::FIELD_CODE)==1 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField(self::FIELD_CODE)==0 ? ' '.self::CST_CHECKED : ''),
            ($this->objIndex->getField(self::FIELD_CODE)==-1 ? ' '.self::CST_CHECKED : ''),
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
        $titre = ($this->blnShowColNature ? self::LABEL_INDEX : $this->objWpCategory->getField('name'));
        
        // On va construire le Header du tableau
        $thAttributes = array(
            self::ATTR_CLASS => 'mailbox-name',
        );
        $headerContent  = $this->getTh(self::LABEL_NOM, $thAttributes);
        if ($this->blnShowColNature) {
            $thAttributes[self::ATTR_STYLE] = 'width:150px;';
            $headerContent .= $this->getTh(self::LABEL_NATURE, $thAttributes);
            unset($thAttributes[self::ATTR_STYLE]);
        }
        $headerContent .= $this->getTh(self::LABEL_DESCRIPTION, $thAttributes);
        if ($this->hasCopsEditor) {
            $thAttributes = array(self::ATTR_STYLE=>'width:60px;');
            $headerContent .= $this->getTh(self::CST_NBSP, $thAttributes);
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
                self::FIELD_NATURE_ID => $objCopsIndexNature->getField(self::FIELD_ID_IDX_NATURE),
            );
        }
        $objsCopsIndex = $this->copsIndexServices->getIndexes($attributes);
        
        $listContent = '';
        if (empty($objsCopsIndex)) {
            $listContent = '<tr><td class="text-center" colspan="3">'.self::LABEL_NO_RESULT.'</td></tr>';
        } else {
            /////////////////////////////////////////////:
            // Pagination
            $strPagination = $this->buildPagination($objsCopsIndex);
            /////////////////////////////////////////////:
            foreach ($objsCopsIndex as $objCopsIndex) {
                $url = $this->getRefreshUrl();
                $bln = $this->blnShowColNature;
                $listContent .= $objCopsIndex->getBean()->getCopsIndexRow($url, $bln, $this->hasCopsEditor);
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Toolbar & Pagination
        // Bouton pour recharger la liste
        $label = $this->getLink($this->getIcon('arrows-rotate'), $this->getRefreshUrl(), self::CST_TEXT_WHITE);
        $btnAttributes = array(self::ATTR_TITLE => self::LABEL_REFRESH_LIST);
        $strToolBar = $this->getButton($label, $btnAttributes);
        
        // Bouton pour créer une nouvelle entrée, si droits d'édition
        if ($this->hasCopsEditor) {
            $href = $this->getRefreshUrl(array(self::CST_ACTION=>self::CST_WRITE));
            $label = $this->getLink($this->getIcon('square-plus'), $href, self::CST_TEXT_WHITE);
            $btnAttributes = array(self::ATTR_TITLE => self::LABEL_CREER_ENTREE);
            $strToolBar .= self::CST_NBSP.$this->getButton($label, $btnAttributes);
        }
        // Bouton pour effectuer un export Excel
        $btnAttributes = array(
            self::ATTR_CLASS => self::AJAX_ACTION,
            self::ATTR_TITLE => self::LABEL_EXPORT_LIST,
            self::ATTR_DATA => array(
                self::ATTR_DATA_TRIGGER => 'click',
                self::ATTR_DATA_AJAX => 'csvExport',
                strtolower(self::FIELD_NATURE_ID) => $this->objCopsIndexNature->getField(self::FIELD_ID_IDX_NATURE),
            ),
        );
        $strToolBar .= self::CST_NBSP.$this->getButton($this->getIcon('download'), $btnAttributes);
        // Ajout de la pagination
        $strToolBar .= $this->getDiv($strPagination, array(self::ATTR_CLASS=>'float-right'));
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

