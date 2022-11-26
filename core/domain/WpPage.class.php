<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageBean
 * @author Hugues
 * @version 1.1.00
 * @since 1.1.00
 */
class WpPage extends WpPost
{
  private $Children = null;

  public function __construct($id=0)
  {
    parent::__construct();
    $post = get_post($id);
    $attributes = $this->getClassVars();
    foreach ($attributes as $attribute => $value) {
      $this->{$attribute} = $post->{$attribute};
    }
    $this->WpPostServices = new WpPostServices();
  }
/*
  public function getBean()
  { return new WpPageBean($this); }
*/
  public function getParent()
  { return new WpPage($this->getPostParent()); }

  public function getDepth()
  { return $this->getPostParent()==0 ? 0 : 1+$this->getParent()->getDepth(); }

  public function isCurrentWpPage()
  { return (get_query_var('pagename')==$this->getPostName() || get_query_var('pagename')=='' && $this->getPostParent()==0 && $this->getMenuOrder()==1); }

  public function hasChildren()
  {
    if ($this->Children==null) {
      $this->Children = $this->WpPostServices->getChildPagesByParentId($this->getID(), -1, array('orderby'=> 'menu_order'));
    }
    return $this->Children;
  }

  public function getChildrenHeaderNav()
  {
    $str = '<li id="menu-item-%1$s" class="menu-item menu-item-%1$s menu-item-depth-%2$s"><a href="%3$s"><span>%4$s</span></a></li>';
    $ulHeaderNav = '<ul class="sub-menu">';
    foreach ($this->Children as $Child) {
      $ulHeaderNav .= vsprintf($str, array($Child->getId(), $Child->getDepth(), $Child->getPermalink(), $Child->getPostTitle()));
    }
    return $ulHeaderNav .= '</ul>';
  }
}
