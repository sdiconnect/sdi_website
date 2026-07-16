<?php
/**
 * SDi design-system component helpers (Button, Input, eyebrow, breadcrumb).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Render a design-system Button.
 *
 * @param array $args {
 *   @type string $label     Button text.
 *   @type string $variant   'primary' | 'secondary'. Default 'primary'.
 *   @type string $size      'md' | 'lg'. Default 'md'.
 *   @type bool   $full       Full width. Default false.
 *   @type string $href      If set, renders <a>, else <button>.
 *   @type string $icon      Optional trailing Lucide icon name.
 *   @type string $type      Button type when not a link. Default 'button'.
 *   @type string $class     Extra classes.
 *   @type array  $attrs     Extra HTML attributes (key => value).
 * }
 * @return string
 */
function sdi_button( $args = array() ) {
	$a = wp_parse_args(
		$args,
		array(
			'label'   => '',
			'variant' => 'primary',
			'size'    => 'md',
			'full'    => false,
			'href'    => '',
			'icon'    => '',
			'type'    => 'button',
			'class'   => '',
			'attrs'   => array(),
		)
	);

	$classes = array( 'sdi-btn', 'sdi-btn--' . $a['variant'], 'sdi-btn--' . $a['size'] );
	if ( $a['full'] ) { $classes[] = 'sdi-btn--full'; }
	if ( $a['class'] ) { $classes[] = $a['class']; }

	$attr_html = '';
	foreach ( (array) $a['attrs'] as $k => $v ) {
		$attr_html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
	}

	$inner = esc_html( $a['label'] );
	if ( $a['icon'] ) {
		$inner .= sdi_icon( $a['icon'], 18 );
	}

	if ( $a['href'] ) {
		return sprintf(
			'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
			esc_url( $a['href'] ),
			esc_attr( implode( ' ', $classes ) ),
			$attr_html, // phpcs:ignore WordPress.Security.EscapeOutput -- attrs escaped above.
			$inner // phpcs:ignore WordPress.Security.EscapeOutput -- label escaped, icon trusted.
		);
	}

	return sprintf(
		'<button type="%1$s" class="%2$s"%3$s>%4$s</button>',
		esc_attr( $a['type'] ),
		esc_attr( implode( ' ', $classes ) ),
		$attr_html, // phpcs:ignore WordPress.Security.EscapeOutput -- attrs escaped above.
		$inner // phpcs:ignore WordPress.Security.EscapeOutput -- label escaped, icon trusted.
	);
}

/**
 * Render a labelled text input (with optional leading icon).
 *
 * @param array $args {
 *   @type string $label
 *   @type string $name
 *   @type string $type        Default 'text'.
 *   @type string $placeholder
 *   @type string $icon        Optional Lucide icon name.
 *   @type bool   $required
 *   @type string $value
 * }
 * @return string
 */
function sdi_input( $args = array() ) {
	$a = wp_parse_args(
		$args,
		array(
			'label'       => '',
			'name'        => '',
			'type'        => 'text',
			'placeholder' => '',
			'icon'        => '',
			'required'    => false,
			'value'       => '',
		)
	);

	$id       = 'sdi-' . sanitize_title( $a['name'] ? $a['name'] : $a['label'] );
	$required = $a['required'] ? ' required' : '';

	$icon_html = '';
	$wrap_open = '<div class="sdi-field__wrap">';
	if ( $a['icon'] ) {
		$icon_html = '<span class="sdi-field__icon">' . sdi_icon( $a['icon'], 17 ) . '</span>';
	}

	return sprintf(
		'<div class="sdi-field"><label class="sdi-field__label" for="%1$s">%2$s</label>%3$s%4$s<input class="sdi-input" type="%5$s" id="%1$s" name="%6$s" placeholder="%7$s" value="%8$s"%9$s></div></div>',
		esc_attr( $id ),
		esc_html( $a['label'] ),
		$wrap_open,
		$icon_html, // phpcs:ignore WordPress.Security.EscapeOutput -- trusted icon SVG.
		esc_attr( $a['type'] ),
		esc_attr( $a['name'] ),
		esc_attr( $a['placeholder'] ),
		esc_attr( $a['value'] ),
		$required // phpcs:ignore WordPress.Security.EscapeOutput -- literal.
	);
}

/**
 * Eyebrow label (mono, uppercase, // prefix already included in $text if wanted).
 *
 * @param string $text  Text (caller includes the "// " prefix).
 * @param string $color CSS color value. Default brand primary.
 * @return string
 */
function sdi_eyebrow( $text, $color = 'var(--brand-primary)' ) {
	return sprintf(
		'<span class="sdi-eyebrow" style="color:%2$s;">%1$s</span>',
		esc_html( $text ),
		esc_attr( $color )
	);
}

/**
 * Shared bottom CTA block (white split card on a light section).
 *
 * @param string $heading Heading.
 * @param string $text    Supporting text.
 * @param array  $args    'secondary' => array('type'=>'phone'|'link'|'none','label','href').
 */
function sdi_cta_split( $heading, $text, $args = array() ) {
	$secondary = isset( $args['secondary'] ) ? $args['secondary'] : array( 'type' => 'phone' );
	?>
	<section style="background:var(--surface-page);">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(48px,6vw,80px) clamp(20px,5vw,32px);">
			<div class="sdi-reveal" data-reveal data-split style="display:grid;grid-template-columns:1.3fr 0.7fr;gap:32px;align-items:center;background:var(--white);border:1px solid var(--border-subtle);border-radius:var(--radius-3xl);box-shadow:var(--shadow-md);padding:clamp(28px,4vw,48px);">
				<div>
					<h2 style="font-family:var(--font-display);font-weight:700;font-size:clamp(24px,3vw,36px);line-height:1.1;letter-spacing:-0.02em;color:var(--text-strong);text-wrap:balance;"><?php echo esc_html( $heading ); ?></h2>
					<p style="margin-top:12px;font-size:16px;line-height:1.6;color:var(--text-muted);max-width:520px;"><?php echo esc_html( $text ); ?></p>
				</div>
				<div style="display:flex;flex-direction:column;gap:12px;">
					<?php echo sdi_button( array( 'label' => 'Nous contacter', 'variant' => 'primary', 'size' => 'lg', 'full' => true, 'href' => sdi_page_url( 'contact' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php if ( 'phone' === $secondary['type'] ) : ?>
						<a href="tel:+33980806296" class="sdi-btn-outline"><?php sdi_the_icon( 'phone', 18 ); ?>09 80 80 62 96</a>
					<?php elseif ( 'link' === $secondary['type'] ) : ?>
						<a href="<?php echo esc_url( $secondary['href'] ); ?>" class="sdi-btn-outline"><?php echo esc_html( $secondary['label'] ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Dark-hero breadcrumb.
 *
 * @param array $trail  Array of ['label'=>, 'href'=>(optional)] items; last has no href.
 * @return string
 */
function sdi_breadcrumb( $trail ) {
	$out = '<nav aria-label="Fil d\'ariane" class="sdi-crumbs">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $item ) {
		if ( $i > 0 ) {
			$out .= '<span aria-hidden="true">/</span>';
		}
		if ( ! empty( $item['href'] ) && $i !== $last ) {
			$out .= '<a class="sdi-crumb" href="' . esc_url( $item['href'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			$out .= '<span aria-current="page" style="color:var(--navy-300);">' . esc_html( $item['label'] ) . '</span>';
		}
	}
	$out .= '</nav>';
	return $out;
}
