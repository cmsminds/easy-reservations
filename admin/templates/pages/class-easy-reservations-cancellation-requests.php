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
			'date_time' => __( 'Date & Time', 'easy-reservations' ),
			'item'      => __( 'Item', 'easy-reservations' ),
			'order_id'  => __( 'Order ID', 'easy-reservations' ),
			'actions'   => __( 'Actions', 'easy-reservations' ),
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
		$uploaded_resumes = get_option( 'vs-submitted-resumes' );
		$data             = array();

		if ( ! empty( $uploaded_resumes ) && is_array( $uploaded_resumes ) ) {
			$_upload     = wp_upload_dir();
			$_upload_dir = $_upload['basedir'];
			$_upload_dir = "{$_upload_dir}/vns-resumes/";
			$_upload_dir_url = $_upload['baseurl'] . '/vns-resumes/';

			foreach ( $uploaded_resumes as $key => $resume ) {
				// Actions.
				ob_start();
				?>
				<div class="vs-resume-actions">
					<?php var_dump( $_upload_dir_url ); ?>
					<form action="" method="POST">
						<input type="submit" class="button button-secondary" name="vs-delete-submitted-resume" value="<?php esc_html_e( 'Delete', 'easy-reservations' ); ?>">
						<input type="hidden" name="file_key" value="<?php echo $key; ?>" />
						<input type="hidden" name="file_name" value="<?php echo basename( $resume['file'] ); ?>" />
					</form>
					<a style="margin-top: 50px;" href="<?php echo esc_url( $_upload_dir_url . basename( $resume['file'] ) ); ?>" class="button button-secondary test" download><?php esc_html_e( 'Download Resume 123', 'easy-reservations' ); ?></a>
				</div>
				<?php
				$actions = ob_get_clean();

				// File preview.
				ob_start();
				?>
				<div style="height: 15rem;" id="<?php echo "vs_preview_file_{$key}" ?>"></div>
				<script>PDFObject.embed("<?php echo esc_attr( $_upload_dir_url . basename( $resume['file'] ) ); ?>", "#<?php echo "vs_preview_file_{$key}" ?>");</script>
				<?php
				$file_preview = ob_get_clean();

				$data[] = array(
					'date_time' => date( "F jS, Y, g:i A", strtotime( $resume['date_time'] ) ),
					'email'     => $resume['email'],
					'file'      => $file_preview,
					'actions'   => $actions,
				);
			}
		}

		return $data;

	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 3.1.0
	 */
	public function no_items() {

		esc_html_e( 'No submitted resumes found.', 'easy-reservations' );
		
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
			case 'email':
			case 'file':
			case 'actions':
				return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}

	}
}