<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package maksim-kujundus
 */

/**
 * Generate a readable, deterministic color for a taxonomy term.
 *
 * @param int $term_id Term ID.
 * @return string Hex color, e.g. #22c55e
 */
function maksim_kujundus_term_color_hex( $term_id ) {
	$term_id = absint( $term_id );

	// Spread hues nicely across categories.
	$hue = ( $term_id * 47 ) % 360; // 0..359
	$sat = 68; // percent
	$lit = 42; // percent

	return maksim_kujundus_hsl_to_hex( $hue, $sat, $lit );
}

/**
 * Convert HSL to hex.
 *
 * @param int|float $h Hue 0..360
 * @param int|float $s Saturation 0..100
 * @param int|float $l Lightness 0..100
 * @return string Hex color, e.g. #16a34a
 */
function maksim_kujundus_hsl_to_hex( $h, $s, $l ) {
	$h = fmod( (float) $h, 360.0 );
	if ( $h < 0 ) {
		$h += 360.0;
	}

	$s = max( 0.0, min( 100.0, (float) $s ) ) / 100.0;
	$l = max( 0.0, min( 100.0, (float) $l ) ) / 100.0;

	$c  = ( 1.0 - abs( 2.0 * $l - 1.0 ) ) * $s;
	$hp = $h / 60.0;
	$x  = $c * ( 1.0 - abs( fmod( $hp, 2.0 ) - 1.0 ) );

	$r1 = 0.0;
	$g1 = 0.0;
	$b1 = 0.0;

	if ( $hp >= 0.0 && $hp < 1.0 ) {
		$r1 = $c;
		$g1 = $x;
	} elseif ( $hp < 2.0 ) {
		$r1 = $x;
		$g1 = $c;
	} elseif ( $hp < 3.0 ) {
		$g1 = $c;
		$b1 = $x;
	} elseif ( $hp < 4.0 ) {
		$g1 = $x;
		$b1 = $c;
	} elseif ( $hp < 5.0 ) {
		$r1 = $x;
		$b1 = $c;
	} else {
		$r1 = $c;
		$b1 = $x;
	}

	$m = $l - ( $c / 2.0 );
	$r = (int) round( ( $r1 + $m ) * 255.0 );
	$g = (int) round( ( $g1 + $m ) * 255.0 );
	$b = (int) round( ( $b1 + $m ) * 255.0 );

	$r = max( 0, min( 255, $r ) );
	$g = max( 0, min( 255, $g ) );
	$b = max( 0, min( 255, $b ) );

	return sprintf( '#%02x%02x%02x', $r, $g, $b );
}

if ( ! function_exists( 'maksim_kujundus_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function maksim_kujundus_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'maksim-kujundus' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'maksim_kujundus_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function maksim_kujundus_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'maksim-kujundus' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'maksim_kujundus_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function maksim_kujundus_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				$category_links = array();
				foreach ( $categories as $category ) {
					$color = maksim_kujundus_term_color_hex( $category->term_id );
					$category_links[] = sprintf(
						'<a class="cat-badge cat-%1$s" href="%2$s" style="--cat-color:%3$s;">%4$s</a>',
						esc_attr( $category->slug ),
						esc_url( get_category_link( $category ) ),
						esc_attr( $color ),
						esc_html( $category->name )
					);
				}

				$posted_in = sprintf(
					/* translators: %s: list of categories. */
					__( 'Posted in %s', 'maksim-kujundus' ),
					implode( ' ', $category_links )
				);

				echo '<span class="cat-links">' . wp_kses_post( $posted_in ) . '</span>';
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'maksim-kujundus' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'maksim-kujundus' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'maksim-kujundus' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'maksim_kujundus_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function maksim_kujundus_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
