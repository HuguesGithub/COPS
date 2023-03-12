<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryBddBean
 * @author Hugues
 * @since 1.22.10.29
 * @version 1.22.10.29
 */
class WpPageAdminLibraryBddBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objWpPostServices = new WpPostServices();
        
        $urlElements = array(
            self::CST_SUBONGLET => self::CST_LIB_BDD,
        );

        $buttonContent = $this->getLink('Bases de données', $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->catSlug==''?$this->btnDisabled:$this->btnDark));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        
        if ($this->catSlug!='') {
            $attributes = array(
                'name' => $this->catSlug,
            );
            $objsWpPost = $this->objWpPostServices->getPosts($attributes);
            $this->objWpPost = array_shift($objsWpPost);
            $postTitle = $this->objWpPost->getField(self::WP_POSTTITLE);
            list($name) = explode(':', $postTitle);
            
            $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
            $buttonContent = $this->getLink(trim($name), $this->getOngletUrl($urlElements), self::CST_TEXT_WHITE);
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
        // On va définir la liste des éléments du menu de gauche.
        $menuContent = '';
        // Récupération des articles Wordpress liés à la catégorie "Base de données"
        $attributes = array(
            // Catégorie "Base de données".
            self::WP_CAT       => self::WP_CAT_ID_BDD,
            self::WP_METAKEY   => self::WP_CF_ORDREDAFFICHAGE,
            self::SQL_ORDER_BY => self::WP_METAVALUENUM,
            self::SQL_ORDER    => self::SQL_ORDER_ASC,
        );
        $objWpPosts = $this->objWpPostServices->getPosts($attributes);
        while (!empty($objWpPosts)) {
            $objWpPost = array_shift($objWpPosts);
            $postName = $objWpPost->getField(self::WP_POSTNAME);
            $url = $this->getUrl(array(self::CST_CAT_SLUG=>$postName));
            if ($this->catSlug==$postName) {
                $blnSelected = true;
                $targetWpPost = $objWpPost;
            } else {
                $blnSelected = false;
            }
            $objBean = WpPost::getBean($objWpPost, self::WP_CAT_ID_BDD);
            $menuContent .= $objBean->getCategoryNavItem($url, self::I_DATABASE, $blnSelected);
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Si on est sur la page d'accueil, on affiche la présentation des bases de données
        // Sinon, on affiche le détail de la base de données sélectionnée.
        if ($this->catSlug=='') {
            $urlTemplate = 'web/pages/public/fragments/public-fragments-article-bdd-entete.php';
            $mainContent = $this->getRender($urlTemplate);
        } else {
            $mainContent = WpPost::getBean($targetWpPost, self::WP_CAT_ID_BDD)->getContentDisplay();
        }
        /////////////////////////////////////////
        
        $urlTemplate = self::WEB_PPFS_ONGLET;
        $attributes = array(
            // L'id de la page
            'section-bdd',
            // Il n'y a pas de bouton sur cette interface
            '',
            // Le nom du bloc du menu de gauche
            self::LABEL_DATABASES,
            // La liste des éléments du menu de gauche
            $menuContent,
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
