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
        if ($this->curPage!=1) {
            $this->urlRefresh .= '&amp;curPage='.$this->curPage;
        }
        // Est-on sur la page principale ou le filtre Nature est-il activé ?
        $this->blnShowColNature = ($this->catSlug=='');
        
        // On initialise l'éventuelle action : write
        $this->action = $this->initVar(self::CST_ACTION);
        // Selon la valeur, on initialise le panneau à afficher
        if ($this->hasCopsEditor && ($this->action==self::CST_WRITE || isset($this->urlParams[self::CST_WRITE_ACTION]))) {
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
        if ($this->panel!='list') {
            $aAttributes = array(
                self::ATTR_HREF => $this->urlRefresh,
                self::ATTR_CLASS => 'btn btn-primary mb-3'.($blnExtraButton ? ' col-6' : ' btn-block'),
            );
            $label = '<i class="fa-solid fa-angles-left"></i> Retour';
            $strButtonCreation = $this->getBalise(self::TAG_A, $label, $aAttributes);
        }
        if ($this->hasCopsEditor || $blnExtraButton) {
            $aAttributes = array(
                self::ATTR_HREF => $this->urlRefresh.'&amp;action=write',
                self::ATTR_CLASS => 'btn btn-primary mb-3'.($blnExtraButton ? ' col-6' : ' btn-block'),
            );
            $strButtonCreation .= $this->getBalise(self::TAG_A, 'Créer une entrée', $aAttributes);
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
            $strSelect .= $this->getBalise(self::TAG_OPTION, $objIndexNature->getField('nomIdxNature'), $optionAttributes);
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
            $listContent = '<tr><td class="text-center" colspan="3">Aucune entrée.<br></td></tr>';
        } else {
            /////////////////////////////////////////////:
            // Pagination
            $nbItems = count($objsCopsIndex);
            $nbItemsPerPage = 10;
            $nbPages = ceil($nbItems/$nbItemsPerPage);
            $strPagination = '';
            if ($nbPages>1) {
                if ($this->curPage!=1) {
                    $btnClass = '';
                    $aAttributes = array(
                        self::ATTR_HREF => $this->urlRefresh.'&amp;curPage='.($this->curPage-1),
                        self::ATTR_CLASS => 'text-white',
                    );
                    $label = '<i class="fa-solid fa-caret-left"></i>';
                    $btnContent = $this->getBalise(self::TAG_A, $label, $aAttributes);
                } else {
                    $btnClass = ' disabled text-white';
                    $btnContent = '<i class="fa-solid fa-caret-left"></i>';
                }
                $strPagination .= '<button type="button" class="btn btn-default btn-sm'.$btnClass.'">';
                $strPagination .= $btnContent.'</button>&nbsp;';
                $firstItem = ($this->curPage-1)*$nbItemsPerPage;
                $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
                $strPagination .= ($firstItem+1).' - '.$lastItem.' sur '.$nbItems;
                if ($this->curPage!=$nbPages) {
                    $btnClass = '';
                    $aAttributes = array(
                        self::ATTR_HREF => $this->urlRefresh.'&amp;curPage='.($this->curPage+1),
                        self::ATTR_CLASS => 'text-white',
                    );
                    $label = '<i class="fa-solid fa-caret-right"></i>';
                    $btnContent = $this->getBalise(self::TAG_A, $label, $aAttributes);
                } else {
                    $btnClass = ' disabled text-white';
                    $btnContent = '<i class="fa-solid fa-caret-right"></i>';
                }
                $strPagination .= '&nbsp;<button type="button" class="btn btn-default btn-sm'.$btnClass;
                $strPagination .= '">'.$btnContent.'</button>';
                $objsCopsIndex = array_slice($objsCopsIndex, $firstItem, $nbItemsPerPage);
            }
            /////////////////////////////////////////////:
            foreach ($objsCopsIndex as $objCopsIndex) {
                $listContent .= $objCopsIndex->getBean()->getCopsIndexRow($this->blnShowColNature, $this->hasCopsEditor);
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Toolbar & Pagination
        // Bouton pour recharger la liste
        $strToolBar  = '<button type="button" class="btn btn-default btn-sm" title="Rafraîchir la liste"><a href="';
        $strToolBar .= $this->urlRefresh.'" class="text-white"><i class="fa-solid fa-arrows-rotate"></i></a></button>';
        // Bouton pour créer une nouvelle entrée, si droits d'édition
        if ($this->hasCopsEditor) {
            $strToolBar .= '&nbsp;<button type="button" class="btn btn-default btn-sm" title="Créer une entrée">';
            $strToolBar .= '<a href="'.$this->urlRefresh.'&amp;action=write" class="text-white">';
            $strToolBar .= '<i class="fa-solid fa-square-plus"></i></a></button>';
        }
        // Bouton pour effectuer un export Excel
        $strToolBar .= '&nbsp;<button type="button" class="btn btn-default btn-sm ajaxAction"';
        $strToolBar .= ' title="Exporter la liste" data-trigger="click" data-ajax="csvExport"';
        $strToolBar .= ' data-natureid="'.$this->objCopsIndexNature->getField('idIdxNature').'">';
        $strToolBar .= '<i class="fa-solid fa-download"></i></button>';
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
}
