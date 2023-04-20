<?php
namespace core\bean;

use core\domain\CopsIndexNatureClass;
use core\domain\WpCategoryClass;
use core\domain\MySQLClass;

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
        
        // L'utilisateur a-t-il les droits d'édition ?
        $this->hasCopsEditor = static::isCopsEditor();

        // On initialise l'éventuelle pagination & on ajoute à l'url de Refresh
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // On initialise l'éventuelle action : write
        $this->action = $this->initVar(self::CST_ACTION);
        // Définition de l'url de Refresh
        $this->urlRefresh = $this->getRefreshUrl();
        
        // Est-on sur la page principale ou le filtre Nature est-il activé ?
        $this->blnShowColNature = ($this->catSlug=='');
        
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
        $this->objIndex = $this->objCopsIndexServices->getIndexReference($id);

        // On initialise l'id Wordpress de la Catégory "Index"
        $this->wpCategoryId = 48;

        $urlElements = [self::CST_SUBONGLET => self::CST_LIB_INDEX];
        $buttonContent = $this->getLink('Index', $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = [self::ATTR_CLASS=>($this->catSlug==''?$this->btnDisabled:$this->btnDark)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        
        if ($this->catSlug=='') {
            $this->objCopsIndexNature = new CopsIndexNatureClass();
        } else {
            $this->objWpCategory = $this->wpCategoryServices->getCategoryByField('slug', $this->catSlug);
            $name = $this->objWpCategory->getField('name');
            $this->objCopsIndexNature = $this->objCopsIndexServices->getCopsIndexNatureByName($name);
            
            $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
            $buttonContent = $this->getLink($name, $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
            $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
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
        $classe = 'btn btn-primary mb-3'.($blnExtraButton ? ' col' : ' btn-block');
        if ($this->panel!=self::CST_LIST) {
            $label = $this->getIcon('angles-left').self::CST_NBSP.self::LABEL_RETOUR;
            $strButtonCreation .= $this->getLink($label, $this->getRefreshUrl(), $classe);
        }
        if ($this->hasCopsEditor || $blnExtraButton) {
            $href = $this->getRefreshUrl([self::CST_ACTION=>self::CST_WRITE]);
            $strButtonCreation .= $this->getLink(self::LABEL_CREER_ENTREE, $href, $classe);
        }
        $strButtonCreation = $this->getDiv($strButtonCreation, [self::ATTR_CLASS=>'btn-group d-flex']);
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        // Pour ça, on doit récupérer les catégories Wp qui sont des enfants de la catégorie Index.
        $menuContent = '';
        $objsCategoryMenu = $this->objWpCategoryServices->getCategoryChildren($this->wpCategoryId);
        usort($objsCategoryMenu, [WpCategoryClass::class, 'compCategories']);
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
        
        $urlTemplate = self::WEB_PPFS_ONGLET;
        $attributes = [
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
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function dealWithWriteAction()
    {
        $this->objIndex = $this->objCopsIndexServices->getIndexReference($this->initVar(self::FIELD_ID));

        // On défini les champs éditables par n'importe qui.
        $arrFields = [self::FIELD_NOM_IDX, self::FIELD_PRENOM_IDX, self::FIELD_AKA_IDX, self::FIELD_NATURE_IDX_ID, self::FIELD_DESCRIPTION_PJ];
        // On ajoute les champs qui nécessite des droits spécifiques
        if ($this->hasCopsEditor) {
            $arrFields = array_merge(
                $arrFields,
                [self::FIELD_DESCRIPTION_MJ, self::FIELD_CODE]
            );
        }
        // On met à jour l'objet
        while (!empty($arrFields)) {
            $field = array_shift($arrFields);
            $this->objIndex->setField($field, stripslashes($this->urlParams[$field]));
        }
        // On vérifie les champs et on créé/met à jour l'entrée
        if ($this->objIndex->isFieldsValid()) {
            if ($this->objIndex->getField(self::FIELD_ID_IDX_REF)=='') {
                $this->objCopsIndexServices->insertIndexReference($this->objIndex);
            } else {
                $this->objCopsIndexServices->updateIndexReference($this->objIndex);
            }
        }
    }

    /**
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getEditContent()
    {
        $urlTemplateEdit = self::WEB_PPFF_LIBRARY_INDEX;

        $objsIndexNature = $this->objCopsIndexServices->getIndexNatures();
        $strSelect = '';
        while (!empty($objsIndexNature)) {
            $objIndexNature = array_shift($objsIndexNature);
            $optionAttributes = [self::ATTR_VALUE => $objIndexNature->getField(self::FIELD_ID_IDX_NATURE)];

            $idNature = $objIndexNature->getField(self::FIELD_ID_IDX_NATURE);
            $nomNature = $objIndexNature->getField(self::FIELD_NOM_IDX_NATURE);
            if ($idNature==$this->objIndex->getField(self::FIELD_NATURE_IDX_ID)) {
// || $this->objWpCategory->getField(self::WP_NAME)==$nomNature) {
                $optionAttributes[self::CST_SELECTED] = self::CST_SELECTED;
            }
            $strSelect .= $this->getBalise(self::TAG_OPTION, $nomNature, $optionAttributes);
        }
        
        $attributes = [$this->objIndex->getField(self::FIELD_ID_IDX_REF), $this->urlRefresh, $this->objIndex->getField(self::FIELD_NOM_IDX), $strSelect, $this->objIndex->getField(self::FIELD_DESCRIPTION_PJ), ($this->hasCopsEditor ? $this->objIndex->getField(self::FIELD_DESCRIPTION_MJ) : ''), ($this->hasCopsEditor ? $this->objIndex->getBean()->getReferences() : ''), ($this->hasCopsEditor ? '' : ' d-none'), ($this->objIndex->getField(self::FIELD_CODE)==2 ? ' '.self::CST_CHECKED : ''), ($this->objIndex->getField(self::FIELD_CODE)==1 ? ' '.self::CST_CHECKED : ''), ($this->objIndex->getField(self::FIELD_CODE)==0 ? ' '.self::CST_CHECKED : ''), ($this->objIndex->getField(self::FIELD_CODE)==-1 ? ' '.self::CST_CHECKED : ''), $this->objIndex->getField(self::FIELD_PRENOM_IDX), $this->objIndex->getField(self::FIELD_AKA_IDX)];
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
        $urlTemplateList = self::WEB_PPFS_ONGLET_LIST;
        $titre = ($this->blnShowColNature ? self::LABEL_INDEX : $this->objWpCategory->getField(self::WP_NAME));
        
        // On va construire le Header du tableau
        $thAttributes = [self::ATTR_CLASS => 'mailbox-name'];
        $headerContent  = $this->getTh(self::LABEL_NOM, $thAttributes);
        if ($this->blnShowColNature) {
            $thAttributes[self::ATTR_STYLE] = 'width:100px;';
            $headerContent .= $this->getTh(self::LABEL_NATURE, $thAttributes);
            unset($thAttributes[self::ATTR_STYLE]);
        }
        $thAttributes[self::ATTR_STYLE] = 'width:250px;';
        $headerContent .= $this->getTh(self::LABEL_DESCRIPTION, $thAttributes);
        if ($this->hasCopsEditor) {
            $headerContent .= $this->getTh(self::LABEL_DESCRIPTION, $thAttributes);
            $thAttributes[self::ATTR_STYLE] = 'width:150px;';
        }
        $headerContent .= $this->getTh(self::LABEL_REFERENCE, $thAttributes);
        unset($thAttributes[self::ATTR_STYLE]);
        if ($this->hasCopsEditor) {
            $thAttributes = [self::ATTR_STYLE=>'width:60px;'];
            $headerContent .= $this->getTh(self::CST_NBSP, $thAttributes);
        }
        $header = $this->getBalise(self::TAG_TR, $headerContent);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        // Si on a une Catégorie spécifique, on va chercher son équivalent Nature dans la base.
        $attributes = [];
        if (!$this->blnShowColNature) {
            $name = $this->objWpCategory->getField(self::WP_NAME);
            $objCopsIndexNature = $this->objCopsIndexServices->getCopsIndexNatureByName($name);
            $attributes[self::SQL_WHERE_FILTERS] = [self::FIELD_NATURE_IDX_ID => $objCopsIndexNature->getField(self::FIELD_ID_IDX_NATURE)];
        }
        $objsCopsIndex = $this->objCopsIndexServices->getIndexReferences($attributes);
        
        $listContent = '';
        $strPagination = '';
        if (empty($objsCopsIndex)) {
            $listContent  = '<tr><td class="text-center" colspan="'.($this->hasCopsEditor?6:5).'">';
            $listContent .= self::LABEL_NO_RESULT.'</td></tr>';
        } else {
            /////////////////////////////////////////////:
            // Pagination
            $strPagination = $this->buildPagination($objsCopsIndex);
            /////////////////////////////////////////////:
            foreach ($objsCopsIndex as $objCopsIndex) {
                $url = $this->getRefreshUrl();
                $bln = $this->blnShowColNature;
                $listContent .= $objCopsIndex->getBean()->getCopsIndexReferenceRow($url, $bln, $this->hasCopsEditor);
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Toolbar & Pagination
        // Bouton pour recharger la liste
        $label = $this->getLink($this->getIcon('arrows-rotate'), $this->getRefreshUrl(), self::CST_TEXT_WHITE);
        $btnAttributes = [self::ATTR_TITLE => self::LABEL_REFRESH_LIST];
        $strToolBar = $this->getButton($label, $btnAttributes);
        
        // Bouton pour créer une nouvelle entrée, si droits d'édition
        if ($this->hasCopsEditor) {
            $href = $this->getRefreshUrl([self::CST_ACTION=>self::CST_WRITE]);
            $label = $this->getLink($this->getIcon('square-plus'), $href, self::CST_TEXT_WHITE);
            $btnAttributes = [self::ATTR_TITLE => self::LABEL_CREER_ENTREE];
            $strToolBar .= self::CST_NBSP.$this->getButton($label, $btnAttributes);
        }
        // Bouton pour effectuer un export Excel
        $btnAttributes = [self::ATTR_CLASS => self::AJAX_ACTION, self::ATTR_TITLE => self::LABEL_EXPORT_LIST, self::ATTR_DATA => [self::ATTR_DATA_TRIGGER => 'click', self::ATTR_DATA_AJAX => 'csvExport']];
        $strToolBar .= self::CST_NBSP.$this->getButton($this->getIcon('download'), $btnAttributes);
        // Ajout de la pagination
        $strToolBar .= $this->getDiv($strPagination, [self::ATTR_CLASS=>'float-right']);
        /////////////////////////////////////////
        
        $listAttributes = [$titre, $strToolBar, $header, $listContent];
        /////////////////////////////////////////
        
        return $this->getRender($urlTemplateList, $listAttributes);
    }

    /**
     * @param array $urlElements
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getRefreshUrl($urlElements=[])
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

