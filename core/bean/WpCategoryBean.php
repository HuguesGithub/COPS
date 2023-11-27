<?php
namespace core\bean;

use core\utils\HtmlUtils;

/**
 * WpCategoryBean
 * @since 1.22.10.20
 * @version v1.23.12.02
 */
class WpCategoryBean extends UtilitiesBean
{
    /**
     * WpCategory affichée
     * @var WpCategory $wpCategory
     */
    protected $wpCategory;
    
    /**
     * Constructeur
     */
    public function __construct($wpCategory='')
    {
        $this->wpCategory = $wpCategory;
    }
    
    /**
     * @since 1.22.10.20
     * @version v1.23.12.02
     */
    public function getCategoryNavItem(string $url, string $icon, bool $blnSelected=false): string
    {
        $strIcon = HtmlUtils::getIcon($icon);
        $label   = $this->wpCategory->getField(self::FIELD_NAME);
        $aContent = $strIcon.self::CST_NBSP.$label;
        
        $url    .= '&amp;'.self::CST_CAT_SLUG.'='.$this->wpCategory->getField(self::FIELD_SLUG);
        $lien    = HtmlUtils::getLink($aContent, $url, 'nav-link text-white');
        
        $liAttributes = [self::ATTR_CLASS => 'nav-item'.($blnSelected ? ' '.self::CST_ACTIVE : '')];
        return $this->getBalise(self::TAG_LI, $lien, $liAttributes);
    }

}
