=== Plugin Name ===
Contributors: dmitrik86
Tags: map, google map, post map, simple google map
Requires at least: 4.0
Tested up to: 4.2.4
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin allows to add google map to each post quickly and simple.

== Description ==

Plugin "Posts maps" allows to add the google map to your post.

1. It display map for one post.

2. It display map with list of points for a given parameters.

3. It has a list of default icons.

4. It allows to use your templates for display map.


Simple use

Search necessary place via search bar and click right button on the map or marker.

Add shortcodes or template tags for action map.

Your map is ready.

== Installation ==

= Installation and configuration: =

1. Install the plugin.

2. Choose the settings of the plugin.

3. Select the post types for which you want to add a map.

4. Specify the size of the map (if you left field empty, then the height and width of the block will be equal to 100%).



= To show the map: =

1. Add new/edit post

2. Search necessary point on the map and click the right mouse button

3. Select a marker and save


To show the map you need to add shortcode [pm-show-map] to the content 




= Shortcodes: =

`[pm-show-map]`
It displays a map for the current post.



`[pm-show-map-by-post-types]`
It displays a map with all points of a posts for a given post types.

For example, `[pm-show-map-by-post-types store market]`
It displays a map for the post types "store" and "market".



`[pm-show-map-by-post-id]`
It displays a map of a posts for a given post_id

For example, 
`[pm-show-map-by-post-id 135 148 1120]`
It displays a map with points of posts 135, 148 and 1120.



= Template tags: =

Display map for one post
`<?php showPostMap($post_ID);?>`
It displays a map for a given post_id

Parameters
`post_ID (integer)(required)`



Display map for list of post types
`<?php showPostsMapByPostTypes($post_types);?>`
It displays a map of a posts for a given post types.

Parameters
`post_types(integer/array)(required)`

For example, 
`<?php showPostsMapByPostTypes(array('store','market'));?>`
displays a map for the post types "store" and "market".



Display map for list of post ID
`<?php showPostsMapByPostId(posts_ID);?>`
It displays a map with points of a posts for a given posts ID
`posts_ID(integer/array)(required)`



= For Developers =

If you do not want to use the default plugin templates, tou can to create your template:

Create a folder:

`pmViews`
in active theme.


Add template:

`mapBlock.php`
to display the map for one post via shortcode [pm-show-map] or template tag showPostMap(post ID)

`mapBlockMultiple.php`
to display the map with a list of points using the shortcode [pm-show-map-by-post-types] or [pm-show-map-by-post-id] or by using the template tag showPostsMapByPostTypes(post types) or showPostsMapByPostId(posts ID)


Template variables mapBlock.php:

`$post - the current post

$lat - latitude for a current post

$lng - longitude for a current post

$postMarker - marker for a current post

$mapMarkerIcons - an associative array with the list of icons

$mapHeight - height of a map

$mapWidth - width of a map`


to create a map add this javascript to template:

`var pmMap = {};

pmMap.lat = '<?php echo $lat;?>';

pmMap.lng = '<?php echo $lng;?>';

pmMap.title = '<?php echo $post->post_title;?>';

pmMap.img = '<?php echo $thumbUrl;?>';

pmMap.markerIcon = '<?php echo plugins_url() . '/posts-maps/assets/images/' . $mapMarkerIcons[$postMarker];?>';

initialize(pmMap)`



Template variables mapBlockMultiple.php:

`$posts - a list of selected posts`

Each item contains:

`$post_item->ID - post ID

$post_item->title - post title

$post_item->url - post url

$post_item->thumbUrl - thumbnail url for a post

$post_item->lat - latitude for a post

$post_item->lng - Longitude for a post

$post_item->postMarker - post marker

$post_item->postMarkerIcon - url icon for a given marker post`


to create a map add this javascript to template:

`var pmMapData = jQuery.parseJSON('<?php echo json_encode($posts);?>');

initializeMultiple(pmMapData);`

== Screenshots ==

1. Settings
2. Search place and click right button on map or marker
3. Add shortcode