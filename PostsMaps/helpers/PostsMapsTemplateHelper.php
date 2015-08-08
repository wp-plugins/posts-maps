<?php
if(!function_exists('showPostMap')) {
    function showPostMap($postId) {
        if(isCorrectNumber($postId)) {
            global $PostsMaps;
            $PostsMaps->showMap($postId);
        }
        return false;
    }
}

if(!function_exists('showPostsMapByPostTypes')) {
    function showPostsMapByPostTypes($postTypes) {
        global $PostsMaps;
        $PostsMaps->showMapByPostTypes($postTypes);
    }
}

if(!function_exists('showPostsMapByPostId')) {
    function showPostsMapByPostId($postId) {
        global $PostsMaps;
        $PostsMaps->showMapByPostId($postId);
    }
}
?>