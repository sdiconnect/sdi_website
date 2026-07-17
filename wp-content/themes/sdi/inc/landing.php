<?php
/**
 * Landing-page renderer: turns a structured config array into a designed page
 * (hero + typed sections + FAQ). Shared by all v1.0.1 marketing pages.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Render a full landing page from a config array.
 *
 * @param array $c Config (see inc/landing-data.php for the shape).
 */
function sdi_landing( $c ) {
	$c = wp_parse_args(
		$c,
		array(
			'eyebrow'   => '',
			'h1'        => get_the_title(),
			'intro'     => '',
			'crumbs'    => array(),
			'cta'       => array(),          // hero CTAs: array of ['label','href','style'=>'primary|ghost']
			'sections'  => array(),
			'final_cta' => array(),          // ['heading','text','secondary'=>...]
		)
	);

	sdi_landing_hero( $c );

	foreach ( (array) $c['sections'] as $section ) {
		$type = isset( $section['type'] ) ? $section['type'] : 'richtext';
		$fn   = 'sdi_landing_section_' . str_replace( '-', '_', $type );
		if ( function_exists( $fn ) ) {
			$fn( $section );
		}
	}

	if ( ! empty( $c['final_cta'] ) ) {
		$fc = $c['final_cta'];
		sdi_cta_split(
			isset( $fc['heading'] ) ? $fc['heading'] : 'Parlons de votre projet.',
			isset( $fc['text'] ) ? $fc['text'] : 'On analyse votre besoin et on vous propose une direction claire, chiffrée et sans engagement.',
			isset( $fc['secondary'] ) ? array( 'secondary' => $fc['secondary'] ) : array()
		);
	}

	sdi_landing_faq_schema( $c['sections'] );
}

/**
 * Hero (navy, halo + grid, breadcrumb, eyebrow, H1, intro, CTAs).
 *
 * @param array $c Config.
 */
