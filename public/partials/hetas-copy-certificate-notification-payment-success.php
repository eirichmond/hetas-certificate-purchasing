<?php
    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '1.0.0');
    $response = $public_class->process_ccp_sagepay_transaction($_POST, 'test');
    //var_dump($response);
get_header();?>


<h2>HETAS Copy Certificate Confirmation Page</h2>

<?php if($response['response']->statusCode == '0000') { ?>

    <div class="bg-success">
        <h4>Payment Successful</h4>
    </div>

<?php } else { ?>

    <div class="bg-danger" style="padding:20px;">

        <h4>There was a problem, your payment was unsuccessful!</h4>

        <?php foreach($response['response']->errors as $errors ) { ?>
            <ul class="list-unstyled">
                <li><strong>Description: </strong><?php echo esc_html($errors->description); ?></li>
                <li><strong>Property: </strong><?php echo esc_html($errors->property); ?></li>
                <li><strong>Code: </strong><?php echo esc_html($errors->code); ?></li>
            </ul>
            
        <?php } ?>

        <p>Please report this issue to info@hetas.co.uk</p>
        
    
    </div>
<?php } ?>
     


<?php get_footer();?>