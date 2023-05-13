<?php
namespace core\domain;

/**
 * Classe WpPageClass
 * @author Hugues
 * @since 1.1.00
 * @version v1.23.04.14
 */
class WpPageClass extends WpPostClass
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

    public function getParent()
    { return new WpPageClass($this->getPostParent()); }

    public function getDepth()
    { return $this->getPostParent()==0 ? 0 : 1+$this->getParent()->getDepth(); }

    /**
     * @since v1.23.05.09
     * @version v1.23.05.14
     */
    public function isCurrentWpPage()
    {
        $strPageName = get_query_var('pagename');
        return $strPageName==$this->getPostName()
            || $strPageName=='' && $this->getPostParent()==0 && $this->getMenuOrder()==1;
    }

    public function hasChildren()
    {
        if ($this->Children==null) {
            $attributes = [self::SQL_ORDER_BY => self::WP_MENUORDER];
            $this->Children = $this->WpPostServices->getChildPagesByParentId($this->getID(), -1, $attributes);
        }
        return $this->Children;
    }

    public function getChildrenHeaderNav()
    {
        $str  = '<li id="menu-item-%1$s" class="menu-item menu-item-%1$s menu-item-depth-%2$s">';
        $str .= '<a href="%3$s"><span>%4$s</span></a></li>';
        $ulHeaderNav = '';
        foreach ($this->Children as $Child) {
            $attributes = [$Child->getId(), $Child->getDepth(), $Child->getPermalink(), $Child->getPostTitle()];
            $ulHeaderNav .= vsprintf($str, $attributes);
        }
        return '<ul class="sub-menu">'.$ulHeaderNav.'</ul>';
    }
}
