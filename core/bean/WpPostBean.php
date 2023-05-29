<?php
namespace core\bean;

use core\domain\WpPostClass;
use core\utils\HtmlUtils;

/**
 * WpPostBean
 * @author Hugues
 * @since 1.22.00.00
 * @version v1.23.05.28
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
        $this->WpPost = WpPostClass::convertElement($post);
    }
    
    /**
     * @since 1.22.10.29
     * @version v1.23.05.28
     */
    public function getCategoryNavItem(string $url, string $icon, bool $blnSelected=false): string
    {
        // Construction du Lien
        $strIcon = HtmlUtils::getIcon($icon);
        $label   = $this->WpPost->getField(self::WP_POSTTITLE);
        $aContent = $strIcon.self::CST_NBSP.$label;
        $liContent = HtmlUtils::getLink($aContent, $url, 'nav-link '.self::CST_TEXT_WHITE);

        // Construction de l'élément de la liste.
        $liAttributes = [self::ATTR_CLASS => 'nav-item'.($blnSelected ? ' '.self::CST_ACTIVE : '')];
        return $this->getBalise(self::TAG_LI, $liContent, $liAttributes);
    }
}
