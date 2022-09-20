<?php
if ( !defined( 'ABSPATH') ) {
    die( 'Forbidden' );
}
/**
 * Classe WpPostServices
 * @author Hugues.
 * @version 22.07.06
 * @since 22.07.06
 */
class WpPostServices extends GlobalServices {

  public function __construct() { }

  public function getPosts($attributes)
  {
    $args = array(
      'orderby'=>'name',
      'order'=>'ASC',
      'posts_per_page'=>-1,
      'post_type'=>'post',
      'post_status'=>'publish',
      'suppress_filters'=>TRUE
    );
    if ( !empty($attributes) ) {
      foreach ( $attributes as $key=>$value ) {
        $args[$key] = $value;
      }
    }
    $posts_array = get_posts($args);
    $WpPosts = array();
    if ( !empty($posts_array) ) {
      foreach ( $posts_array as $post ) {
        $WpPosts[] = WpPost::convertElement($post);
      }
    }
    return $WpPosts;
  }

  /*
  public function getArticles($file, $line, $params=array(), $WpPostType='WpPost', $viaWpQuery=false) {
    $args = array(
      'orderby'=> 'name',
      'order'=>'ASC',
      'posts_per_page'=>-1,
      'post_type'=>'post'
    );
    if ( !empty($params) ) {
      foreach ( $params as $key=>$value ) {
        $args[$key] = $value;
      }
    }
    if ( $viaWpQuery ) {
      $wpQuery = new WP_Query( $args );
      $posts_array = $wpQuery->posts;
    } else {
      $posts_array = get_posts( $args );
    }
    $WpPosts = array();
    if ( !empty($posts_array) ) {
      foreach ( $posts_array as $post ) {
        $WpPosts[] = WpPost::convertElement($post, $WpPostType);
      }
    }
    return $WpPosts;
    }
  public function getChildPagesByParentId($pageId, $limit = -1) {
    global $post;
    $pages = array();
    $args = array(
      'orderby'=> 'name',
      'order'=>'ASC',
      'post_type' => 'page',
      'post_parent' => $pageId,
      'posts_per_page' => $limit
    );
    $the_query = new WP_Query( $args );
    while ( $the_query->have_posts() ) {
      $the_query->the_post();
      $pages[] = WpPost::convertElement($post, 'WpPost');
    }
    wp_reset_postdata();
    return $pages;
  }
  public function getWpPostsWithFilters($file, $line, $params=array(), $WpPostType='WpPost') {
    $args = array(
      'orderby'=>'name',
      'order'=>'ASC',
      'posts_per_page'=>-1,
      'post_type'=>'post',
      'post_status'=>'publish',
      'suppress_filters'=>TRUE
    );
    if ( !empty($params) ) {
      foreach ( $params as $key=>$value ) {
        $args[$key] = $value;
      }
    }
    $posts_array = get_posts( $args );
    $WpPosts = array();
    if ( !empty($posts_array) ) {
      foreach ( $posts_array as $post ) {
        $WpPosts[] = WpPost::convertElement($post, $WpPostType);
      }
    }
    return $WpPosts;
  }
  public function getWpPost($file, $line, $ID, $WpPostType='WpPost') {
    $WpPost = get_post($ID);
    return WpPost::convertElement($WpPost, $WpPostType);
  }
  public function addCategories($WpPost, $arrIds, $append=false)
  { wp_set_post_categories($WpPost->getID(), $arrIds, $append); }
  public function addTags($WpPost, $arrIds, $append=false)
  { wp_set_post_tags($WpPost->getID(), $arrIds, $append); }

  public function getWpPostByCustomField($name, $value)
  {
    $args = array('numberposts'=>1, 'post_type'=>'post', 'meta_key'=>$name, 'meta_value'=>$value);
    $posts = get_posts($args);
    return empty($posts) ? new WpPost() : WpPost::convertElement(array_shift($posts), 'WpPost');
  }

  public function addCustomField($WpPost, $key, $value)
  { add_post_meta($WpPost->getID(), $key, $value); }

  public function getWpPostsByTag($tag, $args=array(), $WpPostType='WpPost')
  {
    $args = array_merge($args, array('tax_query'=>array(array('taxonomy'=>'post_tag', 'field'=>'slug', 'terms'=>array($tag)))));
    return $this->getWpPostsWithFilters(__FILE__, __LINE__, $args, $WpPostType);
  }

  public function getWpPostsByCategory($catId)
  { return $this->getWpPostsWithFilters(__FILE__, __LINE__, array('cat'=>$catId)); }
  */

}
?>
