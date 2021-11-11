<?php
/**
 * This file is used for templating the custom fields.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/metaboxes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Get the values from the database now.
$post_id         = get_the_ID();
$banner_image_id = get_post_meta( $post_id, 'ersrv_banner_image_id', true );
?>
<table class="form-table">
	<tobody>
		<tr>
			<th scope="row"><label for="ersrv-banner-image-id"><?php esc_html_e( 'Banner Image ID', 'easy-reservations' ); ?></label></th>
			<td>
				<input id="ersrv-banner-image-id" value="<?php echo esc_html( $banner_image_id ); ?>" name="banner_image_id" type="number" class="regular-text" />
				<p class="description"><?php echo wp_kses_post( sprintf( __( 'This holds the banner image file ID found under the %1$smedia%2$s menu.', 'easy-reservations' ), '<a target="_blank" href="' . admin_url( 'upload.php' ) . '">', '</a>' ) ); ?></p>
			</td>
		</tr>
	</tobody>
</table>
