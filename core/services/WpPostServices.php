<?php
namespace core\services;

use core\domain\WpPostClass;

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
            self::SQL_ORDER_BY       => self::WP_NAME,
            self::SQL_ORDER          => self::SQL_ORDER_ASC,
            self::WP_POSTSPERPAGE    => -1,
            self::WP_POSTTYPE        => self::WP_POST,
            self::WP_POSTSTATUS      => self::WP_PUBLISH,
            self::WP_SUPPRESS_FILTER => true
        );
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $args[$key] = $value;
            }
        }
        $posts = get_posts($args);
        $objsWpPost = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $objsWpPost[] = WpPostClass::convertElement($post);
            }
        }
        return $objsWpPost;
    }

}
