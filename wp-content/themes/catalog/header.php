<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

check_permissions();

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<?php do_action( 'foundationpress_after_body' ); ?>

	<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) : ?>
		<?php get_template_part( 'template-parts/mobile-off-canvas' ); ?>
	<?php endif; ?>

	<?php do_action( 'foundationpress_layout_start' ); ?>

	<header class="site-header" role="banner">
		<div class="site-title-bar title-bar" <?php foundationpress_title_bar_responsive_toggle() ?>>
			<div class="title-bar-left">
				<button class="menu-icon" type="button" data-toggle="<?php foundationpress_mobile_menu_id(); ?>"></button>
				<span class="site-mobile-title title-bar-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
			</div>
		</div>

		<nav class="site-navigation top-bar" role="navigation">
			<div class="top-bar-left">
				<div class="site-desktop-title top-bar-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</div>
			</div>
			<div class="top-bar-right">
				<?php foundationpress_top_bar_r(); ?>

				<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) === 'topbar' ) : ?>
					<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
				<?php endif; ?>
			</div>
		</nav>

		<?php if (is_user_logged_in()) { ?>
			<script>
				$(document).ready(function() {
					$('ul.desktop-menu, ul.mobile-nav').find('li.account-dropdown ul').append('<li><a href="<?php echo wp_logout_url( '/' ); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>' );
				});
			</script>
		<?php } ?>

	</header>

	<section class="container">
		<?php if ( isset($_GET['message']) && ($_GET['message'] != "") ) { ?>
			<div id="messages">
				<div class="grid-x grid-padding-x grid-container">
					<div class="cell">
						<div class="<?php echo $_GET['type']; ?> callout" data-closable="slide-out-right">
						  <span><?php echo $_GET['message']; ?></span>
						  <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php do_action( 'foundationpress_after_header' );
