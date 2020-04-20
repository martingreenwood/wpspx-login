<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_init', 'wpspx_login_settings_init' );

function wpspx_login_settings_init(  ) {

	// BASKET Settings

	register_setting( 'wpspxLoginPage', 'wpspx_login_settings' );

	add_settings_section(
		'wpspx_wpspxLoginPage_section',
		__( 'Settings for your login model', 'wpspx' ),
		'wpspx_login_section_callback',
		'wpspxLoginPage'
	);

	add_settings_field(
		'wpspx_login_link_location',
		__( 'Login Link Location', 'wpspx' ),
		'wpspx_login_link_location_render',
		'wpspxLoginPage',
		'wpspx_wpspxLoginPage_section'
	);

	add_settings_field(
		'wpspx_login_colour',
		__( 'Login Colours', 'wpspx' ),
		'wpspx_login_colour_render',
		'wpspxLoginPage',
		'wpspx_wpspxLoginPage_section'
	);

}

// Login background colour
function wpspx_login_colour_render(  ) {

	$options = get_option( 'wpspx_login_settings' );
	?>
	<input type='text' class="wpspx-color-field" name='wpspx_login_settings[wpspx_login_background_colour]' value='<?php echo $options['wpspx_login_background_colour']; ?>'>
	<?php
}

// Login background colour
function wpspx_login_link_location_render(  ) {

	$options = get_option( 'wpspx_login_settings' );
	$get_nav_menus = get_registered_nav_menus();
	?>
	<select class="wpspx_login_link_location" name="wpspx_login_settings[wpspx_login_link_location]">
		<option value="0" <?php if ( $options['wpspx_login_link_location'] == '0' ) echo 'selected="selected"'; ?>>None</option>
	<?php foreach ($get_nav_menus as $key => $value): ?>
		<option value="<?php echo $key ?>" <?php if ( $options['wpspx_login_link_location'] == $key ) echo 'selected="selected"'; ?>><?php echo $value ?></option>
	<?php endforeach ?>
	</select>
	<?php
}


function wpspx_login_section_callback(  ) {

	echo __( '<p>Please select the colours you would like for your login box.</p>', 'wpspx' );

}

function wpspx_login_options_page(  ) {
		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])): ?>
					<div class="notice notice-success is-dismissible">
						<p><strong>Login Settings Saved.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxLoginPage' );
								do_settings_sections( 'wpspxLoginPage' );
								?>
								<br /><br /><?php
								$license = get_option( 'wpspx_licence_settings' );
								$key = $license['wpspx_license_key'];
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button('Save Settings');
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}
