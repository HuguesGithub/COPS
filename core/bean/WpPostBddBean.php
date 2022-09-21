<?php
/**
 * WpPostBddBean
 */
class WpPostBddBean extends WpPostBean
{

  /**
   * Constructeur
   */
  public function __construct($WpPost='')
  {
    $this->WpPost = $WpPost;
  }

  public function getContentDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-library-bdd.php';
    list($sigle, $label) = explode(':', $this->WpPost->getField(self::WP_POSTTITLE));
    $attributes = array(
      // Le titre
      $this->WpPost->getField(self::WP_POSTTITLE),
      // Le contenu
      $this->WpPost->getField(self::WP_POSTCONTENT),
      // L'ancre
      trim($sigle),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
?>
