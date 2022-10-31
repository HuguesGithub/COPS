<?php
/**
 * WpPostBean
 * @author Hugues
 * @since 1.22.00.00
 * @version 1.22.09.23
 */
class WpPostBean extends UtilitiesBean
{
    /**
     * WpPost affiché
     * @var WpPost $WpPost
     */
    protected $WpPost;

    /**
     * Constructeur
     */
    public function __construct($post='')
    {
        if ($post=='') {
            $post = get_post();
        }
        $this->WpPost = WpPost::convertElement($post);
    }
    
    /**
     * @since 1.22.10.29
     * @version 1.22.10.29
     */
    public function getCategoryNavItem($url, $icon, $blnSelected=false)
    {
        // Construction du Lien
        $strIcon = $this->getIcon($icon);
        $label   = $this->WpPost->getField(self::WP_POSTTITLE);
        $aContent = $strIcon.self::CST_NBSP.$label;
        $liContent = $this->getLink($aContent, $url, 'nav-link '.self::CST_TEXT_WHITE);

        // Construction de l'élément de la liste.
        $liAttributes = array(
            self::ATTR_CLASS => 'nav-item'.($blnSelected ? self::CST_NBSP.self::CST_ACTIVE : ''),
        );
        return $this->getBalise(self::TAG_LI, $liContent, $liAttributes);
    }
}
