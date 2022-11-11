<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.10.18
 */
class WpPageBean extends UtilitiesBean
{
    /**
     * WpPost affiché
     * @var WpPost $WpPage
     */
    protected $WpPage;

    public $hasHeader = true;
    public $hasFooter = true;

    /**
     * @param string $post
     */
    public function __construct($post='')
    {
        if ($post=='') {
            $post = get_post();
        }
        $this->WpPage = WpPage::convertElement($post);
        $this->WpPostServices = new WpPostServices();
    }

    /**
     * @since 1.22.04.27
     * @version 1.22.10.18
     */
    public static function getPageBean()
    {
        if (is_front_page()) {
            $returned = new WpPageHomeBean();
        } else {
            $uri = $_SERVER['REQUEST_URI'];
            $arrUri = explode('/', $uri);
            if (!isset($arrUri[1])) {
                $returned = new WpPageHomeBean();
            } elseif ($arrUri[1]==self::PAGE_ADMIN) {
                $returned = new WpPageAdminBean();
            } else {
                $returned = new WpPageHomeBean();
            }
        }
        return $returned;
    }
}
