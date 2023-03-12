<?php
namespace core\services;

use core\domain\WpCategoryClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpCategoryServices
 * @since 1.22.10.20
 * @version 1.22.10.20
 */
class WpCategoryServices extends LocalServices
{
    /**
     * @param int $parentId
     * @return WpCategory[]
     * @since 1.22.10.20
     * @version 1.22.10.20
     */
    public function getCategoryChildren($parentId)
    {
        $arrChildren = get_term_children($parentId, 'category');
        $arrItems = array();
        while (!empty($arrChildren)) {
            $catId = array_shift($arrChildren);
            $arrItems[] = $this->getCategory($catId);
        }
        return $arrItems;
    }
    
    /**
     * @param int $catId
     * @return WpCategoryClass
     * @since 1.22.10.20
     * @version 1.22.10.20
     */
    public function getCategory($catId)
    { return new WpCategoryClass(get_term($catId)); }

    /**
     * @param int $catId
     * @return WpCategoryClass
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getCategoryByField($field, $value)
    { return new WpCategoryClass(get_term_by($field, $value, 'category')); }
    
    /*
  public function __construct() { }

  public function getCategoryByName($name)
  {
    $name = str_replace("'", "''", $name);
    $requete = "SELECT term_id FROM wp_5_terms WHERE name = '$name';";
    $rows = MySQL::wpdbSelect($requete);
    if (empty($rows)) {
      $catId = wp_create_category($name);
    } else {
      $row = array_shift($rows);
      $catId = $row->term_id;
    }
    $WpCategory = WpCategory::convertElement(get_category($catId));
    return $WpCategory;
  }

  public function getCategoryBySlug($slug='', $name='')
  {
    $categs = get_categories(array('slug'=>$slug, 'hide_empty'=>false));
    if (count($categs)==1) {
      $categ = array_shift($categs);
      return WpCategory::convertElement($categ);
    } else {
      return new WpCategory();
//      $catId = wp_create_category($name);
    }
  }

  public function getCategoryChildren($WpCategory, $addArgs=array())
  {
    $defaults = array('hide_empty'=>false);
    $args = wp_parse_args($addArgs, $defaults);
    $categs = get_categories($args);
    $WpCategs = array();
    while (!empty($categs)) {
      $categ = array_shift($categs);
      array_push($WpCategs, WpCategory::convertElement($categ));
    }
    return $WpCategs;
  }

  public function getCategorySiblings($WpCategory) {
    $args = array('parent'=>$WpCategory->getCategoryParent(), 'exclude'=>$WpCategory->getCatID(), 'depth'=>1);
    $categs = get_categories($args);
    $WpCategs = array();
    while (!empty($categs)) {
      $categ = array_shift($categs);
      array_push($WpCategs, WpCategory::convertElement($categ));
    }
    return $WpCategs;
  }
    */
}
