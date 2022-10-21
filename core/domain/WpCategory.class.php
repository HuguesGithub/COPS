<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpCategory
 * @since 1.22.10.20
 * @version 1.22.10.20
 */
class WpCategory extends WpTag
{
    protected $term_id;
    protected $name;
    protected $slug;
    protected $term_group;
    protected $term_taxonomy_id;
    protected $taxonomy;
    protected $description;
    protected $parent;
    protected $count;
    protected $filter;
    
    public function __construct($wpTermObject='')
    {
        if ($wpTermObject!='') {
            foreach ($wpTermObject as $field => $value) {
                $this->{$field} = $value;
            }
        }
    }
    
    public function getBean()
    { return new WpCategoryBean($this); }
    
    /*
  protected $cat_ID;
  protected $category_count;
  protected $category_description;
  protected $cat_name;
  protected $category_nicename;
  protected $category_parent;


  public function getClassVars() { return get_class_vars('WpCategory'); }
  public static function convertElement($row, $WpPostClass='', $b='') {
    $Obj = new WpCategory();
    $WpCategory = GlobalDomain::convertElement($Obj, $Obj::getClassVars(), $row);
    return $WpCategory;
  }

  public function getCatId()
  { return $this->cat_ID; }
  public function getCategoryName()
  { return $this->cat_name; }
  public function getCategoryDescription()
  { return $this->category_description; }
  public function getCategoryParent()
  { return $this->category_parent; }

  public function isWellEligible() {
    return ( $this->count > 2 && !in_array($this->cat_ID, array(BREVE_TERM_ID, INSTAGRAM_TERM_ID)) );  }

  public function getThreeRandomPosts() {
    $WpServices = new WpPostServices();
    $params = array(
      'cat' => $this->cat_ID,
      'posts_per_page'=>3,
      'orderby' => 'rand',
      'order'    => 'ASC',
      'post_type'=>'post'
    );
    return $WpServices->getArticles(__FILE__, __LINE__, $params, 'WpPostBreves', TRUE);
  }
  public function getUrl()
  { return get_category_link($this->cat_ID); }
  */
}
