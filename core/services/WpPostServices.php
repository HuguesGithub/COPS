<?php
namespace core\services;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPostServices
 * @author Hugues.
 * @version 22.07.06
 * @since 22.07.06
 */
class WpPostServices extends GlobalServices
{

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
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $args[$key] = $value;
            }
        }
        $posts_array = get_posts($args);
        $WpPosts = array();
        if (!empty($posts_array)) {
            foreach ($posts_array as $post) {
                $WpPosts[] = WpPost::convertElement($post);
            }
        }
        return $WpPosts;
    }

}
