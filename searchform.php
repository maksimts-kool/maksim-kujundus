<?php
/**
 * The template for displaying the search form.
 *
 * @package maksim-kujundus
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'maksim-kujundus' ); ?></span>
		<input
			type="search"
			class="search-field"
				placeholder="<?php echo esc_attr_x( 'Search \u2026', 'placeholder', 'maksim-kujundus' ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
			aria-label="<?php echo esc_attr_x( 'Search', 'aria label', 'maksim-kujundus' ); ?>"
		/>
	</label>
	<button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'maksim-kujundus' ); ?></button>
</form>
