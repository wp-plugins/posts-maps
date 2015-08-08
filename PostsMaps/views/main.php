<div class="wrap">
<h2><?php _e('Posts Maps settings') ?></h2>
<form method="post" action="options.php">
	<?php 
	wp_nonce_field('update-options');
	$post_types = pmGetAllowedPostTypes();
	if(isCorrectArray($post_types)):
	?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Post types') ?></th>
					<td>
						<fieldset>
							<?php 
							$pm_enabled_types = get_option('pm_enabled_types');
							foreach($post_types as $key => $post_type):
							?>
								<label><input name="pm_enabled_types[]" type="checkbox" value="<?php echo $key;?>"<?php if(isCorrectArray($pm_enabled_types) && in_array($key, $pm_enabled_types)):?> checked="checked"<?php endif;?>/> <?php _e($post_type);?></label><br>
							<?php 
							endforeach;
							?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Map block height') ?></th>
					<td>
						<fieldset>
							<?php
							$pm_map_block_height = get_option('pm_map_block_height');
							if($pm_map_block_height === false)
								$pm_map_block_height = '300';
							?>
							<label><input name="pm_map_block_height" type="text" value="<?php echo $pm_map_block_height;?>"/>px</label><br>
							(<?php _e('an empty value is 100%');?>)
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Map block width') ?></th>
					<td>
						<fieldset>
							<label><input name="pm_map_block_width" type="text" value="<?php echo get_option('pm_map_block_width');?>"/>px</label><br>
							(<?php _e('an empty value is 100%');?>)
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	<?php
	endif;
	?>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="pm_enabled_types,pm_map_block_height,pm_map_block_width" />
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
</form>
</div>