<?php
/**
 * This file is used for rendering the saved cancellation requests for reservations.
 *
 * @since   1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/pages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the class file if previously it does not exist.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class that will list all the cancellation requests for the reservations.
 */
class Easy_Reservations_Cancellation_Requests extends WP_List_Table {
	/**
	 * Prepare the items for the table to process
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data     = $this->table_data();

		$perPage     = 10;
		$currentPage = $this->get_pagenum();
		$totalItems  = ( ! empty( $data ) ) ? count( $data ) : 0;

		$this->set_pagination_args(
			array(
				'total_items' => $totalItems,
				'per_page'    => $perPage
			)
		);

		// Slice the data array for pagination.
		if ( ! empty( $data ) ) {
			$data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );
		}

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'date_time'           => __( 'DateTime', 'easy-reservations' ),
			'item'                => __( 'Item', 'easy-reservations' ),
			'item_subtotal'       => __( 'Item Subtotal', 'easy-reservations' ),
			'order_id'            => __( 'Order', 'easy-reservations' ),
			'order_status'        => __( 'Order Status', 'easy-reservations' ),
			'actions'             => __( 'Actions', 'easy-reservations' ),
			'cancellation_status' => __( 'Cancellation Status', 'easy-reservations' ),
		);

		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns() {

		return array();
	}

	/**
	* Get the table data
	*
	* @return array
	*/
	private function table_data() {
		global $wpdb;
		$wc_order_items_meta_table   = "{$wpdb->prefix}woocommerce_order_itemmeta";
		$cancellation_requests_query = "SELECT `order_item_id` FROM `{$wc_order_items_meta_table}` WHERE `meta_key` = 'ersrv_cancellation_request'";
		$cancellation_requests       = $wpdb->get_results( $cancellation_requests_query );

		// Return blank data, if there is no cancellation request.
		if ( empty( $cancellation_requests ) || ! is_array( $cancellation_requests ) ) {
			return array();
		}

		// Iterate through the requests to prepare the data array.
		foreach ( $cancellation_requests as $cancellation_request_item_id ) {
			$line_item_id = $cancellation_request_item_id->order_item_id;
			$order_id     = wc_get_order_id_by_order_item_id( $line_item_id );
			$wc_order     = wc_get_order( $order_id );

			// Get the date time of cancellation request.
			$cancellation_request_datetime = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_time', true );
			$temp['date_time']             = gmdate( ersrv_get_php_date_format() . ' H:i', $cancellation_request_datetime );

			// Get the item details now.
			$product_id     = wc_get_order_item_meta( $line_item_id, '_product_id', true );
			$product_name   = get_the_title( $product_id );
			$product_string = "#{$product_id} {$product_name}";
			$temp['item']   = '<a href="' . get_edit_post_link( $product_id ) . '" title="' . $product_string . '">' . $product_string . '</a>';

			// Get the item subtotal.
			$item_subtotal         = (float) wc_get_order_item_meta( $line_item_id, '_line_subtotal', true );
			$temp['item_subtotal'] = wc_price( $item_subtotal );

			// Get the order data.
			$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
			$customer_last_name  = get_post_meta( $order_id, '_billing_last_name', true );
			$order_string        = "#{$order_id} {$customer_first_name} {$customer_last_name}";
			$temp['order_id']    = '<a href="' . get_edit_post_link( $order_id ) . '" title="' . $order_string . '">' . $order_string . '</a>';

			// Get the order status.
			$order_status         = $wc_order->get_status();
			$status_string        = ersrv_get_readable_order_status( $order_status );
			$temp['order_status'] = '<mark class="order-status status-' . $order_status . ' tips"><span>' . $status_string . '</span></mark>';

			// Actions.
			ob_start();
			?>
			<div class="ersrv-cancellation-request-actions" data-item="<?php echo esc_html( $line_item_id ); ?>">
				<button type="button" class="button button-secondary approve"><?php esc_html_e( 'Approve', 'easy-reservations' ); ?></button>
				<button type="button" class="button button-secondary decline"><?php esc_html_e( 'Decline', 'easy-reservations' ); ?></button>
				<button type="button" class="button button-secondary delete"><?php esc_html_e( 'Delete', 'easy-reservations' ); ?></button>
			</div>
			<?php
			$temp['actions'] = ob_get_clean();

			// Get the cancellation status.
			$cancellation_status         = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_status', true );
			$cancellation_status         = ( ! empty( $cancellation_status ) ) ? wcfirst( $cancellation_status ) : __( 'Pending', 'easy-reservations' );
			$temp['cancellation_status'] = $cancellation_status;

			// Push all the data into an array.
			$data[] = $temp;
		}

		return $data;
	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 3.1.0
	 */
	public function no_items() {
		$no_items_message = __( 'There are no cancellation requests yet!', 'easy-reservations' );

		/**
		 * This filter runs on the admin listing page of cancellation requests.
		 *
		 * This filter helps modifying the message that is displayed when there are no cancellation requests.
		 *
		 * @param string $no_items_message No items available message.
		 * @return string
		 * @since 1.0.0
		 */
		echo apply_filters( 'ersrv_no_cancellation_requests_found_message', $no_items_message );
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  array $item        Data
	 * @param  string $column_name - Current column name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'date_time':
			case 'item':
			case 'item_subtotal':
			case 'order_id':
			case 'order_status':
			case 'actions':
			case 'cancellation_status':
				return $item[ $column_name ];

			default:
				return '--';
		}
	}
}
