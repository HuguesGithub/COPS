<?php
namespace core\domain;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpTagClass
 * @since 1.22.10.20
 * @version 1.22.10.20
 */
class WpTagClass extends LocalDomainClass
{
    /*
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

  public function __construct() {}

  public function getTermId() { return $this->term_id; }
  public function getName() { return $this->name; }
  public function getSlug() { return $this->slug; }
  public function getCount() { return $this->count; }
  public function getTermTaxonomyId() { return $this->term_taxonomy_id; }

  public function getClassVars() { return get_class_vars('WpTag'); }
  public static function convertElement($row, $WpPostClass='', $b='') {
    $Obj = new WpTag();
    $WpTag = parent::convertElement($Obj, $Obj::getClassVars(), $row);
    return $WpTag;
  }

  public function getBean()
  { return new WpTagBean($this); }

  public function getUrl()
  { return get_term_link($this->term_id); }
  */
}
