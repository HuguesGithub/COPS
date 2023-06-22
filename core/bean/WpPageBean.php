<?php
namespace core\bean;

use core\domain\WpPageClass;
use core\services\WpPostServices;
use core\utils\SessionUtils;

/**
 * WpPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version v1.23.06.25
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
        $this->WpPage = WpPageClass::convertElement($post);
        $this->initServices();
    }

    /**
     * Initialisation des Services
     * @since 1.23.02.18
     * @version 1.23.02.18
     */
    private function initServices()
    {
        $this->WpPostServices = new WpPostServices();
    }

    /**
     * @return mixed WpPageHomeBen|WpPageAdminBean
     * @since 1.22.04.27
     * @version v1.23.06.25
     */
    public static function getPageBean()
    {
        if (is_front_page()) {
            $returned = new WpPageHomeBean();
        } else {
            $uri = SessionUtils::fromServer('REQUEST_URI');
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
