<?php
/**
 * Fallback template (blog index / archives / search).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,72px) clamp(20px,5vw,32px);">
		<h1 style="font-family:var(--font-display);font-weight:700;font-size:clamp(28px,4vw,48px);line-height:1.05;letter-spacing:-0.03em;color:#fff;">
			<?php
			if ( is_search() ) {
				printf( 'Résultats pour « %s »', esc_html( get_search_query() ) );
			} elseif ( is_archive() ) {
				the_archive_title();
			} else {
				echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: 'Actualités' );
			}
			?>
		</h1>
	</div>
</section>

<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(48px,6vw,80px) clamp(20px,5vw,32px);">
		<?php if ( have_posts() ) : ?>
			<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="sdi-card" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);overflow:hidden;">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" style="display:block;"><?php the_post_thumbnail( 'sdi-cover', array( 'style' => 'width:100%;height:190px;object-fit:cover;display:block;' ) ); ?></a>
						<?php endif; ?>
						<div style="padding:24px;">
							<h2 style="font-family:var(--font-display);font-weight:600;font-size:20px;color:var(--text-strong);line-height:1.25;"><a href="<?php the_permalink(); ?>" style="color:var(--text-strong);"><?php the_title(); ?></a></h2>
							<p style="margin-top:10px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a href="<?php the_permalink(); ?>" style="margin-top:14px;display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:600;color:var(--brand-primary);">Lire la suite <?php sdi_the_icon( 'arrow-right', 15 ); ?></a>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<div style="margin-top:40px;"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
		<?php else : ?>
			<p style="font-size:17px;color:var(--text-muted);">Aucun contenu pour le moment.</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
