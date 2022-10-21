<?php
/**
 * WpCategoryBean
 * @since 1.22.10.20
 * @version 1.22.10.20
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
     * @version 1.22.10.21
     */
    public function getCategoryNavItem($url, $icon, $blnSelected=false)
    {
        $strIcon = $this->getIcon($icon);
        $label   = $this->wpCategory->getField('name');
        $aContent = $strIcon.' '.$label;
        
        $url    .= $this->wpCategory->getField('slug');
        $aAttributes = array(
            self::ATTR_CLASS => 'nav-link text-white',
            self::ATTR_HREF  => $url,
        );
        $lien    = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
        
        $liAttributes = array(
            self::ATTR_CLASS => 'nav-item'.($blnSelected ? ' '.self::CST_ACTIVE : ''),
        );
        return $this->getBalise(self::TAG_LI, $lien, $liAttributes);
    }

}
