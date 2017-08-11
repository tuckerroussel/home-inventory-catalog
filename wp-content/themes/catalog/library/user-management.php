<?php

// ============================
// checks the permissions and logged-in state of user
// called from the header
// ============================
function check_permissions() {
	// if page is accessible to logged in users only
	if (get_field('access') == 'loggedin') {
		// if the user is not logged in, send them to the login page
		if (!is_user_logged_in()) {
			$redirect_url = urlencode( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			$redirect = 'Location: ' . get_bloginfo('url') . '/login?redirect=' . $redirect_url;
			header($redirect);
			exit;
		} else {
			if (is_front_page()) {
				$redirect = 'Location: ' . get_field('logged_in_user_redirect');
				header($redirect);
				exit;
			}
		}
	} else {
		if (is_front_page() && is_user_logged_in()) {
			$redirect = 'Location: ' . get_field('logged_in_user_redirect');
			header($redirect);
			exit;
		}
	}

	global $allowedPosts;
	global $allowedPostsStrings;
	global $post;

	$allowedPosts = array();

	$args = array(
		'post_type'              => array( 'post', 'page', ),
		'post_status'            => array( 'publish' ),
		'posts_per_page'         => '-1',
		'meta_query'             => array(
			'relation' => 'OR',
			array(
				'key'     => 'access',
				'value'   => 'everyone',
				'compare' => '=',
			),
			array(
				'relation' => 'AND',
				array(
					'key'     => 'access',
					'value'   => 'loggedin',
					'compare' => '=',
				),
				array(
					'key'     => 'logged_in_roles',
				),
			),
		),
	);

	$posts = get_posts( $args );

	$user = wp_get_current_user();
	if (!in_array('all', get_field('logged_in_roles'))) {
		if ( in_array( 'administrator', (array) $user->roles ) ) {

			if ( !empty($posts) ) {
				foreach ($posts as $post) {
					setup_postdata($post);
					if (in_array('administrator', get_field('logged_in_roles'))) {
						array_push($allowedPosts, $post->ID);
					}
					wp_reset_postdata();
				}
			}

		} else if ( in_array( 'editor', (array) $user->roles ) ) {

			if ( !empty($posts) ) {
				foreach ($posts as $post) {
					setup_postdata($post);
					if (in_array('editor', get_field('logged_in_roles'))) {
						array_push($allowedPosts, $post->ID);
					}
					wp_reset_postdata();
				}
			}

		}
	}

	if (empty($allowedPosts)) {
		$allowedPostsStrings = "-1";
	}
}

// ============================
// shortcode to generate frontend login form
// ============================
function custom_login( $atts ) {

	$html = '';

	// Attributes
	$atts = shortcode_atts(
		array(
			'redirect' => get_bloginfo('url') . '/dashboard',
		),
		$atts
	);

	$args = array(
		'echo'           => false,
		'remember'       => true,
		'redirect'       => $atts['redirect'],
		'form_id'        => 'loginform',
		'id_username'    => 'user_login',
		'id_password'    => 'user_pass',
		'id_remember'    => 'rememberme',
		'id_submit'      => 'wp-submit',
		'label_username' => __( 'Username' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in'   => __( 'Log In' ),
		'value_username' => '',
		'value_remember' => false
	);

	$html .= '<div class="callout secondary" style="max-width: 350px;">';
	$html .= '<p><strong>Login</strong></p>';
	$html .= wp_login_form( $args );
	$html .= '<a href="' . get_bloginfo('url') . '/wp-login.php?action=lostpassword">Forgot password?</a>';
	$html .= '</div>';

	return $html;

}
add_shortcode( 'login', 'custom_login' );


// ============================
// hides frontend admin bar for all users
// blocks access to wp-admin for non administrator roles
// ============================
add_action( 'init', 'blockusers_init' );
function blockusers_init() {
	show_admin_bar( false );
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url() );
		exit;
	}
}

// ============================
// change logo on default WordPress login pages
// ============================
function nexecute_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
          background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo/dark-bg-logo.png);
					height:44px;
					width:250px;
					background-size: 250px 44px;
					background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'nexecute_login_logo' );

add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return get_bloginfo('url');
}

