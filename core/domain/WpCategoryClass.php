<?php
namespace core\domain;

use core\bean\WpCategoryBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpCategoryClass
 * @since 1.22.10.20
 * @version 1.22.10.20
 */
class WpCategoryClass extends WpTagClass
{
    protected $term_id;
    public $name;
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

    
    public static function compCategories($a, $b)
    { return strtolower((string) $a->name) <=> strtolower((string) $b->name); }
    
}
