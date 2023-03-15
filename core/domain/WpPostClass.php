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

    public static function getBean($WpPost, $catId=45)
    {
        switch ($catId) {
            case '47' :
            $Bean = new WpPostSkillBean($WpPost);
            break;
            case '45' :
            default :
            $Bean = new WpPostBddBean($WpPost);
            break;
        }
        return $Bean;
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
        return substr($s, 8, 2).'/'.substr($s, 5, 2).' à '.substr($s, 11, 2).'h'.substr($s, 14, 2);
    }
    public function getStrDate()
    {
        $arrDate = explode('-', substr($this->post_date, 0, 10));
        $arrMois = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
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
    {
        $secondsperDay = 60*60*24;
        $tresholdDays = 100;
        $s = $this->getPostDate();
        $daysElapsed = (time()-mktime(0, 0, 0, substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4)))/$secondsperDay;
        return (self::isAdmin() ? $this->getGuid() : $this->getPostMeta('article_url'));
    }

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
            $this->WpCategories = array();
            $categories = wp_get_post_categories($this->ID);
            while (!empty($categories)) {
                $cat_id = array_shift($categories);
                $category = get_category($cat_id);
                array_push($this->WpCategories, WpCategoryClass::convertElement($category));
            }
        }
        return $this->WpCategories;
    }

    public function getTags()
    {
        if (empty($this->WpTags)) {
            $this->WpTags = array();
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
        $WpTags = $this->getTags();
        while (!empty($WpTags)) {
            $WpTag = array_shift($WpTags);
            if ($WpTag->getName()==$tag || $WpTag->getSlug()==$tag) {
                return true;
            }
        }
        return false;
    }
}
