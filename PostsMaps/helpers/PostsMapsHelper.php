<?php
if(!function_exists('isCorrectArray')) {
    function isCorrectArray($array) {
        if($array && is_array($array) && count($array) > 0)
            return $array;
        return false;
    }
}

if(!function_exists('isCorrectNumber')) {
    function isCorrectNumber($number) {
        if($number && is_numeric($number))
            return $number;
        return false;
    }
}

if(!function_exists('isOnlyLatinLetter')) {
    function isOnlyLatinLetter($str) {
        if($str && !preg_match('/[^A-Za-z\-_]+/ui', $str))
            return true;
        return false;
    }
}

if(!function_exists('isOnlyLatinLetterAndNumber')) {
    function isOnlyLatinLetterAndNumber($str) {
        if($str && !preg_match('/[^A-Za-z0-9\-_]+/ui', $str))
            return true;
        return false;
    }
}

if(!function_exists('getCorrectNumber')) {
    function getCorrectNumber($num) {
        $num = str_replace(',', '.', $num);
        $num = preg_replace('/[^0-9\.]+/ui', '', $num);
        $num = preg_replace('/(\.)+/ui', '.', $num);
        return $num;
    }
}

if(!function_exists('pmGetExcludePostTypes')) {
    function pmGetExcludePostTypes() {
        return array('attachment', 'revision', 'nav_menu_item');
    }
}

if(!function_exists('pmGetAllowedPostTypes')) {
    function pmGetAllowedPostTypes() {
        $args = array(
            'public' => true
            );
        $postTypes = get_post_types($args);
        $excludePostTypes = pmGetExcludePostTypes();
        return array_diff($postTypes, $excludePostTypes);
    }
}

if(!function_exists('pmGetPostMapData')) {
    function pmGetPostMapData($post) {
        $mapData = array();
        $mapData['lat'] = get_post_meta($post->ID, 'pm_lat', 1) ? get_post_meta($post->ID, 'pm_lat', 1) : false;
        $mapData['lng'] = get_post_meta($post->ID, 'pm_lng', 1) ? get_post_meta($post->ID, 'pm_lng', 1) : false;
        $mapData['postMarker'] = get_post_meta($post->ID, 'pm_marker', 1) ? get_post_meta($post->ID, 'pm_marker', 1) : false;
        $mapData['mapHeight'] = get_option('pm_map_block_height') ? get_option('pm_map_block_height') . 'px' : '100%';
        $mapData['mapWidth'] = get_option('pm_map_block_width') ? get_option('pm_map_block_width') . 'px' : '100%';
        $mapData['mapMarkerIcons'] = get_option('pm_marker_icons') ? unserialize(get_option('pm_marker_icons')) : false;
        if(isCorrectArray($mapData['mapMarkerIcons']))
            $mapData['postMarkerIcon'] = plugins_url() . '/posts-maps/assets/images/' . $mapData['mapMarkerIcons'][$mapData['postMarker']];
        return $mapData;
    }
}

if(!function_exists('pmGetPostMapSettings')) {
    function pmGetPostMapSettings() {
        $mapSettings = array();
        $mapSettings['mapHeight'] = get_option('pm_map_block_height') ? get_option('pm_map_block_height') . 'px' : '100%';
        $mapSettings['mapWidth'] = get_option('pm_map_block_width') ? get_option('pm_map_block_width') . 'px' : '100%';
        $mapSettings['mapMarkerIcons'] = get_option('pm_marker_icons') ? unserialize(get_option('pm_marker_icons')) : false;
        return $mapSettings;
    }
}

if(!function_exists('issetAllKeysInArray')) {
	function issetAllKeysInArray($referenceArray, $array) {
		$issetAllKeys = false;
		if($referenceArray && $array && isCorrectArray($referenceArray) && isCorrectArray($array)) {
			$issetAllKeys = true;
			foreach($referenceArray as $key) {
				if(!array_key_exists($key, $array)) {
					$issetAllKeys = false;
					break;
				}
			}
		}
		return $issetAllKeys;
	}
}
?>