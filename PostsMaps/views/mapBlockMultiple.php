<?php 
if(isset($posts) && isCorrectArray($posts)):
	?>
	<div id="pm-map" style="height:<?php echo $mapSettings['mapHeight'];?>;width:<?php echo $mapSettings['mapWidth'];?>;min-height:50px;"></div>
	<script type="text/javascript">
		var pmMapData = jQuery.parseJSON('<?php echo json_encode($posts);?>');
		initializeMultiple(pmMapData);
	</script>
	<?php 
endif;
?>