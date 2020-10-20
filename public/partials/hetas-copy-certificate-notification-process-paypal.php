<?php 
header('Content-Type: application/json');

if ( ! isset( $_POST['coc_nonce'] )|| ! wp_verify_nonce( $_POST['coc_nonce'], 'coc_action' )) {

    wp_die('Sorry, something was insecure while processing your post, please go back and try again.');

} else {

    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '1.0.0');
    $response = $public_class->process_ccp_paypal_transaction($_POST);
    
}

wp_send_json($response);
wp_die();
?>