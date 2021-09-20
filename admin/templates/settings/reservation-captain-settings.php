<?php
/**
 * This file is used for templating the reservation product blockout dates settings.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/settings
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$product_type_slug = ersrv_get_custom_product_type_slug();
$product_id        = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
?>
<div id="reservation_captain_settings_product_options" class="panel woocommerce_options_panel">
	<div class="options_group reservations-captain-settings">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Captain Settings', 'easy-reservations' ); ?></h4>
		<?php
		// Has captain.
		woocommerce_wp_checkbox(
			array(
				'id'                => 'has_captain',
				'label'             => __( 'Has Captain?', 'easy-reservations' ),
				'desc_tip'          => 'true',
				'description'       => __( 'This sets whether this reservation item comes with a captain or not.', 'easy-reservations' ),
				'value'             => '',
				'cbvalue'           => '',
			)
		);

		// Has captain text.
		woocommerce_wp_text_input(
			array(
				'id'                => 'has_captain_text',
				'label'             => __( 'Has Captain Text', 'easy-reservations' ),
				'placeholder'       => __( 'Example: With captain', 'easy-reservations' ),
				'desc_tip'          => 'true',
				'description'       => __( 'This sets the text to be displayed with the icon in the front.', 'easy-reservations' ),
				'type'              => 'text',
				'value'             => get_post_meta( $post->ID, '_ersrv_has_captain_text', true ),
			)
		);

		/**
		 * Hook that fires after the captain settings item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the captain settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_captain_settings', $product_type_slug );
		?>
	</div>
</div>