function sdi_landing_hero( $c ) {
	$has_form = ! empty( $c['hero_form'] );
	?>
	<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
		<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
		<div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);-webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(52px,6vw,88px);">
			<?php if ( ! empty( $c['crumbs'] ) ) { echo sdi_breadcrumb( $c['crumbs'] ); // phpcs:ignore WordPress.Security.EscapeOutput
			} ?>
			<div<?php echo $has_form ? ' data-split style="margin-top:22px;display:grid;grid-template-columns:1.05fr 0.95fr;gap:clamp(36px,5vw,64px);align-items:center;"' : ''; ?>>
				<div>
					<?php if ( $c['eyebrow'] ) : ?>
						<div class="sdi-reveal sdi-eyebrow sdi-eyebrow--pill" data-reveal style="<?php echo $has_form ? '' : 'margin-top:22px;'; ?>color:var(--cyan-400);font-weight:500;"><?php echo esc_html( $c['eyebrow'] ); ?></div>
					<?php endif; ?>
					<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:22px;max-width:920px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.05;letter-spacing:-0.03em;color:#fff;text-wrap:balance;"><?php echo esc_html( $c['h1'] ); ?></h1>
					<?php if ( $c['intro'] ) : ?>
						<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:22px;max-width:660px;font-size:clamp(17px,1.4vw,20px);line-height:1.6;color:var(--navy-300);"><?php echo esc_html( $c['intro'] ); ?></p>
					<?php endif; ?>
					<?php if ( $has_form && ! empty( $c['hero_form']['bullets'] ) ) : ?>
						<div class="sdi-reveal" data-reveal data-reveal-delay="220" style="margin-top:26px;display:flex;flex-direction:column;gap:12px;">
							<?php foreach ( $c['hero_form']['bullets'] as $b ) : ?>
								<span style="display:inline-flex;align-items:center;gap:10px;font-size:15px;color:var(--navy-200);"><span style="color:var(--green-400);display:inline-flex;"><?php sdi_the_icon( 'check-circle-2', 18 ); ?></span><?php echo esc_html( $b ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! $has_form && ! empty( $c['cta'] ) ) : ?>
						<div class="sdi-reveal" data-reveal data-reveal-delay="240" style="margin-top:32px;display:flex;flex-wrap:wrap;gap:14px;">
							<?php foreach ( $c['cta'] as $btn ) : ?>
								<?php if ( 'ghost' === ( $btn['style'] ?? 'primary' ) ) : ?>
									<a href="<?php echo esc_url( $btn['href'] ); ?>" class="sdi-ghost-dark"><?php echo esc_html( $btn['label'] ); ?></a>
								<?php else : ?>
									<a href="<?php echo esc_url( $btn['href'] ); ?>" class="sdi-btn sdi-btn--primary sdi-btn--lg"><?php echo esc_html( $btn['label'] ); ?></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( $has_form ) : ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="120">
						<div style="background:var(--white);border-radius:var(--radius-3xl);padding:clamp(24px,3vw,34px);box-shadow:0 40px 90px rgba(7,11,22,0.5);">
							<?php sdi_contact_form( $c['hero_form']['heading'] ?? 'Recevez votre devis gratuit', $c['hero_form']['source'] ?? '' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Section header (eyebrow + h2 + optional lead) on a light or dark background.
 *
 * @param array  $s    Section (uses eyebrow, title, lead).
 * @param string $mode 'light' or 'dark'.
 */
function sdi_landing_head( $s, $mode = 'light' ) {
	$eyebrow_color = 'dark' === $mode ? 'var(--cyan-400)' : ( $s['accent'] ?? 'var(--brand-primary)' );
	$h2_class      = 'dark' === $mode ? 'sdi-h2 sdi-h2--dark' : 'sdi-h2';
	?>
	<div class="sdi-reveal" data-reveal style="max-width:720px;">
		<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
			<span class="sdi-eyebrow" style="color:<?php echo esc_attr( $eyebrow_color ); ?>;"><?php echo esc_html( $s['eyebrow'] ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $s['title'] ) ) : ?>
			<h2 class="<?php echo esc_attr( $h2_class ); ?>" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);"><?php echo esc_html( $s['title'] ); ?></h2>
		<?php endif; ?>
		<?php if ( ! empty( $s['lead'] ) ) : ?>
			<p style="margin-top:18px;font-size:18px;line-height:1.6;color:<?php echo 'dark' === $mode ? 'var(--navy-300)' : 'var(--text-muted)'; ?>;"><?php echo esc_html( $s['lead'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Feature cards grid. items: [ icon, title, desc, points(optional) ].
 *
 * @param array $s Section.
 */
function sdi_landing_section_features( $s ) {
	$bg = $s['bg'] ?? 'page';
	$bgc = 'white' === $bg ? 'var(--white)' : 'var(--surface-page)';
	?>
	<section style="background:<?php echo esc_attr( $bgc ); ?>;<?php echo ( 'white' === $bg ) ? 'border-top:1px solid var(--border-subtle);' : ''; ?>">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<?php sdi_landing_head( $s ); ?>
			<div style="margin-top:36px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
				<?php $d = 0; foreach ( (array) $s['items'] as $it ) : ?>
					<div class="sdi-reveal sdi-card sdi-card--sm" data-reveal data-reveal-delay="<?php echo esc_attr( $d ); ?>" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);padding:28px;">
						<div style="width:52px;height:52px;border-radius:15px;background:var(--blue-50);color:var(--brand-primary);display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $it['icon'], 24 ); ?></div>
						<h3 style="margin-top:18px;font-family:var(--font-display);font-weight:600;font-size:20px;letter-spacing:-0.01em;color:var(--text-strong);"><?php echo esc_html( $it['title'] ); ?></h3>
						<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $it['desc'] ); ?></p>
						<?php if ( ! empty( $it['points'] ) ) : ?>
							<div style="margin-top:16px;display:flex;flex-direction:column;gap:9px;">
								<?php foreach ( $it['points'] as $pt ) : ?>
									<div style="display:flex;align-items:flex-start;gap:9px;font-size:14px;color:var(--text-body);"><span style="margin-top:1px;color:var(--brand-primary);flex-shrink:0;display:inline-flex;"><?php sdi_the_icon( 'check', 16 ); ?></span><?php echo esc_html( $pt ); ?></div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php $d += 60; endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Split section: copy on the left, checklist card on the right.
 * s: eyebrow, title, body (array of paragraphs), points (array), image(optional), reverse(bool).
 *
 * @param array $s Section.
 */
function sdi_landing_section_checklist( $s ) {
	$bg  = $s['bg'] ?? 'white';
	$bgc = 'page' === $bg ? 'var(--surface-page)' : 'var(--white)';
	?>
	<section style="background:<?php echo esc_attr( $bgc ); ?>;border-top:1px solid var(--border-subtle);">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<div data-split-900 style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(36px,5vw,64px);align-items:center;">
				<div class="sdi-reveal" data-reveal>
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?><span class="sdi-eyebrow" style="color:var(--brand-primary);"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
					<h2 style="margin-top:16px;font-family:var(--font-display);font-weight:700;font-size:clamp(26px,3.4vw,42px);line-height:1.08;letter-spacing:-0.025em;color:var(--text-strong);text-wrap:balance;"><?php echo esc_html( $s['title'] ); ?></h2>
					<?php foreach ( (array) ( $s['body'] ?? array() ) as $p ) : ?>
						<p style="margin-top:14px;font-size:16px;line-height:1.65;color:var(--text-muted);"><?php echo esc_html( $p ); ?></p>
					<?php endforeach; ?>
				</div>
				<div class="sdi-reveal" data-reveal data-reveal-delay="120" style="background:var(--white);border:1px solid var(--border-subtle);border-radius:var(--radius-3xl);box-shadow:var(--shadow-md);padding:clamp(26px,3vw,38px);">
					<?php if ( ! empty( $s['card_title'] ) ) : ?>
						<h3 style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--text-muted);font-weight:600;"><?php echo esc_html( $s['card_title'] ); ?></h3>
					<?php endif; ?>
					<div style="margin-top:16px;display:flex;flex-direction:column;gap:14px;">
						<?php foreach ( (array) ( $s['points'] ?? array() ) as $pt ) : ?>
							<div style="display:flex;align-items:flex-start;gap:11px;font-size:15px;line-height:1.5;color:var(--text-body);"><span style="margin-top:1px;color:var(--brand-primary);flex-shrink:0;display:inline-flex;"><?php sdi_the_icon( 'check-circle-2', 19 ); ?></span><?php echo esc_html( $pt ); ?></div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Numbered steps (navy background). items: [ title, desc ].
 *
 * @param array $s Section.
 */
function sdi_landing_section_steps( $s ) {
	?>
	<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
		<div style="position:absolute;top:-20%;right:-5%;width:700px;height:520px;background:radial-gradient(ellipse at center,rgba(0,165,228,0.15) 0%,rgba(0,165,228,0) 65%);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<?php sdi_landing_head( $s, 'dark' ); ?>
			<div style="margin-top:34px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
				<?php $i = 1; $d = 0; foreach ( (array) $s['items'] as $it ) : ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $d ); ?>" style="border:1px solid var(--border-inverse);border-radius:var(--radius-xl);padding:24px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));">
						<span style="font-family:var(--font-mono);font-weight:700;font-size:14px;color:var(--cyan-400);letter-spacing:0.06em;"><?php echo esc_html( sprintf( '%02d', $i ) ); ?></span>
						<h3 style="margin-top:12px;font-size:17px;font-weight:600;color:#fff;"><?php echo esc_html( $it['title'] ); ?></h3>
						<p style="margin-top:7px;font-size:14px;line-height:1.5;color:var(--navy-300);"><?php echo esc_html( $it['desc'] ); ?></p>
					</div>
				<?php $i++; $d += 60; endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Stat row (navy gradient). items: [ value, label, color(optional) ].
 *
 * @param array $s Section.
 */
