<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminLibrarySkillBean
 * @author Hugues
 * @since 1.22.11.03
 * @version 1.22.11.03
 */
class WpPageAdminLibrarySkillBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objWpPostServices = new WpPostServices();
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getSubongletContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        $menuContent = '';
        // Récupération des articles Wordpress liés à la catégorie "Compétences"
        $attributes = array(
            // Catégorie "Compétences".
            self::WP_CAT       => self::WP_CAT_ID_SKILL, // 47
            self::SQL_ORDER_BY => self::WP_POSTTITLE,
            self::SQL_ORDER    => self::SQL_ORDER_ASC,
        );
        $objWpPosts = $this->objWpPostServices->getPosts($attributes);
        /////////////////////////////////////////

        // Construction des données du template
        $strContent = '';
        $strAncres  = '';
        $prevAncre  = '';
        while (!empty($objWpPosts)) {
            $objWpPost = array_shift($objWpPosts);
			// On récupère le titre du post et sa première lettre, pour vérifier si on doit créer une nouvelle ancre.
            $postTitle = str_replace('É', 'E', $objWpPost->getField(self::WP_POSTTITLE));
            $postAnchor = substr($postTitle, 0, 1);
            if ($prevAncre!=$postAnchor) {
                $prevAncre = $postAnchor;
				$btnContent = $this->getLink($postAnchor, '#anchor-'.$postAnchor, 'nav-link text-white');
				$liContent = $this->getButton($btnContent, array(self::ATTR_CLASS=>'btn-dark', self::ATTR_STYLE=>'width: -webkit-fill-available'));
                $strAncres .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>'nav-item'));
                $strContent .= $this->getBalise(self::TAG_A, '', array(self::ATTR_CLASS=>'col-12', self::FIELD_ID=>'anchor-'.$postAnchor));
            }
			// On récupère le contenu de l'article pour afficher la compétence
            $strContent .= WpPost::getBean($objWpPost, self::WP_CAT_ID_SKILL)->getContentDisplay();
        }
        /////////////////////////////////////////
		
        /////////////////////////////////////////
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-skills.php';
        $attributes = array(
			//
			'section-skill',
            // La liste alphabétique des ancres du haut de page
            $strAncres,
            // La liste des compétences
            $strContent,
        );
        return $this->getRender($urlTemplate, $attributes);
        /////////////////////////////////////////
    }
}
