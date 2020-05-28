<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );


/*=============================
=            LOGIN            =
=============================*/

function wpspx_login() {
?>
<div class="accountbox modal">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<p class="modal-card-title">Login to your account</p>
			<button class="delete" aria-label="close"><i class="far fa-times"></i></button>
		</header>
		<section class="modal-card-body">
			<div class="formcontent"></div>
		</section>
		<footer class="modal-card-foot">
		</footer>
	</div>
</div>
<script>
if (sessionStorage.getItem('spektrix_auth') === 'success') {
	var spektrixuserid = null;
	var userdetailssettings = {
		"async": true,
		"crossDomain": true,
		"url": "<?php echo SPEKTRIX_API_URL ?>customer",
		"method": "GET",
		"xhrFields": {
			"withCredentials": true
		},
		"headers": {
			"Content-Type": "application/json",
			"cache-control": "no-cache"
		},
		"statusCode": {
			401: function (error) {
				// simply ignore this error
			}
		},
		"processData": false
	}
	jQuery.ajax(userdetailssettings).done(function (userdetailsresponse) {
		if(typeof userdetailsresponse.id !== 'undefined' && userdetailsresponse.id !== '') {
			spektrixuserid = userdetailsresponse.id;
			jQuery('.menu-item-wpspx-login').html('<div class="greeting">Hello '+ userdetailsresponse.firstName +'</div><a class="is-info" href="<?php echo home_url('my-account'); ?>">My Account</a><a class="logoutlink" onclick="wpspxLogout();">Log Out</a>'
			);

		}
	}).fail(function(xhr, status, error) {
		jQuery('.menu-item-wpspx-login').html('<a class="loginlink" onclick="wpspxLogin()">Log in</a>');
	});

}
function wpspxLogout() {
	var settings = {
		"async": true,
		"crossDomain": true,
		"url": "<?php echo SPEKTRIX_API_URL ?>customer/deauthenticate",
		"method": "POST",
		"xhrFields": {
			"withCredentials": true
		},
		"headers": {
			"Content-Type": "application/json",
			"cache-control": "no-cache"
		},
		"processData": false
	}
	jQuery.ajax(settings).done(function (response) {
		location.reload();
	});
}

function wpspxLogin(message) {

	jQuery('.accountbox').addClass('is-active');
	jQuery('.delete').on("click", function(){
		jQuery('.accountbox').removeClass('is-active');
	});

	var lostpass = '<p>Please enter your email to reset your password.</p><div class="field"><div class="label"><label class="label">Email Address</label></div><div class="control"><input id="passwordresetemail" class="input" type="text" placeholder="your email" name="forgot-password" required></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div>';

	var logpaaslinks = '<button id="sendpassword" class="button btn button-primary">Reset Password<span class="loading"></span></button>';

	var loginform = '<p>Login to your account. Don\'t have one yet? You can <a class="registerlink" href="#">register here</a></p><div class="field"><div class="label"><label class="label">Email Address</label></div><div class="control"><input class="input" autocomplete="username" type="text" placeholder="Email" name="login-username" id="spektrix-username" required></div></div><div class="field"><div class="label"><label class="label">Password</label></div><div class="control"><input class="input" id="spektrix-password" autocomplete="current-password" type="password" placeholder="Password" name="login-password" required></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';

	var loginformlinks = '<button id="loginbutton" class="button btn button-primary">Login<span class="loading"></span></button><button id="forgotpassword" class="button btn button-secondary">Forgot password?</button>';

	var registerform = '<p>Please complete the below form to register for an account.</p><div class="field"><div class="label"><label class="label">Name</label></div><div class="control is-horizontal"><div class="field"><input class="input" type="text" placeholder="First Name" id="signup-firstname"></div><div class="field"><input class="input" type="text" placeholder="Last Name" id="signup-lastname"></div></div></div><div class="field"><label class="label">Date of Birth</label><div class="control"><input class="input" type="date" id="signup-birthdate"></div></div><div class="field"><label class="label">Phone Number</label><div class="control"><input class="input" type="text" id="signup-phone"></div></div><div class="field"><label class="label">Email Address</label><div class="control"><input class="input" type="email" id="signup-email"></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';

	var registerformlinks = '<button id="register" class="button btn button-primary">Register<span class="loading"></span></button>';


	jQuery('.modal-card-title').html('Login to your account');
	jQuery('.formcontent').html(loginform);
	jQuery('.modal-card-foot').html(loginformlinks);

	//login button
	jQuery('#loginbutton').on("click", function(){
		jQuery(this).children('span').addClass('show');
		var settings = {
			"async": true,
			"crossDomain": true,
			"url": "<?php echo SPEKTRIX_API_URL ?>customer/authenticate",
			"method": "POST",
			"xhrFields": {
				"withCredentials": true
			},
			"headers": {
				"Content-Type": "application/json",
				"cache-control": "no-cache"
			},
			"processData": false,
			"data": "{\r\n  \"email\": \"" + jQuery('#spektrix-username').val() + "\",\r\n  \"password\": \"" + jQuery('#spektrix-password').val() + "\"\r\n}"
		}
		jQuery.ajax(settings).done(function (response) {
			if(response.id !== '') {
				sessionStorage.setItem("spektrix_auth", "success");
				jQuery('.message').addClass('is-success');
				jQuery('.message .message-body').text('Sucessfully loggin in...');
				jQuery('.message').show();
				setTimeout(function() {
					jQuery('#loginbutton span').removeClass('show');
					jQuery('.accountbox').removeClass('is-active');
				},1000);
				setTimeout(function() {
					location.reload();
				},3000);
			}
			else {
				jQuery('.message').addClass('is-danger');
				jQuery('.message .message-body').text('There was an error.');
				jQuery('.message').show();
				jQuery('#loginbutton span').removeClass('show');
			}
		}).fail(function(xhr, status, error) {
			switch(xhr.status) {
				case 404:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('Invalid username or password');
					setTimeout(function() {
						jQuery('.message').show();
						jQuery('#loginbutton span').removeClass('show');
					},1000);
					break;
				case 401:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('There was an error trying to log you in.');
					setTimeout(function() {
						jQuery('.message').show();
						jQuery('#loginbutton span').removeClass('show');
					},1000);
					break;
				default:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('There was an error trying to log you in.');
					setTimeout(function() {
						jQuery('#loginbutton span').removeClass('show');
						jQuery('.message').show();
					},1000);
					break;
			}
		});
	});

	// forget password link
	jQuery('#forgotpassword').on("click", function(e) {
		jQuery('.modal-card-title').html('Forgot your password');
		jQuery('.formcontent').html(lostpass);
		jQuery('.modal-card-foot').html(logpaaslinks)
		jQuery('#sendpassword').on("click", function(e){
			jQuery(this).children('span').addClass('show');
			var emailaddress = jQuery('#passwordresetemail').val();
			if(emailaddress !== '') {
				var settings = {
					"async": true,
					"crossDomain": true,
					"url": "<?php echo SPEKTRIX_API_URL ?>customer/forgot-password?emailAddress=" + emailaddress,
					"method": "POST",
					"headers": {
						"Content-Type": "application/x-www-form-urlencoded",
						"cache-control": "no-cache"
					}
				}

				jQuery.ajax(settings).done(function (response) {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('Password reset email sent.');
					setTimeout(function() {
						jQuery('.message').show();
						jQuery('#sendpassword span').removeClass('show');
					},1000);
				}).fail(function(xhr, status, error) {
					switch(xhr.status) {
						case 404:
							jQuery('#sendpassword span').removeClass('show');
							jQuery('.message').addClass('is-danger');
							jQuery('.message .message-body').text('No account found with this email address.');
							jQuery('.message').show();
							break;
						default:
							jQuery('#sendpassword span').removeClass('show');
							jQuery('.message').addClass('is-danger');
							jQuery('.message .message-body').text('Sorry there was an error - please call the box office to reset your password.');
							jQuery('.message').show();
							break;
					}
				});
			}
			else {
				jQuery('#sendpassword span').removeClass('show');
				jQuery('.message .message-body').text('You must enter an email address');
				jQuery('.message').show();
			}
		});
		// back to login
		jQuery('#backtologin').on("click", function(e) {
			jQuery('.modal-card-title').html('Login to your account');
			jQuery('.formcontent').html(loginform);
			jQuery('.modal-card-foot').html(loginformlinks);
		});
	});

	// register button
	jQuery('.registerlink').on("click", function(e){
		jQuery('.modal-card-title').html('Sign uo for an account');
		jQuery('.formcontent').html(registerform);
		jQuery('.modal-card-foot').html(registerformlinks);
		jQuery('#register').on("click", function(e){
			jQuery(this).children('span').addClass('show');
			var settings = {
				"async": true,
				"crossDomain": true,
				"url": "<?php echo SPEKTRIX_API_URL ?>customer",
				"method": "POST",
				"xhrFields": {
					"withCredentials": true
				},
				"headers": {
					"Content-Type": "application/json",
					"cache-control": "no-cache"
				},
				"processData": false,
				"data": "{\r\n  \"birthDate\": \"" + jQuery('#signup-birthdate').val() + "\",\r\n  \"email\": \"" + jQuery('#signup-email').val() + "\",\r\n  \"firstName\": \"" + jQuery('#signup-firstname').val() + "\",\r\n  \"lastName\": \"" + jQuery('#signup-lastname').val() + "\",\r\n  \"mobile\": \"" + jQuery('#signup-phone').val() + "\",\r\n  \"password\": \"" + jQuery('#signup-password').val() + "\"\r\n}"
			}

			jQuery.ajax(settings).done(function (response) {
				if(response.id !== '') {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('Account sucessfully created, logging you in....');
					jQuery('.message').show();
					jQuery('#register span').removeClass('show');
					setTimeout(function() {
						location.reload();
					},3000);
				}
				else {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('There was an error.');
					setTimeout(function() {
						jQuery('.message').show();
						jQuery('#register span').removeClass('show');
					},1000);
				}
			}).fail(function(xhr, status, error) {
				switch(xhr.status) {
					default:
						jQuery('.message').addClass('is-success');
						jQuery('.message .message-body').text('There was an error.');
						setTimeout(function() {
							jQuery('.message').show();
							jQuery('#register span').removeClass('show');
						},1000);

						break;
				}
			});
		});
	});
}

</script>
<?php
}
add_action( 'wp_footer', 'wpspx_login', 99);



function wpspx_login_link()
{
	echo '<a class="loginlink button is-primary" onclick="wpspxLogin()">Log in</a>';
}
add_shortcode( 'wpspx_login_link', 'wpspx_login_link' );



function wpspx_custom_login_link ( $items, $args ) {
	$options = get_option( 'wpspx_login_settings' );
	if ($args->theme_location == $options['wpspx_login_link_location'] ) {
		$items .= '<li id="menu-item-wpspx-login" class="menu-item menu-item-wpspx-login"><a class="loginlink" onclick="wpspxLogin()">Log in</a></li>';
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'wpspx_custom_login_link', 10, 2 );
