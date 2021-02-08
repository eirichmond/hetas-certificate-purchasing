<?php
class CCP_Async_Task extends WP_Async_Task {

	protected $action = 'is_invoice_paid';

	/**
	 * Prepare data for the asynchronous request
	 *
	 * @throws Exception If for any reason the request should not happen
	 *
	 * @param array $data An array of data sent to the hook
	 *
	 * @return array
	 */
	protected function prepare_data( $data ) {
        error_log('sleep');
        error_log('awake');
        $data = 'foobar';
        error_log($data);

        return array( 'data' => $data );
    }

	/**
	 * Run the async task action
	 */
	protected function run_action() {

        $data = $_POST['data'];
        if(isset($_POST[ 'data' ])) {
            error_log('RUN ACTION' . $_POST['data'] . $data);
            do_action( "wp_async_$this->action", $data );
        }
        
    }

}