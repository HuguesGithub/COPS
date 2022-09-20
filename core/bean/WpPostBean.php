<?php
/**
 * WpPostBean
 */
class WpPostBean extends LocalBean {

  /**
   * Constructeur
   */
  public function __construct($WpPost='') {
    parent::__construct($services);
    $this->WpPost = $WpPost;
  }

}
?>
