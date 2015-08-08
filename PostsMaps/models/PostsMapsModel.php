<?php
class PostsMapsModel {
    private $_dbVersion = '0.01';

    public function __construct() {
    }

    private function getPostsWithMapDataByQuery($query) {
        $posts = array();
        if($query->have_posts()) {
            $i = 0;
            $mapMarkerIcons = get_option('pm_marker_icons') ? unserialize(get_option('pm_marker_icons')) : false;
            while($query->have_posts()) {
                $query->the_post();
                $posts[$i] = new stdClass();
                $posts[$i]->ID = get_the_ID();
                $posts[$i]->title = get_the_title();
                $posts[$i]->url = get_permalink($posts[$i]->ID);
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($posts[$i]->ID), array('thumbnail'));
                $posts[$i]->thumbUrl = $thumb['0'];
                $posts[$i]->lat = get_post_meta($posts[$i]->ID, 'pm_lat', 1) ? get_post_meta($posts[$i]->ID, 'pm_lat', 1) : false;
                $posts[$i]->lng = get_post_meta($posts[$i]->ID, 'pm_lng', 1) ? get_post_meta($posts[$i]->ID, 'pm_lng', 1) : false;
                $posts[$i]->postMarker = get_post_meta($posts[$i]->ID, 'pm_marker', 1) ? get_post_meta($posts[$i]->ID, 'pm_marker', 1) : false;
                if(isCorrectArray($mapMarkerIcons))
                    $posts[$i]->postMarkerIcon = plugins_url() . '/posts-maps/assets/images/' . $mapMarkerIcons[$posts[$i]->postMarker];
                ++$i;
            }
            wp_reset_query();
            return $posts;
        }
        return false;
    }

    public function getPostsByTypes($postTypes) {
        $posts = array();
        if(is_string($postTypes) && $postTypes)
            $postTypes = array($postTypes);
        if(isCorrectArray($postTypes)) {
            $args = array(
                'post_type' => $postTypes,
                'post_status' => 'publish',
                'posts_per_page' => -1
            );        
            $query = new WP_Query($args);
            return $this->getPostsWithMapDataByQuery($query);
        }
        return false;
    }

    public function getPostsById($postId) {
        $posts = array();
        if(is_numeric($postId))
            $postId = array($postId);
        if(isCorrectArray($postId)) {
            $args = array(
                'post__in' => $postId,
                'post_status' => 'publish',
                'posts_per_page' => -1
            );        
            $query = new WP_Query($args);
            return $this->getPostsWithMapDataByQuery($query);
        }
        return false;
    }

}
?>