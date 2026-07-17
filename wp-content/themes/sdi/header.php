<?php
/**
 * Header: <head>, opening body, sticky site header.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$sdi_active = sdi_active_nav();
$sdi_logo   = SDI_URI . '/assets/img/sdi-logo-color.png';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#sdi-content">Aller au contenu</a>

<div class="sdi-root">
	<header class="sdi-header" data-sdi-header>
		<div class="sdi-header__halo" aria-hidden="true"></div>
		<div class="sdi-header__bar">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sdi-header__logo" aria-label="SDi — Accueil">
				<img src="<?php echo esc_url( $sdi_logo ); ?>" alt="SDi — Agence web &amp; IA à Dijon et Paris">
			</a>

			<nav class="sdi-nav" aria-label="Navigation principale">
				<?php foreach ( sdi_nav_items() as $item ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" class="sdi-navlink<?php echo ( $sdi_active === $item['key'] ) ? ' is-active' : ''; ?>">
						<?php echo esc_html( $item['label'] ); ?>
						<?php if ( ! empty( $item['dot'] ) ) : ?><span class="sdi-nav__dot" aria-hidden="true"></span><?php endif; ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="sdi-header__right">
				<a href="tel:+33980806296" class="sdi-header__phone"><?php sdi_the_icon( 'phone', 16 ); ?>09&nbsp;80&nbsp;80&nbsp;62&nbsp;96</a>
				<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-header__cta"><?php sdi_the_icon( 'mail', 16 ); ?>Nous contacter</a>
				<button type="button" class="sdi-burger" data-sdi-burger aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="sdi-mobile-menu"><?php sdi_the_icon( 'menu', 22 ); ?></button>
			</div>
		</div>

		<div class="sdi-mobile-menu" id="sdi-mobile-menu" data-sdi-mobile-menu>
			<?php foreach ( sdi_nav_items() as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo ( $sdi_active === $item['key'] ) ? ' class="is-active"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
			<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-mobile-contact">Nous contacter</a>
		</div>
	</header>

	<main id="sdi-content">
