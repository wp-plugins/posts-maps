<?php 
if(isset($post) && $post && $lat && $lng):
	$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array('thumbnail'));
	$thumbUrl = $thumb['0'];
	?>
	<div id="pm-map" style="height:<?php echo $mapHeight;?>;width:<?php echo $mapWidth;?>;min-height:50px;"></div>
	<script type="text/javascript">
		var pmMap = {};
		pmMap.lat = '<?php echo $lat;?>';
		pmMap.lng = '<?php echo $lng;?>';
		pmMap.title = '<?php echo $post->post_title;?>';
		pmMap.img = '<?php echo $thumbUrl;?>';
		pmMap.markerIcon = '<?php echo plugins_url() . '/posts-maps/assets/images/' . $mapMarkerIcons[$postMarker];?>';
		initialize(pmMap)
	</script>
	<?php 
endif;
?>