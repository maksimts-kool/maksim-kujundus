<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package maksim-kujundus
 */

if ( ! function_exists( 'maksim_kujundus_posted_on' ) ) {
	require_once get_template_directory() . '/inc/template-tags.php';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			if ( function_exists( 'maksim_kujundus_posted_on' ) ) {
				maksim_kujundus_posted_on();
			}
			if ( function_exists( 'maksim_kujundus_posted_by' ) ) {
				maksim_kujundus_posted_by();
			}
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
	if ( function_exists( 'maksim_kujundus_post_thumbnail' ) ) {
		maksim_kujundus_post_thumbnail();
	}
	?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php
		if ( function_exists( 'maksim_kujundus_entry_footer' ) ) {
			maksim_kujundus_entry_footer();
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