function sdi_landing_section_stats( $s ) {
	$colors = array( 'var(--blue-400)', 'var(--cyan-400)', 'var(--blue-400)', 'var(--magenta-400)' );
	?>
	<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
		<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,5vw,68px) clamp(20px,5vw,32px);">
			<?php if ( ! empty( $s['title'] ) ) : ?>
				<div class="sdi-reveal" data-reveal style="max-width:720px;margin-bottom:34px;">
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?><span class="sdi-eyebrow" style="color:var(--cyan-400);"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
					<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:16px;font-size:clamp(26px,3.4vw,42px);"><?php echo esc_html( $s['title'] ); ?></h2>
				</div>
			<?php endif; ?>
			<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px 24px;">
				<?php $i = 0; $d = 0; foreach ( (array) $s['items'] as $it ) : $col = $it['color'] ?? $colors[ $i % 4 ]; ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $d ); ?>" style="text-align:center;">
						<div style="font-family:var(--font-display);font-weight:700;font-size:clamp(40px,5vw,60px);line-height:1;letter-spacing:-0.03em;color:<?php echo esc_attr( $col ); ?>;"><?php echo esc_html( $it['value'] ); ?></div>
						<div style="margin-top:12px;font-size:15px;color:var(--navy-300);font-weight:500;"><?php echo esc_html( $it['label'] ); ?></div>
					</div>
				<?php $i++; $d += 80; endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Rich prose block (crawlable SEO copy). s: eyebrow, title, html (trusted markup).
 *
 * @param array $s Section.
 */
