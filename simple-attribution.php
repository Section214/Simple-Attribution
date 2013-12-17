<?php
/*
Plugin Name: Simple Attribution
Description: Allows bloggers to easily add an attribution link to sourced blog posts.
Version: 1.0
Author: Daniel J Griffiths
Author URI: http://www.ghost1227.com
 */

// Run activation hooks
register_activation_hook(__FILE__, 'simple_attribution_setup');

// Setup the plugin
function simple_attribution_setup() {
	add_option('simple_attribution_ctype', 'text');
	add_option('simple_attribution_caption', 'Attribution:');
	add_option('simple_attribution_icon', '1');
	add_option('simple_attribution_icon_url', '');
	add_option('simple_attribution_icon_height', '24');
	add_option('simple_attribution_disable', 'false');
}

// Add actions
add_action('add_meta_boxes', 'simple_attribution_meta');
add_action('save_post', 'simple_attribution_meta_save');
add_action('admin_menu', 'add_simple_attribution_menu');
add_action('admin_head', 'ghost_styles');

if(get_option('simple_attribution_disable') != 'true')
	add_filter('the_content', 'simple_attribution_return');

// Include stylesheets on dashboard
function ghost_styles() {
	echo '<link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'css/ghost.css" type="text/css">';
}

// Add admin menu
function add_simple_attribution_menu() {
	add_options_page(__('Simple Attribution'), __('Simple Attribution'), 'manage_options', 'simple-attribution', 'simple_attribution_options');
}

