<?php
if ( ! isset( $_POST['coc_nonce'] )|| ! wp_verify_nonce( $_POST['coc_nonce'], 'coc_action' )) {

    wp_die('Sorry, something was insecure while processing your post, please go back and try again.');

} else {

    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '1.0.0');
    $response = $public_class->process_ccp_sagepay_transaction($_POST, 'test');
    
}
get_header(); ?>


<h2>HETAS Copy Certificate Confirmation Page</h2>


<?php if($response['response']->statusCode == '0000') { ?>

    <div class="bg-success" style="padding:20px;">
        <h4>Payment Successful</h4>
    </div>

    <?php
    echo '<pre>';
    print_r($response);
    echo '</pre>';
    ?>

<?php } else { ?>

    <div class="bg-danger" style="padding:20px;">

        <h4>There was a problem, your payment was unsuccessful!</h4>

        <p>We have logged this error, please report this issue to info@hetas.co.uk stating the time and date of the error.</p>
        
    
    </div>
<?php } ?>
     


<?php get_footer();?>