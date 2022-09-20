<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPageBean
 * @author Hugues
   * @since 1.22.04.27
   * @version 1.22.04.27
 */
class WpPageBean extends LocalBean
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
    parent::__construct();
    if ($post=='') {
      $post = get_post();
      $this->WpPage = WpPage::convertElement($post);
    }
    $this->WpPostServices = new WpPostServices();
  }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public static function getPageBean()
  {
    if (is_front_page()) {
      $returned = new WpPageHomeBean();
    } else {
      $uri = $_SERVER['REDIRECT_SCRIPT_URL'];
      $arrUri = explode('/', $uri);
      if (isset($arrUri[1]) && $arrUri[1]=='category') {
        // TODO : à gérer plus tard
      } elseif (isset($arrUri[1]) && $arrUri[1]=='tag') {
        // TODO : à gérer plus tard
        $returned = new GonePageTagBean($arrUri[2]);
      } elseif (isset($arrUri[1]) && $arrUri[1]=='mec-event') {
        $returned = new WpPageEventBean($_GET);
      } elseif (isset($arrUri[1]) && $arrUri[1]=='mec-category') {
        $returned = new WpPageCategoryBean($_GET);
      } else {
        $page = get_post();
        if ($page instanceof WP_Post) {
          // On est dans le cas d'une page
          if ($page->post_type=='page') {
            $returned = self::getSpecificPageBean($page);
          } elseif ($page->post_type=='post') {
            $returned = new WpPostBean(WpPost::convertElement($page));
          } else {
            // La page des tags est ici.
          }
        }
      }
    }
    return $returned;
  }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  private static function getSpecificPageBean($page)
  {
    switch ($page->post_name) {
      case 'admin' :
        $returned = new AdminCopsPageBean();
      break;
      default :
        echo "Need to add a slug ".$page->post_name." in WpPageBean > getContentPage().";
      break;
    }
    return $returned;
  }

}
