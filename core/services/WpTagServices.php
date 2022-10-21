<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpTagServices
 * @since 1.22.10.20
 * @version 1.22.10.20
 */
class WpTagServices extends LocalServices
{
    /*
  public function __construct() { }

  public function getTagByName($name, $dbTag='wp_5')
  {
    $requete = "SELECT t.term_id AS termId FROM ".$dbTag."_terms t INNER JOIN ".$dbTag."_term_taxonomy tt ON t.term_id = tt.term_id WHERE name = '$name' AND taxonomy='post_tag';";
    $rows = MySQL::wpdbSelect($requete);
    if (empty($rows)) {
      $tagId = wp_create_tag($name);
    } else {
      $row = array_shift($rows);
      $tagId = $row->termId;
    }
    return WpTag::convertElement(get_tag($tagId));
  }

  public function getTagBySlug($slug='', $name='')
  {
    $requete = "SELECT term_id FROM wp_5_terms WHERE slug = '$slug';";
    $rows = MySQL::wpdbSelect($requete);
    if (empty($rows)) {
      $tagId = wp_create_tag($name);
    } else {
      $row = array_shift($rows);
      $tagId = $row->term_id;
    }
    return WpTag::convertElement(get_tag($tagId));
  }

  public function getTags()
  {
    $WpTags = array();
    $tags = get_tags();
    while (!empty($tags)) {
      // Qu'on convertit en WpTag
      array_push($WpTags, WpTag::convertElement(array_shift($tags)));
    }
    return $WpTags;
  }
  */
}