// Display options page
function simple_attribution_options() {
?>
	
	<div class="wrap" style="width: 1024px;">
		<h2><?php _e('Simple Attribution'); ?></h2>

		<?php if($_POST['updset'] == 'updset') {
			// Update options
			update_option('simple_attribution_ctype', $_POST['simple_attribution_ctype']);
			update_option('simple_attribution_caption', $_POST['simple_attribution_caption']);
			update_option('simple_attribution_icon', $_POST['simple_attribution_icon']);
			update_option('simple_attribution_icon_url', $_POST['simple_attribution_icon_url']);
			update_option('simple_attribution_icon_height', $_POST['simple_attribution_icon_height']);
			($_POST['simple_attribution_disable'] == 'checked') ? $simple_attribution_disable = 'true' : $simple_attribution_disable = 'false';
			update_option('simple_attribution_disable', $simple_attribution_disable); ?>

			<div class="updated fade below-h2" id="message" style="background-color: rgb(255,251,204);">
				<p><?php _e('Settings updated successfully.'); ?></p>
			</div>
		<?php } ?>

		<form action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=simple-attribution" method="post" name="success">
			<input type="hidden" name="updset" value="updset" />

			<table cellpadding="5" class="widefat post fixed" style="width: 600px;">
				<thead>
					<tr>
						<th scope="row" colspan=2>
							<strong><?php _e('General Settings'); ?></strong>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="simple_attribution_ctype">
								<strong><?php _e('Caption type'); ?>:</strong>
							</label>
						</th>
						<td>
							<input type="radio" name="simple_attribution_ctype" id="simple_attribution_ctype_text" value="text" <?php echo (get_option('simple_attribution_ctype') == 'text' ? 'checked' : ''); ?>>
								<label for="simple_attribution_ctype_text" style="padding: 0 15px 0 5px;">Text-based</label>
							</input>
							<input type="radio" name="simple_attribution_ctype" id="simple_attribution_ctype_image" value="image" <?php echo (get_option('simple_attribution_ctype') == 'image' ? 'checked' : ''); ?>>
								<label for="simple_attribution_ctype_image" style="padding: 0 15px 0 5px;">Image-based</label>
							</input>
						</td>
					</tr>
					<tr valign="top" id="simple_attribution_caption_row">
						<th scope="row">
							<label for="simple_attribution_caption">
								<strong><?php _e('Caption'); ?>:</strong>
							</label>
						</th>
						<td>
							<input type="text" name="simple_attribution_caption" id="simple_attribution_caption" value="<?php echo get_option('simple_attribution_caption'); ?>" style="width: 100%;">
						</td>
					</tr>
					<tr valign="top" id="simple_attribution_icon_row" style="display: none;">
						<th scope="row">
							<label for="simple_attribution_icon">
								<strong><?php _e('Icon'); ?>:</strong><br/>
							</label>
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/clip.png" style="height: 24px;" title="Clip">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/clip-light.png" style="height: 24px;" title="Clip (light)">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/clipboard.png" style="height: 24px;" title="Clipboard">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/clipboard-light.png" style="height: 24px;" title="Clip (light)">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/globe-1.png" style="height: 24px;" title="Globe 1">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/globe-1-light.png" style="height: 24px;" title="Globe 1 (light)">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/globe-2.png" style="height: 24px;" title="Globe 2">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/globe-2-light.png" style="height: 24px;" title="Globe 2 (light)">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/quote.png" style="height: 24px;" title="Quote">
							<img src="<?php echo plugin_dir_url(__FILE__); ?>img/quote-light.png" style="height: 24px;" title="Quote (light)">
						</th>
						<td>
							<select name="simple_attribution_icon" id="simple_attribution_icon" style="width: 100%;">
								<?php $active_icon = get_option('simple_attribution_icon');
								if($active_icon == '1') {
									echo '<option value="1">Clip</option>';
								} elseif($active_icon == '2') {
									echo '<option value="2">Clip (light)</option>';
								} elseif($active_icon == '3') {
									echo '<option value="3">Clipboard</option>';
								} elseif($active_icon == '4') {
									echo '<option value="4">Clipboard (light)</option>';
								} elseif($active_icon == '5') {
									echo '<option value="5">Globe 1</option>';
								} elseif($active_icon == '6') {
									echo '<option value="6">Globe 1 (light)</option>';
								} elseif($active_icon == '7') {
									echo '<option value="7">Globe 2</option>';
								} elseif($active_icon == '8') {
									echo '<option value="8">Globe 2 (light)</option>';
								} elseif($active_icon == '9') {
									echo '<option value="9">Quote</option>';
								} elseif($active_icon == '10') {
									echo '<option value="10">Quote (light)</option>';
								} elseif($active_icon == '11') {
									echo '<option value="11">Custom</option>';
								} ?>
								<option disabled>----------</option>
								<option value="1">Clip</option>
								<option value="2">Clip (light)</option>
								<option value="3">Clipboard</option>
								<option value="4">Clipboard (light)</option>
								<option value="5">Globe 1</option>
								<option value="6">Globe 1 (light)</option>
								<option value="7">Globe 2</option>
								<option value="8">Globe 2 (light)</option>
								<option value="9">Quote</option>
								<option value="10">Quote (light)</option>
								<option value="11">Custom</option>
							</select>
						</td>
					</tr>
					<tr valign="top" id="simple_attribution_icon_url_row" style="display: none;">
						<th scope="row">
							<label for="simple_attribution_icon_url">
								<strong><?php _e('Custom Icon URL'); ?>:</strong>
							</label>
						</th>
						<td>
							<input type="text" name="simple_attribution_icon_url" id="simple_attribution_icon_url" value="<?php echo get_option('simple_attribution_icon_url'); ?>" style="width: 100%;">
						</td>
					</tr>
					<tr valign="top" id="simple_attribution_icon_height_row" style="display: none;">
						<th scope="row">
							<label for="simple_attribution_icon_height">
								<strong><?php _e('Custom Icon Height'); ?> <small style="font-size: .65em;">(Enter as '24', not '24px')</small>:</strong>
							</label>
						</th>
						<td>
							<input type="text" name="simple_attribution_icon_height" id="simple_attribution_icon_height" value="<?php echo get_option('simple_attribution_icon_height'); ?>" style="width: 100%;">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="simple_attribution_disable">
								<strong><?php _e('Disable auto-attribution'); ?>:</strong>
								<p class="gnote"><small><?php _e('Useful if you would prefer to add attribution to a specific place in your template as opposed to allowing it to auto-place at the bottom of posts.'); ?></small></p>
								<p class="gnote"><small><?php _e('To manually add Simple Attribution to your template, simple add \'simple_attribution()\' wherever you would like it to be displayed.'); ?></small></p>
							</label>
						</th>
						<td>
							<input id="simple_attribution_disable" name="simple_attribution_disable" value="checked" type="checkbox" <?php echo (get_option('simple_attribution_disable') == 'true' ? 'checked' : ''); ?> />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" colspan=2>
							<strong style="color: #ff0000;"><?php _e('Note'); ?>:</strong> <?php _e('You can change attribution styling by overriding the .simple-attribution class.'); ?>
						</th>
					</tr>
				</tbody>
			</table>
			<div id="simple_attribution_actions" style="width: 600px; text-align: right; padding-top: 10px;">
				<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Update'); ?>" />
			</div>
		</form>

		<script>
			jQuery(document).ready(function() {
				jQuery("input[name='simple_attribution_ctype']").change(function() {
					if (jQuery("input[name='simple_attribution_ctype']:checked").val() == 'text') {
						jQuery("tr#simple_attribution_caption_row").css("display", "");
						jQuery("tr#simple_attribution_icon_row").css("display", "none");
						jQuery("tr#simple_attribution_icon_url_row").css("display", "none");
						jQuery("tr#simple_attribution_icon_height_row").css("display", "none");
					} else if (jQuery("input[name='simple_attribution_ctype']:checked").val() == 'image') {
						jQuery("tr#simple_attribution_caption_row").css("display", "none");
						jQuery("tr#simple_attribution_icon_row").css("display", "");
						jQuery("tr#simple_attribution_icon_height_row").css("display", "");
					}
				}).change();
				jQuery("select[name='simple_attribution_icon']").change(function() {
					if (jQuery(this).val() == '11') {
						jQuery("tr#simple_attribution_icon_url_row").css("display", "");
					} else {
						jQuery("tr#simple_attribution_icon_url_row").css("display", "none");
					}
				}).change();
			});
		</script>
	</div>
<?php }