function sdi_landing_section_richtext( $s ) {
	$bg  = $s['bg'] ?? 'page';
	$bgc = 'white' === $bg ? 'var(--white)' : 'var(--surface-page)';
	?>
	<section style="background:<?php echo esc_attr( $bgc ); ?>;<?php echo ( 'white' === $bg ) ? 'border-top:1px solid var(--border-subtle);' : ''; ?>">
		<div style="max-width:900px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<?php if ( ! empty( $s['eyebrow'] ) || ! empty( $s['title'] ) ) : ?>
				<div class="sdi-reveal" data-reveal style="margin-bottom:24px;">
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?><span class="sdi-eyebrow" style="color:var(--brand-primary);"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
					<?php if ( ! empty( $s['title'] ) ) : ?><h2 class="sdi-h2" style="margin-top:16px;font-size:clamp(26px,3.4vw,40px);"><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="sdi-reveal sdi-prose" data-reveal><?php echo wp_kses_post( $s['html'] ); ?></div>
		</div>
	</section>
	<?php
}

/**
 * Internal-link grid (maillage interne / zones). items: [ label, href, desc(optional) ].
 *
 * @param array $s Section.
 */
function sdi_landing_section_links( $s ) {
	?>
	<section style="background:var(--white);border-top:1px solid var(--border-subtle);">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<?php sdi_landing_head( $s ); ?>
			<div style="margin-top:32px;display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
				<?php $d = 0; foreach ( (array) $s['items'] as $it ) : ?>
					<a href="<?php echo esc_url( $it['href'] ); ?>" class="sdi-reveal sdi-card sdi-card--sm" data-reveal data-reveal-delay="<?php echo esc_attr( $d ); ?>" style="display:block;padding:22px 24px;border-radius:var(--radius-xl);border:1px solid var(--border-subtle);background:var(--surface-page);">
						<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
							<span style="font-family:var(--font-display);font-weight:600;font-size:17px;color:var(--text-strong);"><?php echo esc_html( $it['label'] ); ?></span>
							<span style="color:var(--brand-primary);display:inline-flex;"><?php sdi_the_icon( 'arrow-right', 17 ); ?></span>
						</div>
						<?php if ( ! empty( $it['desc'] ) ) : ?><p style="margin-top:8px;font-size:14px;line-height:1.5;color:var(--text-muted);"><?php echo esc_html( $it['desc'] ); ?></p><?php endif; ?>
					</a>
				<?php $d += 40; endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * FAQ accordion (native <details>, zero-JS, accessible). items: [ q, a ].
 *
 * @param array $s Section.
 */
function sdi_landing_section_faq( $s ) {
	?>
	<section style="background:var(--surface-page);">
		<div style="max-width:900px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<div class="sdi-reveal" data-reveal style="margin-bottom:28px;">
				<span class="sdi-eyebrow" style="color:var(--brand-primary);"><?php echo esc_html( $s['eyebrow'] ?? '// FAQ' ); ?></span>
				<h2 class="sdi-h2" style="margin-top:16px;font-size:clamp(26px,3.4vw,42px);"><?php echo esc_html( $s['title'] ?? 'Questions fréquentes' ); ?></h2>
			</div>
			<div class="sdi-reveal sdi-faq" data-reveal>
				<?php foreach ( (array) $s['items'] as $qa ) : ?>
					<details class="sdi-faq__item">
						<summary class="sdi-faq__q"><span><?php echo esc_html( $qa['q'] ); ?></span><span class="sdi-faq__chevron"><?php sdi_the_icon( 'arrow-right', 18 ); ?></span></summary>
						<div class="sdi-faq__a"><?php echo wp_kses_post( wpautop( $qa['a'] ) ); ?></div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Social-proof section: rating summary + review cards (from the Customizer).
 *
 * @param array $s Section.
 */
function sdi_landing_section_reviews( $s ) {
	$reviews = function_exists( 'sdi_get_reviews' ) ? sdi_get_reviews() : array();
	if ( empty( $reviews ) ) {
		return;
	}
	$rating = function_exists( 'sdi_rating_value' ) ? sdi_rating_value() : '4,9';
	$count  = function_exists( 'sdi_rating_count' ) ? sdi_rating_count() : 109;
	?>
	<section style="background:var(--surface-page);">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<div class="sdi-reveal" data-reveal style="display:flex;flex-wrap:wrap;align-items:center;gap:16px;justify-content:space-between;">
				<div>
					<span class="sdi-eyebrow" style="color:var(--brand-primary);"><?php echo esc_html( $s['eyebrow'] ?? '// Avis clients' ); ?></span>
					<h2 class="sdi-h2" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);"><?php echo esc_html( $s['title'] ?? 'Ce que disent nos clients.' ); ?></h2>
				</div>
				<div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-radius:var(--radius-xl);background:var(--white);border:1px solid var(--border-subtle);box-shadow:var(--shadow-sm);">
					<div style="font-family:var(--font-display);font-weight:700;font-size:34px;color:var(--text-strong);line-height:1;"><?php echo esc_html( $rating ); ?></div>
					<div><?php echo sdi_stars( 5, 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div style="font-size:12px;color:var(--text-muted);margin-top:3px;"><?php echo esc_html( $count ); ?> avis Google</div></div>
				</div>
			</div>
			<div style="margin-top:44px;display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:22px;">
				<?php foreach ( $reviews as $r ) : ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $r['delay'] ); ?>" style="padding:30px 28px;border-radius:var(--radius-2xl);background:var(--white);border:1px solid var(--border-subtle);box-shadow:var(--shadow-md);display:flex;flex-direction:column;">
						<?php echo sdi_stars( $r['rating'], 17 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<p style="margin-top:18px;font-size:16px;line-height:1.6;color:var(--text-body);flex:1;"><?php echo esc_html( $r['quote'] ); ?></p>
						<div style="margin-top:22px;display:flex;align-items:center;gap:12px;">
							<div style="width:44px;height:44px;border-radius:50%;background:var(--gradient-brand);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;"><?php echo esc_html( $r['initials'] ); ?></div>
							<div><div style="font-weight:600;color:var(--text-strong);font-size:15px;"><?php echo esc_html( $r['name'] ); ?></div><div style="font-size:13px;color:var(--text-muted);"><?php echo esc_html( $r['meta'] ); ?></div></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Centered lead-capture form section. s: eyebrow, title, lead, heading, source.
 *
 * @param array $s Section.
 */
function sdi_landing_section_leadform( $s ) {
	?>
	<section id="devis" style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
		<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:1000px;height:640px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.2) 0%,rgba(29,110,255,0) 62%);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<div data-split style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(40px,5vw,72px);align-items:center;">
				<div class="sdi-reveal" data-reveal>
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?><span class="sdi-eyebrow" style="color:var(--cyan-400);"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
					<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:16px;"><?php echo esc_html( $s['title'] ?? 'Parlons de votre projet.' ); ?></h2>
					<?php if ( ! empty( $s['lead'] ) ) : ?><p style="margin-top:18px;font-size:18px;line-height:1.6;color:var(--navy-300);max-width:460px;"><?php echo esc_html( $s['lead'] ); ?></p><?php endif; ?>
					<?php if ( ! empty( $s['bullets'] ) ) : ?>
						<div style="margin-top:26px;display:flex;flex-direction:column;gap:12px;">
							<?php foreach ( $s['bullets'] as $b ) : ?>
								<span style="display:inline-flex;align-items:center;gap:10px;font-size:15px;color:var(--navy-200);"><span style="color:var(--green-400);display:inline-flex;"><?php sdi_the_icon( 'check-circle-2', 18 ); ?></span><?php echo esc_html( $b ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="sdi-reveal" data-reveal data-reveal-delay="120">
					<div style="background:var(--white);border-radius:var(--radius-3xl);padding:clamp(24px,3vw,36px);box-shadow:0 40px 90px rgba(7,11,22,0.5);">
						<?php sdi_contact_form( $s['heading'] ?? 'Recevez votre devis gratuit', $s['source'] ?? '' ); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Emit FAQPage JSON-LD from any faq sections (skipped when Yoast is active,
 * as Yoast owns the schema graph — see sdi_seo_yoast_active()).
 *
 * @param array $sections Sections config.
 */
function sdi_landing_faq_schema( $sections ) {
	// FAQ schema is page-specific; Yoast free won't emit it unless FAQ blocks are
	// used, so we always output it for our landing pages.
	$faqs = array();
	foreach ( (array) $sections as $s ) {
		if ( ( $s['type'] ?? '' ) === 'faq' && ! empty( $s['items'] ) ) {
			foreach ( $s['items'] as $qa ) {
				$faqs[] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $qa['q'] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $qa['a'] ),
					),
				);
			}
		}
	}
	if ( empty( $faqs ) ) {
		return;
	}
	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faqs,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
