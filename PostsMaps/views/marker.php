<div class="wrap">
<h2><?php _e('Posts Maps marker icons') ?></h2>
<form action="<?php echo get_admin_url();?>admin.php?page=<?php echo $menuSlugs['posts_maps_marker_icons'];?>&action=save_marker_icons;?>" method="post" enctype="multipart/form-data">
	<?php
	$pmMarkerIcons = unserialize(get_option('pm_marker_icons'));
	if(isCorrectArray($pmMarkerIcons)):
	?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><h3><?php _e('Icon name') ?></h3></th>
					<td><h3><?php _e('Icon') ?></h3></td>
				</tr>
				<?php foreach($pmMarkerIcons as $pmMarkerIconName => $pmMarkerIconImg):?>
				<tr>
					<th scope="row"><?php echo $pmMarkerIconName;?></th>
					<td><img alt="<?php echo $pmMarkerIconName;?>" title="<?php echo $pmMarkerIconName;?>" src="<?php echo plugins_url() . '/posts-maps/assets/images/' . $pmMarkerIconImg;?>"/></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php
	endif;
	?>
</form>
</div>