// Add the post meta box
function simple_attribution_meta() {
	add_meta_box('simple_attribution_meta', 'Simple Attribution', 'simple_attribution_meta_cb', 'post', 'side', 'low');
}

// Callback for the post meta box
function simple_attribution_meta_cb($post) {
	global $post;

	// Define necessary variables
	$result = get_post_custom($post->ID);
	$simple_attribution_title = isset($result['simple_attribution_title']) ? esc_attr($result['simple_attribution_title'][0]) : '';
	$simple_attribution_url = isset($result['simple_attribution_url']) ? esc_attr($result['simple_attribution_url'][0]) : '';

	// Safety first!
    wp_nonce_field( 'simple_attribution_nonce', 'meta_box_nonce' ); 

	// Print the actual post meta box
	echo '<p>';
	echo '<label for="simple_attribution_title">Attribution Title:</label>';
	echo '<input type="text" id="simple_attribution_title" name="simple_attribution_title" value="' . $simple_attribution_title . '" class="widefat" />';
	echo '</p><p>';
	echo '<label for="simple_attribution_url">Attribution URL:</label>';
	echo '<input type="text" id="simple_attribution_url" name="simple_attribution_url" value="' . $simple_attribution_url . '" class="widefat" />';
	echo '</p>';
}

// Save the post meta box
function simple_attribution_meta_save($post_id) {

	// Skip if this is an autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	// Skip if nonce doesn't exist or isn't verifiable
	if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'simple_attribution_nonce')) return;

	// Skip if user can't edit this post
	if (!current_user_can('edit_post')) return;

	// Save the data
	if (isset($_POST['simple_attribution_title']))  
		update_post_meta($post_id, 'simple_attribution_title', esc_attr($_POST['simple_attribution_title']));
	if (isset($_POST['simple_attribution_url']))  
		update_post_meta($post_id, 'simple_attribution_url', esc_attr($_POST['simple_attribution_url']));
}

