<?php
namespace core\domain;

use core\bean\WpPostBddBean;
use core\bean\WpPostSkillBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * WpPost
 */
class WpPostClass extends LocalDomainClass
{
    protected $ID;
    protected $post_author;
    protected $post_date;
    protected $post_date_gmt;
    protected $post_content;
    protected $post_title;
    protected $post_excerpt;
    protected $post_status;
    protected $comment_status;
    protected $ping_status;
    protected $post_password;
    protected $post_name;
    protected $to_ping;
    protected $pinged;
    protected $post_modified;
    protected $post_modified_gmt;
    protected $post_content_filtered;
    protected $post_parent;
    protected $guid;
    protected $menu_order;
    protected $post_type;
    protected $post_mime_type;
    protected $comment_count;
    protected $filter;

    public function __construct()
    {
        $this->stringClass = 'core\domain\WpPostClass';
    }

    public static function convertElement($row)
    { return parent::convertRootElement(new WpPostClass(), $row); }

    public function getID()
    { return $this->ID; }
    public function getPostDate()
    { return $this->post_date; }
    public function getPostContent()
    { return $this->post_content; }
    public function getPostTitle()
    { return $this->post_title; }
    public function getPostStatus()
    { return $this->post_status; }
    public function getPostName()
    { return $this->post_name; }
    public function getPostParent()
    { return $this->post_parent; }
    public function getGuid()
    { return $this->guid; }
    public function getMenuOrder()
    { return $this->menu_order; }

    public function getPostSubtype()
    { return $this->postSubtype; }
    public function setPostSubtype($objWpPostClass)
    { $this->postSubtype = $objWpPostClass; }

    /**
     * @param WpPostClass $objWpPost
     * @param int $catId
     * @return mixed
     * @since 1.23.03.22
     * @version 1.23.03.22
     */
    public static function getBean($objWpPost, $catId=self::WP_CAT_ID_BDD)
    {
        $objBean = match ($catId) {
            self::WP_CAT_ID_SKILL => new WpPostSkillBean($objWpPost),
            default => new WpPostBddBean($objWpPost),
        };
        return $objBean;
    }

    public function getPostMeta($key='')
    {
        if (!isset($this->metas)) {
            $this->metas = get_post_meta($this->ID);
        }
        return $this->metas[$key][0];
    }

    public function getPermalink()
    { return get_permalink($this->getID()); }

    public function getStrPostDate()
    {
        $s = $this->post_date;
        return substr((string) $s, 8, 2).'/'.substr((string) $s, 5, 2).' à '.substr((string) $s, 11, 2).'h'.substr((string) $s, 14, 2);
    }
    public function getStrDate()
    {
        $arrDate = explode('-', substr((string) $this->post_date, 0, 10));
        $arrMois = [1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        return $arrDate[2].' '.$arrMois[$arrDate[1]*1].' '.$arrDate[0];
    }

    public function getPostMetas()
    {
        if (!isset($this->metas)) { $this->metas = get_post_meta($this->ID); }
        return $this->metas;
    }

    public function setPostMeta($key, $value)
    { update_post_meta($this->ID, $key, $value); }

    public function getImgUrl()
    {
        $medias = $this->getAttachedMedia('image');
        if (!empty($medias)) {
            $media = array_shift($medias);
            if ($media->guid!='') {
            return $media->guid;
            }
        }
        return '#';
    }

    public function getAttachedMedia($type)
    { return get_attached_media($type, $this->ID); }

    public function getUrlOrUri()
    { return (static::isAdmin() ? $this->getGuid() : $this->getUrl()); }

    public function getUrl()
    { return $this->getPostMeta('article_url'); }

    public function getPdfUrl()
    {
        $medias = $this->getAttachedMedia('application/pdf');
        if (!empty($medias)) {
            $media = array_shift($medias);
            if ($media->guid!='') {
                return $media->guid;
            }
        }
        return '#';
    }

    public function getCategories()
    {
        if (empty($this->WpCategories)) {
            $this->WpCategories = [];
            $categories = wp_get_post_categories($this->ID);
            while (!empty($categories)) {
                $catId = array_shift($categories);
                $category = get_category($catId);
                array_push($this->WpCategories, WpCategoryClass::convertElement($category));
            }
        }
        return $this->WpCategories;
    }

    public function getTags()
    {
        if (empty($this->WpTags)) {
            $this->WpTags = [];
            $tags = wp_get_post_tags($this->ID);
            while (!empty($tags)) {
                $tag = array_shift($tags);
                array_push($this->WpTags, WpTagClass::convertElement($tag));
            }
        }
        return $this->WpTags;
    }

    public function hasTag($tag)
    {
        $objsWpTag = $this->getTags();
        while (!empty($objsWpTag)) {
            $objWpTag = array_shift($objsWpTag);
            if ($objWpTag->getName()==$tag || $objWpTag->getSlug()==$tag) {
                return true;
            }
        }
        return false;
    }
}
