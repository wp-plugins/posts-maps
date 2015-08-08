<div class="pm-admin-block">
	<div class="pm-admin-left-block">
		<input type="hidden" name="pm_fields_nonce" value="<?php echo wp_create_nonce('pm-fields-save'); ?>" />
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Lat') ?></th>
					<td>
						<input id="pm_lat" name="pm_fields[pm_lat]" type="text" value="<?php echo get_post_meta($post->ID, 'pm_lat', 1) ? get_post_meta($post->ID, 'pm_lat', 1) : '';?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Lng') ?></th>
					<td>
						<input id="pm_lng" name="pm_fields[pm_lng]" type="text" value="<?php echo get_post_meta($post->ID, 'pm_lng', 1) ? get_post_meta($post->ID, 'pm_lng', 1) : '';?>"/>
					</td>
				</tr>
				<?php
				$pmMarkerIcons = unserialize(get_option('pm_marker_icons'));
				if(isCorrectarray($pmMarkerIcons)):
				?>
				<tr>
					<th scope="row"><?php _e('Marker icon') ?></th>
					<td>
						<select id="pm_marker_icon" name="pm_fields[pm_marker]">
							<?php foreach($pmMarkerIcons as $pmMarkerIconName => $pmMarkerIconImg):?>
							<option value="<?php echo $pmMarkerIconName;?>"<?php if(get_post_meta($post->ID, 'pm_marker', 1) && get_post_meta($post->ID, 'pm_marker', 1) == $pmMarkerIconName): $currentIcon = $pmMarkerIconName;?> selected="selected"<?php endif;?> data-img="<?php echo plugins_url() . '/posts-maps/assets/images/' . $pmMarkerIconImg;?>"><?php echo $pmMarkerIconName;?></option>
							<?php endforeach;?>
						</select>
						<span id="pm_marker_icon_preview">
							<?php if(isset($currentIcon) && isset($pmMarkerIcons[$currentIcon])):?>
								<img src="<?php echo plugins_url() . '/posts-maps/assets/images/' . $pmMarkerIcons[$currentIcon];?>"/>
							<?php endif;?>
						</span>
					</td>
				</tr>
				<?php
				endif;
				?>
			</tbody>
		</table>
	</div>
	<div class="pm-admin-right-block">
		<div id="pm-map" style="height:300px;width:100%;"></div>
		<input type="text" name="search-input" id="search-input" value="" style="margin: 10px 10px 0px;border:0 none;line-height: 28px;border-radius: 2px;width: 25%;min-width: 100px;"/>
		<script type="text/javascript">
			var pmMap = {};
			pmMap.lat = '<?php echo get_post_meta($post->ID, 'pm_lat', 1) ? get_post_meta($post->ID, 'pm_lat', 1) : 0;?>';
			pmMap.lng = '<?php echo get_post_meta($post->ID, 'pm_lng', 1) ? get_post_meta($post->ID, 'pm_lng', 1) : 0;?>';
			pmMap.title = '<?php _e('Pick a point on the map') ?>';
			pmMap.changeText = '<?php _e('Change position on the new') ?>';
			adminInitialize(pmMap);

			jQuery(function($) {
				$(document).on('change', '#pm_marker_icon', function(event) {
					$('#pm_marker_icon_preview').html('<img src="' + $('#pm_marker_icon option:selected').attr('data-img') + '"/>');
				});
			});
		</script>
	</div>
</div>