// Display attribution
function simple_attribution_return($content) {
	global $post;

	// Define necessary variables
	$simple_attribution_title = get_post_meta($post->ID, 'simple_attribution_title', true);
	$simple_attribution_url = get_post_meta($post->ID, 'simple_attribution_url', true);
	$active_icon = get_option('simple_attribution_icon');

	if($active_icon == '1') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clip.png';
	} elseif($active_icon == '2') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clip-light.png';
	} elseif($active_icon == '3') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clipboard.png';
	} elseif($active_icon == '4') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clipboard-light.png';
	} elseif($active_icon == '5') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-1.png';
	} elseif($active_icon == '6') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-1-light.png';
	} elseif($active_icon == '7') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-2.png';
	} elseif($active_icon == '8') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-2-light.png';
	} elseif($active_icon == '9') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/quote.png';
	} elseif($active_icon == '10') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/quote-light.png';
	} elseif($active_icon == '11') {
		$active_icon = get_option('simple_attribution_icon_url');
	}

	// Display attribution
	if(is_single() && !empty($simple_attribution_title) && !empty($simple_attribution_url)) {
		if(get_option('simple_attribution_ctype') == 'image') {
			return $content . '<span class="simple-attribution"><img src="' . $active_icon . '" style="height: ' . get_option('simple_attribution_icon_height') . 'px; display: inline;"> <a href="' . $simple_attribution_url . '" target="_new">' . $simple_attribution_title . '</a></span>';
		} else {
			return $content . '<span class="simple-attribution">' . get_option('simple_attribution_caption') . ' <a href="' . $simple_attribution_url . '" target="_new">' . $simple_attribution_title . '</a></span>';
		}
	} else {
		return $content;
	}
}

// Display attribution (manual placement)
function simple_attribution() {
	global $post;

	// Define necessary variables
	$simple_attribution_title = get_post_meta($post->ID, 'simple_attribution_title', true);
	$simple_attribution_url = get_post_meta($post->ID, 'simple_attribution_url', true);
	$active_icon = get_option('simple_attribution_icon');

	if($active_icon == '1') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clip.png';
	} elseif($active_icon == '2') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clip-light.png';
	} elseif($active_icon == '3') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clipboard.png';
	} elseif($active_icon == '4') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/clipboard-light.png';
	} elseif($active_icon == '5') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-1.png';
	} elseif($active_icon == '6') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-1-light.png';
	} elseif($active_icon == '7') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-2.png';
	} elseif($active_icon == '8') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/globe-2-light.png';
	} elseif($active_icon == '9') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/quote.png';
	} elseif($active_icon == '10') {
		$active_icon = plugin_dir_url(__FILE__) . 'img/quote-light.png';
	} elseif($active_icon == '11') {
		$active_icon = get_option('simple_attribution_icon_url');
	}

	// Display attribution
	if(is_single() && !empty($simple_attribution_title) && !empty($simple_attribution_url)) {
		if(get_option('simple_attribution_ctype') == 'image') {
			echo '<span class="simple-attribution"><img src="' . $active_icon . '" style="height: ' . get_option('simple_attribution_icon_height') . 'px; display: inline;"> <a href="' . $simple_attribution_url . '" target="_new">' . $simple_attribution_title . '</a></span>';
		} else {
			echo '<span class="simple-attribution">' . get_option('simple_attribution_caption') . ' <a href="' . $simple_attribution_url . '" target="_new">' . $simple_attribution_title . '</a></span>';
		}
	}
}
