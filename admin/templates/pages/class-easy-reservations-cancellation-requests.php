<?php
/**
 * This file is used for rendering the saved cancellation requests for reservations.
 *
 * @since      1.0.0
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/pages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the class file if previously it does not exist.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class that will list all the cancellation requests for the reservations.
 */
class Easy_Reservations_Cancellation_Requests extends WP_List_Table {

	/**
	 * Set up a constructor that references the parent constructor.
	 * We use the parent reference to set some default configs.
	 */
	public function __construct() {
		global $status, $page;

		parent::__construct( array(
			'singular' => __( 'reservation-cancellation record', 'easy-reservations' ),
			'plural'   => __( 'reservation-cancellation records', 'easy-reservations' ),
			'ajax'     => false,
		) );

		add_action( 'admin_head', array( $this, 'ersrv_admin_header_callback' ) );
	}

	/**
	 * Check for the current page in admin header.
	 */
	public function ersrv_admin_header_callback() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		// Return, if it's not the cancellation requests page.
		if ( is_null( $page ) || 'reservation-cancellation-requests' !== $page ) {
			return;
		}
	}

	/**
	 * Text to be displayed when no items are found.
	 */
	public function no_items() {
		esc_html_e( 'No cancellation requests found.', 'easy-reservations' );
	}

	/**
	 * Get the table data
	 *
	 * @return array
	 */
	private function table_data() {
		global $wpdb;
		$data                        = array();
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

			// Checkbox column.
			$temp['cb'] = '<input type="checkbox" />';

			// Get the date time of cancellation request.
			$cancellation_request_datetime = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_time', true );
			$temp['date_time']             = gmdate( ersrv_get_php_date_format() . ' H:i', $cancellation_request_datetime );

			// Get the item details now.
			$product_id      = wc_get_order_item_meta( $line_item_id, '_product_id', true );
			$product_name    = get_the_title( $product_id );
			$product_string  = "#{$product_id} {$product_name}";
			$temp['item']    = '<a href="' . get_edit_post_link( $product_id ) . '" title="' . $product_string . '">' . $product_string . '</a>';
			$temp['item_id'] = $line_item_id;

			// Get the item subtotal.
			$item_subtotal         = (float) wc_get_order_item_meta( $line_item_id, '_line_subtotal', true );
			$temp['item_subtotal'] = wc_price( $item_subtotal );

			// Get the order data.
			$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
			$customer_last_name  = get_post_meta( $order_id, '_billing_last_name', true );
			$order_string        = "#{$order_id} {$customer_first_name} {$customer_last_name}";
			$temp['order']       = '<a href="' . get_edit_post_link( $order_id ) . '" title="' . $order_string . '">' . $order_string . '</a>';
			$temp['order_id']    = $order_id;

			// Get the order status.
			$order_status         = $wc_order->get_status();
			$status_string        = ersrv_get_readable_order_status( $order_status );
			$temp['order_status'] = '<mark class="order-status status-' . $order_status . ' tips"><span>' . $status_string . '</span></mark>';

			// Get the cancellation status.
			$cancellation_status         = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_status', true );
			$cancellation_status         = ( ! empty( $cancellation_status ) ) ? ucfirst( $cancellation_status ) : __( 'Pending', 'easy-reservations' );
			$temp['cancellation_status'] = $cancellation_status;

			// Push all the data into an array.
			$data[] = $temp;
		}

		return $data;
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param array  $item        Data.
	 * @param string $column_name - Current column name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'date_time':
			case 'item':
			case 'item_subtotal':
			case 'order':
			case 'order_status':
			case 'cancellation_status':
				return $item[ $column_name ];

			default:
				return '--';
		}
	}

	/**
	 * Return the sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'date_time' => array( 'date_time', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'                  => '<input type="checkbox" />',
			'item'                => __( 'Item', 'easy-reservations' ),
			'date_time'           => __( 'DateTime', 'easy-reservations' ),
			'item_subtotal'       => __( 'Item Subtotal', 'easy-reservations' ),
			'order'               => __( 'Order', 'easy-reservations' ),
			'order_status'        => __( 'Order Status', 'easy-reservations' ),
			'cancellation_status' => __( 'Cancellation Status', 'easy-reservations' ),
		);

		return $columns;
	}

	/**
	 * The bulk actions array.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array();
		$actions = array(
			'bulk_approve_requests' => __( 'Approve', 'easy-reservations' ),
			'bulk_decline_requests' => __( 'Decline', 'easy-reservations' ),
		);

		return $actions;
	}


	/**
	 * Prepare the items for the table to process
	 *
	 * @return void
	 */
	public function prepare_items() {
		// check if a search was performed.
		$searched_keyword = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		$this->_column_headers = $this->get_column_info();

		// Fetch table data
		$data = $this->table_data();

		// check for individual row actions
//		$bulk_action = $this->current_action();
//		if ( ! empty( $bulk_action ) ) {
//			// check and process any actions such as bulk actions.
//			$data = $this->process_bulk_action( $data, $bulk_action );
//		}

		// Filter the table data in case of a search
		if ( $searched_keyword ) {
			$data = $this->filter_table_data( $data, $searched_keyword );
		}

		$per_page     = $this->get_items_per_page( 'ersrv_cancellation_requests_per_page', 20 );
		$current_page = $this->get_pagenum();

		$found_data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$total_items = count( $data );
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );

		$this->items = $found_data;
	}

	/**
	 * Filter the table data for search.
	 *
	 * @param array  $table_data List table data.
	 * @param string $search_key Search keyword.
	 *
	 * @return array
	 */
	public function filter_table_data( $table_data, $search_key ) {
		$filtered_table_data = array_values(
			array_filter( $table_data, function ( $row ) use ( $search_key ) {
				foreach ( $row as $row_val ) {
					if ( stripos( $row_val, $search_key ) !== false ) {
						return true;
					}
				}
			} ) );

		return $filtered_table_data;
	}

	/**
	 * The callback where the bulk actions are processed.
	 */
	public function process_bulk_action() {
		// Detect if the delete action was triggered.
		if ( 'bulk_delete_requests' === $this->current_action() ) {
			wp_die( 'Items deleted (or they would be if we had items to delete)!' );
		}
	}

	/**
	 * The column item to have row actions.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_item( $item ) {
		$item_id  = ( ! empty( $item['item_id'] ) ) ? $item['item_id'] : '';
		$order_id = ( ! empty( $item['order_id'] ) ) ? $item['order_id'] : '';

		// Build row actions.
		$actions = array(
			/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
			'approve_request' => sprintf( __( '%1$sApprove%2$s', 'easy-reservations' ), '<a href="javascript:void(0);" class="approve-request" title="' . esc_html__( 'Approve this cancellation request.', 'easy-reservations' ) . '">', '</a>' ),
			/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
			'decline_request' => sprintf( __( '%1$sDecline%2$s', 'easy-reservations' ), '<a href="javascript:void(0);" class="decline" title="' . esc_html__( 'Decline this cancellation request.', 'easy-reservations' ) . '">', '</a>' ),
		);

		// Return the title contents.
		/* translators: 1: %s: item name, 2: %s: row actions, 3: %s: div tag open, 4: %s: div tag closed */
		return sprintf(
			'%1$s%3$s%2$s%4$s',
			$item['item'],
			$this->row_actions( $actions ),
			'<div class="ersrv-cancellation-request-actions" data-item="' . $item_id . '" data-order="' . $order_id . '">',
			'</div>'
		);
	}
}
